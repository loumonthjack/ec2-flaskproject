<?php
require 'vendor/autoload.php';
use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\Exception\AwsException;

class AmazonCloudWatchLog
{

    public static function pushSuccessDataToLog($options, $metricData){
        $time = new DateTime();
        $date = $time->format('Y-m-d');
        $streamPrefix = '/success/';
        $logStreamName = '';
        $logGroupName = 's3-website';
        
        try{
          
            // Connect with AWS CloudWatch then Check for the CloudWatch Logs for the specific Cloudwatch Log Group 
            $logs = new CloudWatchLogsClient($options);

            // Get All Log Info as Array
            $results = $logs->describeLogStreams([
                'logGroupName' => $logGroupName,
                'logStreamName' => $logStreamName,
            ])->get('logStreams');

            // Declaration of Log Stream Name 
            $logStreamName = $streamPrefix.'dev/'.$date;

            // Each Log Stream Info
            foreach($results as $result){
                // Each Log Stream Name
                $loggedStreams = $result['logStreamName'];
                //var_dump($result['uploadSequenceToken']);
            }

            // The upload Sequence Token of the last Log Stream in Results Array
            $uploadSequenceToken = end($results)['uploadSequenceToken'];

            // if Declared logStreamName does not exist, Create it and Log Event
            if($loggedStreams != $logStreamName){

                // Create Log Stream
                $logs->createLogStream([
                    'logGroupName' => $logGroupName,
                    'logStreamName' => $logStreamName
                ]);
                    
                // For First Log Event, Upload Sequence Token Doesnt Exist, Put Log Event Without Token
                $logs->putLogEvents([
                    'logGroupName' => $logGroupName,
                    'logStreamName' => $logStreamName, 
                    'logEvents' => [
                        [
                        'timestamp' => round(microtime(true) * 1000),
                        'message' => json_encode([$metricData]),
                        ],
                ]]);

            }else{
                
                // For all other Log Events include Sequence Token
                $logs->putLogEvents([
                    'logGroupName' => $logGroupName,
                    'logStreamName' => $logStreamName, 
                    'logEvents' => [
                        [
                        'timestamp' => round(microtime(true) * 1000),
                        'message' => json_encode([$metricData]),
                        ],
                    ],
                    'sequenceToken' => $uploadSequenceToken,
                ]);
            }
        }
        // Catch Any Errors
        catch(\Aws\CloudWatchLogs\Exception\CloudWatchLogsException $e){
            $e->getAwsErrorCode()."\n";
            $e->getMessage()."\n";
        }
    }	

    public static function pushFailureDataToLog($options, $metricData){
        $time = new DateTime();
        $date = $time->format('Y-m-d');
        $streamPrefix = '/failure/';
        $logStreamName = '';
        $logGroupName = 's3-website';
        
        try{
            // Connect with AWS CloudWatch then Check for the CloudWatch Logs for the specific Cloudwatch Log Group 
            $logs = new CloudWatchLogsClient($options);

            // Get All Log Info as Array
            $results = $logs->describeLogStreams([
                'logGroupName' => $logGroupName,
                'logStreamName' => $logStreamName,
            ])->get('logStreams');

            // Declaration of Log Stream Name 
            $logStreamName = $streamPrefix.'dev/'.$date;

            // Each Log Stream Info
            foreach($results as $result){
                // Each Log Stream Name
                $loggedStreams = $result['logStreamName'];
                var_dump($result);
                //var_dump($result['uploadSequenceToken']);
            }

            // The upload Sequence Token of the last Log Stream in Results Array
            $uploadSequenceToken = end($results)['uploadSequenceToken'];

            // if Declared logStreamName does not exist, Create it and Log Event
            if($loggedStreams != $logStreamName){

                // Create Log Stream
                $logs->createLogStream([
                    'logGroupName' => $logGroupName,
                    'logStreamName' => $logStreamName
                ]);
                    
                // For First Log Event, Upload Sequence Token Doesnt Exist, Put Log Event Without Token
                $logs->putLogEvents([
                    'logGroupName' => $logGroupName,
                    'logStreamName' => $logStreamName, 
                    'logEvents' => [
                        [
                        'timestamp' => round(microtime(true) * 1000),
                        'message' => json_encode([$metricData]),
                        ],
                ]]);

            }else{
                
                // For all other Log Events include Sequence Token
                $logs->putLogEvents([
                    'logGroupName' => $logGroupName,
                    'logStreamName' => $logStreamName, 
                    'logEvents' => [
                        [
                        'timestamp' => round(microtime(true) * 1000),
                        'message' => json_encode([$metricData]),
                        ],
                    ],
                    'sequenceToken' => $uploadSequenceToken,
                ]);
            }
        }
        // Catch Any Errors
        catch(\Aws\CloudWatchLogs\Exception\CloudWatchLogsException $e){
            $e->getAwsErrorCode()."\n";
            $e->getMessage()."\n";
        }
    }	

}