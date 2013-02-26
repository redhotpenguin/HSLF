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

// sendgrid specific

define('SENDGRID_USERNAME', 'jonas.palmero');
define('SENDGRID_PASSWORD', 'RgPqwq24wRFg8Wn');