<?php
require 'vendor/autoload.php';
include_once 'AmazonCloudWatch.php';
include_once 'AmazonSNS.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class AmazonS3 
{

        public static function deleteS3Files($options, $bucketFolder){

            try{
                // Try to connect to AWS S3
                $client = new S3Client($options);
                $files = $client->listObjects([
                    'Bucket' => $bucketFolder
                ]); 
                
                // Empty Bucket
                foreach ($files['Contents'] as $file)
                {
                    $client->deleteObjects([
                        'Bucket'  => $bucketFolder,
                        'Delete' => [
                            'Objects' => [
                                [
                                    'Key' => $file['Key']
                                ]
                            ]
                        ]
                    ]);
                }
                $logMsg = "S3 | Successfully deleted from AWS S3 Bucket: ".$bucketFolder.'.';
                
                // Echo Success
                //echo $logMsg;
                \AmazonCloudWatchLog::pushSuccessDataToLog($options, $logMsg);
                \AmazonSNS::sendMessage($options, $logMsg);
            }
            catch(\Aws\Exception\AwsException $e){
                $e->getAwsErrorCode()."\n";
                $e->getMessage()."\n";
                $logMsg = "S3 | ".$e->getAwsErrorCode()."\n"."=>     ".$e->getMessage()."\n";
                //echo $logMsg;
                \AmazonCloudWatchLog::pushFailureDataToLog($options, $logMsg);
                \AmazonSNS::sendMessage($options, $logMsg);
            }
        }

        public static function downloadS3Files($options, $bucketFolder){
            try{
                // Try to connect to AWS S3
                $client = new S3Client($options);
                
                // Local Directory -- Transfer To
                $fileDestination = '/home/loumonth/AmazonServices/'.$bucketFolder.'-downloads';

                // If directory doesn't exist, create directory
                if(!file_exists($fileDestination)){
                    mkdir($fileDestination);
                }
                
                // Download Action
                $client->downloadBucket($fileDestination, $bucketFolder);
                $logMsg = "S3 | Successfully downloaded from AWS S3 Bucket: ".$bucketFolder.'.';
                
                // Echo Success
                //echo $logMsg;
                \AmazonCloudWatchLog::pushSuccessDataToLog($options, $logMsg);
                \AmazonSNS::sendMessage($options, $logMsg);
            }
            // Catch Any Errors
            catch(\Aws\Exception\AwsException $e){
                $e->getAwsErrorCode()."\n";
                $e->getMessage()."\n";
                $logMsg = "S3 | ".$e->getAwsErrorCode()."\n"."=>    ".$e->getMessage()."\n";
                //echo $logMsg;
                \AmazonCloudWatchLog::pushFailureDataToLog($options, $logMsg);
                \AmazonSNS::sendMessage($options, $logMsg);
            }
        }

        public static function uploadS3Files($options, $bucketFolder){
            try{
                // Try to Connect to AWS S3
                $client = new S3Client($options);
                
                // Local Directory -- Transfer From
                $fileDestination = '/home/loumonth/Flask-App';

                // Upload Action
                $client->uploadDirectory($fileDestination, $bucketFolder);
                $logMsg = "S3 | Successfully uploaded to AWS S3 Bucket: ".$bucketFolder.'.';
                
                // Echo Success
                //echo $logMsg;
                \AmazonCloudWatchLog::pushSuccessDataToLog($options, $logMsg);
                \AmazonSNS::sendMessage($options, $logMsg);

            }
            // Catch Any Errors
            catch(\Aws\Exception\AwsException $e){
                $e->getAwsErrorCode()."\n";
                $e->getMessage()."\n";
                $logMsg = "S3 | ".$e->getAwsErrorCode()."\n"."=>     ".$e->getMessage()."\n";
                //echo $logMsg;
                \AmazonCloudWatchLog::pushFailureDataToLog($options, $logMsg);
                \AmazonSNS::sendMessage($options, $logMsg);
            }
        }
        
        public static function listSpecificFiles($options, $bucketFolder, $searchedKeyword = '')
        {
            $client = new S3Client($options);
            $zipFiles = $client->listObjects([ 
                'Bucket' => $bucketFolder,
            ]);
            $allFiles = $zipFiles['Contents'];

            foreach($allFiles as $file){
               $fileName = $file['Key'];
               if(strpos($fileName, $searchedKeyword)){
                //    echo $fileName ; 
                  var_dump($fileName);
               }
            };
            $logMsg = "S3 | Successfully listed Objects from AWS S3 Bucket: ".$bucketFolder.'.';
                
            // Echo Success
            //echo $logMsg;
            \AmazonCloudWatchLog::pushSuccessDataToLog($options, $logMsg);
            \AmazonSNS::sendMessage($options, $logMsg);
        }

}

