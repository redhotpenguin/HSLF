<?php

/**
 * 
 * Execute with:
  $csvHeaders = array(
  '_id' => 'Identifier',
  'tenant_id' => 'Tenant ID',
  'device_type' => 'Device Type',
  'ua_identifier' => 'Urban Airship Identifier',
  'districts' => 'Districts',
  'action_taken' => 'Action Taken',
  'tags' => 'Tags',
  'name' => 'Name',
  'email' => 'Email',
  'home_address' => 'Home Address',
  'phone_number' => 'Phone Number',
  'registration_date' => 'Registration Date',
  'last_connection_date' => 'Last Connection date',
  );
 * 
  $filterAttributes['ua_identifier'] = array('$exists' => true);


  $parameters = array(
  'tenant_id' => 1,
  'email' => 'jonas.palmero@gmail.com',
  'mongodb_host' => 'mongodb://localhost:27017',
  'mongodb_name' => 'mobile_advocacy_platform',
  'mongodb_username' => 'admin',
  'mongodb_password' => 'admin',
  'mongodb_time_out' => 5000,
  'mongodb_collection_name' => 'mobile_user',
  'csvHeaders' => $csvHeaders,
  'filterAttributes' => $filterAttributes,

  );
 * Yii::app()->queue->enqueue('mobile_platform', 'MobileUserExportJob', $parameters);
 */
require_once('lib/S3.php');
require_once('lib/sendgrid/SendGrid_loader.php');
require_once('config/MobileUserExportJobConfig.php');

/**
 * export and save mobile users report on S3
 * Send an email when the job is complete
 * 
 */
class MobileUserExportJob {

    private $db;
    private $collection;
    private $mongoClient;
    private $s3AKey = S3_AKEY;
    private $s3SKey = S3_SKEY;
    private $s3Bucket = S3_BUCKET;
    private $s3QueueDirectory = 'reports';
    private $sendgridUsername = SENDGRID_USERNAME;
    private $sendgridPassword = SENDGRID_PASSWORD;

    public function perform() {

        $startTime = microtime(true);

        // argument validations
        if (!isset($this->args['tenant_id']) && !is_numeric($this->args['tenant_id'])) {
            return "Tenant id is missing or invalid";
        }

        if (!$this->mongoDBConnect($this->args['mongodb_collection_name'])) {
            return "Could not connect to mongodb server";
        }

        if (!isset($this->args['csvHeaders'])) {
            error_log("Missing CSV header. Aborting");
            return false;
        }

        if (!is_array($this->args['csvHeaders'])) {
            error_log("CSV header format error. Aborting");
            return false;
        }

        if (isset($this->args['filterAttributes']) && is_array($this->args['filterAttributes']))
            $filterAttributes = $this->args['filterAttributes'];

        else
            $filterAttributes = array();

        $this->args['filterAttributes']['tenant_id'] = $this->args['tenant_id'];

        printf('Starting report for tenant %d \n', $this->args['tenant_id']);

        $result = $this->generateExport();

        if ($result === false) {
            error_log("could not generate export. Aborting");
            return false;
        }

        $this->sendResultEmail($result);


        $completeTime = microtime(true) - $startTime;

        printf('Finished exporting user data for tenant %d in %s seconds', $this->args['tenant_id'], $completeTime);
        $memoryUsed = memory_get_peak_usage(true);
        $memoryUsed = $memoryUsed / 1024;
        $memoryUsed = $memoryUsed / 1024;
        printf("Used %s MB ", $memoryUsed);
    }
   

    /**
     * Generate and upload an export file to S3
     * @return mixed download link or false 
     */
    private function generateExport() {

        $tmpFileName = 'tid_' . $this->args['tenant_id'] . '_';
        $tmpFileName .=date('Y-m-d\-h-i-s');

        $tmpFileName .= '_' . microtime(true);
        $tmpFileName.='.csv';

        $tmpFilePath = sys_get_temp_dir() . '/' . $tmpFileName;


        $fp = fopen($tmpFilePath, 'w');

        if (!$fp) {
            error_log("Could not open $tmpFilePath. Aborting");
            return false;
        }

        fputcsv($fp, $this->args['csvHeaders']);

        $mobileUserCursor = $this->collection->find($this->args['filterAttributes']);

        foreach ($mobileUserCursor as $mobileUser) {
            $row = array();
            foreach ($this->args['csvHeaders'] as $head => $friendlyHeadName) {
                $data = null;

                if (isset($mobileUser[$head])) {
                    if (is_array($mobileUser[$head])) {
                        $data = implode(', ', $mobileUser[$head]);
                    } elseif ($mobileUser[$head] instanceof MongoDate) {
                        $data = date('m-d-Y h:i:s', $mobileUser[$head]->sec);
                    } else {
                        $data = $mobileUser[$head];
                    }
                }

                $row[] = $data;
            }
            fputcsv($fp, $row);
        }


        rewind($fp);
        fclose($fp);

        S3::setAuth($this->s3AKey, $this->s3SKey);

        $uploadResult = S3::putObjectFile($tmpFilePath, $this->s3Bucket, $this->s3QueueDirectory . "/" . $tmpFileName, $acl = S3::ACL_PUBLIC_READ, array(), "text/csv");

        if ($uploadResult !== true) {
            error_log("Could not open upload file to S3:");
            error_log(print_r($uploadResult, true));
        }

        unlink($tmpFilePath);

        $exportDownloadLink = 'https://s3.amazonaws.com/' . $this->s3Bucket . '/' . $this->s3QueueDirectory . '/' . $tmpFileName;

        return $exportDownloadLink;
    }

    /**
     * Send an email with a confirmation link
     * @param string link to the export file
     */
    private function sendResultEmail($exportUrl) {

        $htmlBody = '<h3>Hi';

        if (isset($this->args['tenant_name']))
            $htmlBody.= ' ' . $this->args['tenant_name'] . ',';
        else
            $htmlBody.=',';

        $htmlBody.='</h3><p>';

        $htmlBody .= "Your user export is ready and available at <a href='$exportUrl'>$exportUrl</a>";

        $htmlBody.='</p>';

        $htmlBody.='<em>The Winning Mark robot - mobile@winningmark.com</em>';

        try {
            $sendgrid = new SendGrid($this->sendgridUsername, $this->sendgridPassword);

            $mail = new SendGrid\Mail();
            $mail->
                    addTo('jonas.palmero@gmail.com')->
                    setFrom('mobile@winningmark.com')->
                    setSubject('[Winning Mark Mobile] Your user export is ready')->
                    setText('Download export: ' . $exportUrl)->
                    setHtml($htmlBody);

            $sendgrid->smtp->send($mail);
        } catch (Exception $e) {
            error_log("could not deliver email:" . $e->getMessage());
            return false;
        }
    }

    /**
     * Connect to mongodb and select a collection
     * @param string $collectionName collection name
     * @return boolean true on success - false if can't connect to server
     */
    private function mongoDBConnect($collectionName) {
        try {
            $this->mongoClient = new MongoClient($this->args['mongodb_host'], array(
                        'db' => $this->args['mongodb_name'],
                        'password' => $this->args['mongodb_password'],
                        'username' => $this->args['mongodb_username'],
                        'timeout' => $this->args['mongodb_time_out'],
                    )); // connect
        } catch (MongoConnectionException $e) {
            printf("Could not connect to mongodb database: %s\n ", $e->getMessage());
            return false;
        }

        $this->db = $this->mongoClient->selectDB($this->args['mongodb_name']);

        try {
            $this->collection = new MongoCollection($this->db, $collectionName); // @todo: exception handling
        } catch (Exception $e) {
            printf("Could not get a collection: %s\n ", $e->getMessage());
            return false;
        }

        return true;
    }

}