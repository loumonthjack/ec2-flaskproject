<?php
include_once 'Credentials.php';
require 'AmazonS3.php';
//require 'AmazonSNS.php';
//require 'AmazonCloudWatch.php';

// AWS Credentials
$options = Credentials::Amazon();


// ----------------------CLOUDWATCH LOGGING ------------------//
// Log Success Data to AWS Cloudwatch Logs
//$metricData = 'failure data';
//$log = AmazonCloudWatchLog::pushSuccessDataToLogStream($options, $metricData);

// Log Failure Data to AWS Cloudwatch Logs
//$metricData = 'failure data';
//$log = AmazonCloudWatchLog::pushFailureDataToLogStream($options, $metricData);
// ---------------------CLOUDWATCH LOGGING--------------------//



// ------------------SIMPLE STORAGE SERVICE-------------------//
// Public Bucket = cloud-unit-2
// Private Buckets = cloud-unit-1/3/4
$bucket = ['cloud-unit-1', 'cloud-unit-2', 'cloud-unit-3', 'cloud-unit-4'];
//$list = AmazonS3::listSpecificFiles($options, $bucket[1], 'jpg');
// Empty AWS S3 Bucket
//$empty = AmazonS3::deleteS3Files($options, $bucket[2]);

// Download Files From S3 Bucket
//$download = AmazonS3::downloadS3Files($options, $bucket[2]);

// Upload Files From S3 Bucket
//$upload = AmazonS3::uploadS3Files($options, $bucket[2]);
// -------------------SIMPLE STORAGE SERVICE------------------//



// -----------------SIMPLE NOTIFICATION SERVICE---------------//
// Send Text Message using AWS SNS
//$message = "New Account Has Been Added";
if(PHP_SAPI === 'cli'){
    if(isset($argv[2])){
        $message = $argv[1].$argv[2];
    };
    $message = $argv[1];
}
//$sendMessage = \AmazonSNS::sendMessage($options, $message);
// -----------------SIMPLE NOTIFICATION SERVICE---------------//

