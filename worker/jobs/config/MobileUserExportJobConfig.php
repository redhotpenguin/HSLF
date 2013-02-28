<?php

$dotcloud_conf = "/home/dotcloud/environment.json";

if (file_exists($dotcloud_conf)) {     // dotcloud server conf
    $s3AKey = 'AKIAIDNK7VPB47DB2F2Q';
    $s3SKey = '2F7TBdQsokQVpIZAgNUx/PgKyE01wz3AXLmGFYvh';


    if ($env['DOTCLOUD_PROJECT'] === 'productionmap') {
        $s3Host = 'wmmobile.s3.amazonaws.com';
        $s3Bucket = 'wmmobile';
    } else {
        $s3Host = 'mobileadvocacydev.s3.amazonaws.com';
        $s3Bucket = 'mobileadvocacydev';
    }
} else {
    $s3Host = 'maplocal.s3.amazonaws.com';
    $s3Bucket = 'maplocal';
    $s3AKey = 'AKIAIDNK7VPB47DB2F2Q';
    $s3SKey = '2F7TBdQsokQVpIZAgNUx/PgKyE01wz3AXLmGFYvh';
}

// s3 specific
DEFINE('S3_HOST', $s3Host);
define('S3_AKEY', $s3AKey);
define('S3_SKEY', $s3SKey);
define('S3_BUCKET', $s3Bucket);

// email specific
// available shortcodes: {name} {downloadLink} {requester}


define('SENDGRID_USERNAME', 'jpalmero');
define('SENDGRID_PASSWORD', '4L5YMSPq-yLFNcnPM9');


$emailSubjectTemplate = "[Winning Mark Mobile] {name}, your user export is ready";

$emailBodyTemplate = "
<div style='margin:20px;border-radius:10px; background-color:#efefef; padding:25px;color:#666;'><h1 style='color:#DD5336;'>Winning Mark Mobile</h1>    

Hi <b>{name}</b>, <p> Your user export is ready and avalaible at the following url: <a href='{downloadLink}'>{downloadLink}</a><p>If we can be of any assistance, please contact mobile@winningmark.com</p>
</p><p>Thank you!</p><p style='margin-top: 30px;font-style:italic;color:#777;'>Export requested by {requester}</p>
</div>";

define('EMAIL_SUBJECT_TEMPLATE', $emailSubjectTemplate);
define('EMAIL_BODY_TEMPLATE', $emailBodyTemplate);