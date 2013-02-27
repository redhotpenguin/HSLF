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
  'tenant_name' => 'Our Oregon'
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
    private $emailSubjectTemplate = EMAIL_SUBJECT_TEMPLATE;
    private $emailBodyTemplate = EMAIL_BODY_TEMPLATE;

    public function perform() {
        $startTime = microtime(true);

        $this->log('Starting user export');

        if (($r = $this->verifyArguments()) != true) {
            return $r;
        }

        $result = $this->generateExport();

        if ($result === false) {
            $this->logError("could not generate export. Aborting");
            return false;
        }

        $this->log('Export link: ' . $result);

        if ($this->sendResultEmail($result))
            $this->log('Email successfully sent to ' . $this->args['email']);
        else
            $this->logError("Could not send email");

        $completeTime = microtime(true) - $startTime;

        $memoryUsed = memory_get_peak_usage(true);
        $memoryUsed = $memoryUsed / 1024;
        $memoryUsed = $memoryUsed / 1024;
        $this->log("Used $memoryUsed MB ");
        $this->log("User export completed in $completeTime seconds");
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
            $this->logError("Could not open $tmpFilePath. Aborting");
            return false;
        }

        fputcsv($fp, $this->args['csvHeaders']);

        $this->collection->setReadPreference(MongoClient::RP_SECONDARY_PREFERRED);

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

        try {
            S3::setAuth($this->s3AKey, $this->s3SKey);
            $uploadResult = S3::putObjectFile($tmpFilePath, $this->s3Bucket, $this->s3QueueDirectory . "/" . $tmpFileName, $acl = S3::ACL_PUBLIC_READ, array(), "text/csv");
        } catch (S3Exception $e) {
            $this->logError('could not upload file to S3: ' . $e->getMessage());
            $uploadResult = null;
        }
        if ($uploadResult !== true) {
            $this->logError(print_r($uploadResult, true));
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

        $requester = (isset($this->args['requested_by']) ? $this->args['requested_by'] : $this->args['tenant_name']);

        $subject = str_replace('{name}', $this->args['tenant_name'], $this->emailSubjectTemplate);

        $body = str_replace('{name}', $this->args['tenant_name'], $this->emailBodyTemplate);
        $body = str_replace('{downloadLink}', $exportUrl, $body);
        $body = str_replace('{requester}', $requester, $body);

        try {
            $sendgrid = new SendGrid($this->sendgridUsername, $this->sendgridPassword);

            $mail = new SendGrid\Mail();
            $mail->
                    //   addTo('jonas.palmero@gmail.com')->
                    addTo($this->args['email'])->
                    setFrom('no-reply@winningmark.com')->
                    setSubject($subject)->
                    setText(strip_tags($body))->
                    setHtml($body);

            $sendgrid->smtp->send($mail);
            
        } catch (Exception $e) {
            $this->logError('could not deliver email:' . $e->getMessage());
            return false;
        }

        return true;
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
                        'timeout' => 10000,
                    )); // connect
        } catch (MongoConnectionException $e) {
            $this->logError($e->getMessage());
            return false;
        }

        $this->db = $this->mongoClient->selectDB($this->args['mongodb_name']);


        try {
            $this->collection = new MongoCollection($this->db, $collectionName); // @todo: exception handling
        } catch (Exception $e) {
            $this->logError('Could not get a collection: %s' . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * log a message
     * @param string message
     * @param string type type of message
     */
    private function log($message) {
        $name = ( isset($this->args['tenant_name']) ? $this->args['tenant_name'] : '');
        printf('%s: ' . $message . PHP_EOL, $name);
    }

    /**
     * log an error message
     */
    private function logError($message) {
        $name = ( isset($this->args['tenant_name']) ? $this->args['tenant_name'] : '');

        printf('%s[error]: ' . $message . PHP_EOL, $name);
    }

    private function verifyArguments() {

        // argument validations
        if (!isset($this->args['tenant_id']) || !is_numeric($this->args['tenant_id'])) {
            $this->logError("Tenant id is missing or invalid. Aborting");
            return false;
        }

        if (!$this->mongoDBConnect($this->args['mongodb_collection_name'])) {
            $this->logError("Could not connect to mongodb server. Aborting");
            return false;
        }

        if (!isset($this->args['csvHeaders'])) {
            $this->logError("Missing CSV header. Aborting");
            return false;
        }

        if (!isset($this->args['tenant_name'])) {
            $this->logError("Tenant name is missing. Aborting");
            return false;
        }

        if (!isset($this->args['email']) || !filter_var($this->args['email'], FILTER_VALIDATE_EMAIL)) {
            $this->logError("Email address is missing or invalid. Aborting");
            return false;
        }

        if (!is_array($this->args['csvHeaders'])) {
            $this->logError("CSV header format error. Aborting");
            return false;
        }

        if (isset($this->args['filterAttributes']) && is_array($this->args['filterAttributes']))
            $filterAttributes = $this->args['filterAttributes'];

        else
            $filterAttributes = array();

        $this->args['filterAttributes']['tenant_id'] = $this->args['tenant_id'];

        return true;
    }

}