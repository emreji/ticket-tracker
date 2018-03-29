<?php
class DataSource
{
    public static function isAvailable() : bool {
        if(file_exists('xml/users.xml') && file_exists('xml/supportsystem.xml')) {
            return true;
        }
        else {
            return false;
        }
    }
}
