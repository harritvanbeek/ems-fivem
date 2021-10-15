<?php

defined('_BOANN') or header("Location:{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["SERVER_NAME"]}");

class Config{
        public
            $error_reporting    =   "none",
            $host               =   "127.0.0.1",    
            $dbname             =   "",    
            $dbuser             =   "", 
            $dbpassword         =   ""; 
    
}