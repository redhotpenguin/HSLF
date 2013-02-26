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

/**
 * export and save mobile users report on S3
 * Send an email when the job is complete
 * 
 * @todo: move s3 credentials to the config file
 */
class MobileUserExportJob {

    private $db;
    private $collection;
    private $mongoClient;
    private $s3AKey = 'AKIAIDNK7VPB47DB2F2Q';
    private $s3SKey = '2F7TBdQsokQVpIZAgNUx/PgKyE01wz3AXLmGFYvh';
    private $s3Bucket = 'maplocal';
    private $s3QueueDirectory = 'reports';

    public function perform() {

        $startTime = microtime(true);

        if (!isset($this->args['tenant_id']) && !is_numeric($this->args['tenant_id'])) {
            return "Tenant id is missing or invalid";
        }

        printf('Starting report for tenant %d \n', $this->args['tenant_id']);


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

        $filterAttributes['tenant_id'] = $this->args['tenant_id'];

        $s3 = array(
            'aKey' => $this->s3AKey,
            'sKey' => $this->s3SKey,
            'bucket' => $this->s3Bucket,
            'directory' => $this->s3QueueDirectory
        );

        $result = $this->generateExport($this->collection, $filterAttributes, md5($startTime), $this->args['csvHeaders'], $s3);


        if ($result === false) {
            error_log("could not generate export. Aborting");
            return false;
        }

        $htmlBody = '<h3>Hi';

        if (isset($this->args['tenant_name']))
            $htmlBody.= ' ' . $this->args['tenant_name'] . ',';
        else
            $htmlBody.=',';

        $htmlBody.='</h3><p>';

        $htmlBody .= "Your user export is ready and available at <a href='$result'>$result</a>";

        $htmlBody.='</p>';

        $htmlBody.='<em>The Winning Mark robot - mobile@winningmark.com</em>';

        try {
            $sendgrid = new SendGrid('jonas.palmero', 'chickadee1');

            $mail = new SendGrid\Mail();
            $mail->
                    addTo('jonas.palmero@gmail.com')->
                    addBcc('jonas@winningmark.com')->
                    setFrom('jonas@winningmark.com')->
                    setSubject('Your user export is available')->
                    setText('Download export: ' . $result)->
                    setHtml($htmlBody);

            $sendgrid->smtp->send($mail);
        } catch (Exception $e) {
            error_log("could not deliver email:" . $e->getMessage());
            return false;
        }

        $completeTime = microtime(true) - $startTime;

        printf('Finished exporting user data for tenant %d in %s seconds \n', $this->args['tenant_id'], $completeTime);
    }

    /**
     * Generate and upload an export file to S3
     * @param MongoCollection mongodb collection
     * @param array filter attributes
     * @param string $uniqueId unique identifier to make sure exports can't have the same file name
     * @param array $csvHeaders csv headers
     * @param array $s3 s3 credentials
     * @return mixed download link or false 
     */
    private function generateExport(MongoCollection $collection, array $filterAttributes, $uniqueId, $csvHeaders, $s3) {

        $tmpFileName = 'tid_' . $filterAttributes['tenant_id'] . '_';
        $tmpFileName .=date('Y-m-d\-h-i-s');

        $tmpFileName .= '_' . $uniqueId;
        $tmpFileName.='.csv';

        $tmpFilePath = sys_get_temp_dir() . '/' . $tmpFileName;
        // @todo: try using $fp = fopen('php://temp', 'w');
        
        $fp = fopen($tmpFilePath, 'w');

        if (!$fp) {
            error_log("Could not open $tmpFilePath. Aborting");
            return false;
        }

        fputcsv($fp, $csvHeaders);

        $mobileUserCursor = $collection->find($filterAttributes);

        foreach ($mobileUserCursor as $mobileUser) {
            $row = array();
            foreach ($csvHeaders as $head => $friendlyHeadName) {
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

        S3::setAuth($s3['aKey'], $s3['sKey']);

        $uploadResult = S3::putObjectFile($tmpFilePath, $s3['bucket'], $s3['directory'] . "/" . $tmpFileName, $acl = S3::ACL_PUBLIC_READ, array(), "text/csv");

        if ($uploadResult !== true) {
            error_log("Could not open upload file to S3:");
            error_log(print_r($uploadResult, true));
        }

        unlink($tmpFilePath);

        $exportDownloadLink = 'https://s3.amazonaws.com/' . $s3['bucket'] . '/' . $s3['directory'] . '/' . $tmpFileName;

        return $exportDownloadLink;
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