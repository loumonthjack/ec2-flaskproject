<?php

class Credentials{

    public static function Amazon(){
        // Bad Practice, Hard-Coded Credentials
        $options = [
            'profile'       => 'default',
            'region'		=> 'us-west-2',
            'version'		=> 'latest'
        ];

        return $options;
    }

}
