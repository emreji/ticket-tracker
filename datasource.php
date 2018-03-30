<?php
class DataSource
{
    public static function isAvailable() : bool {
        if(file_exists('XML/users.xml') && file_exists('XML/supportsystem.xml')) {
            return true;
        }
        else {
            return false;
        }
    }
}
