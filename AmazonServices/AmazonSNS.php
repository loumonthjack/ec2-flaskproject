<?php
require 'vendor/autoload.php';
include_once 'AmazonCloudWatch.php';
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

class AmazonSNS
{

    public static function sendMessage($options, $message){
        try{
            $client = new SnsClient($options);
            $topic = 'arn:aws:sns:us-west-2:412325484896:EmailText';
            
            $client->SetSMSAttributes([
                'attributes' => [
                    'DefaultSMSType' => 'Transactional',
                ],
            ]);

            $client->publish([
                'Message' => $message,
                'TopicArn' => $topic
            ]);
            
            $logMsg = "SNS | Successfully sent: '".$message. "' to ".$topic."  ";
            echo $logMsg;
            \AmazonCloudWatchLog::pushSuccessDataToLog($options, $logMsg);
        } 
        catch(\Aws\Exception\AwsException $e){
            $e->getAwsErrorCode()."\n";
            $e->getMessage()."\n";
            $logMsg = $e->getAwsErrorCode()."\n"."=>   ".$e->getMessage()."\n";
            echo $logMsg;
            \AmazonCloudWatchLog::pushFailureDataToLog($options, $logMsg);
        }
    }

}