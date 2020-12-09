<?php

class Credentials{

    public static function Amazon(){
        // Bad Practice, Hard-Coded Credentials
        $awsKey = "AKIAWAAEDTFQAOJANPU6";
        $awsSecretKey = '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';
        $options = [
            'region'		=> 'us-west-2',
            'version'		=> 'latest',
            'credentials'	=> [
                'key'		=> $awsKey,
                'secret'	=> $awsSecretKey,
            ],
        ];

        return $options;
    }

}
