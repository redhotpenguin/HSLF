<?php

$dotcloud_conf = "/home/dotcloud/environment.json";

if (file_exists($dotcloud_conf)) {     // dotcloud server conf
    $s3AKey = 'AKIAIDNK7VPB47DB2F2Q';
    $s3SKey = '2F7TBdQsokQVpIZAgNUx/PgKyE01wz3AXLmGFYvh';
    $s3Bucket = 'maplocal';
} else {
    $s3AKey = 'AKIAIDNK7VPB47DB2F2Q';
    $s3SKey = '2F7TBdQsokQVpIZAgNUx/PgKyE01wz3AXLmGFYvh';
    $s3Bucket = 'maplocal';
}

// s3 specific
define('S3_AKEY', $s3AKey);
define('S3_SKEY', $s3SKey);
define('S3_BUCKET', $s3Bucket);

// email specific
// available shortcodes: {name} {downloadLink}


define('SENDGRID_USERNAME', 'jonas.palmero');
define('SENDGRID_PASSWORD', 'RgPqwq24wRFg8Wn');


$emailSubjectTemplate = "[Winning Mark Mobile] {name}, your user export is ready";
$emailBodyTemplate = "Hi <b>{name}</b>, <p> Your report is ready and avalaible at the following url: <a href='{downloadLink}'>{downloadLink}</a> </p><em>The Winning Mark robot - mobile@winningmark.com</em>";

define('EMAIL_SUBJECT_TEMPLATE', $emailSubjectTemplate);
define('EMAIL_BODY_TEMPLATE', $emailBodyTemplate);