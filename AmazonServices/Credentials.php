<?php

class Credentials{

    public static function Amazon(){
        $options = [
            'profile'       => 'default',
            'region'		=> 'us-west-2',
            'version'		=> 'latest'
        ];
        return $options;
    }

}
