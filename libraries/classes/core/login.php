<?php
namespace classes\core;
defined('_BOANN') or header("Location:{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["SERVER_NAME"]}");

class login{
    
    private $_DB        =   NULL,
            $_CONFIG    =   NULL,
            $_SESSION   =   NULL;

    public function __construct(){
        $this->_DB          = NEW \classes\core\db;
        $this->_CONFIG      = NEW \classes\core\config;
        $this->_SESSION     = NEW \classes\core\session;
    }

    //public funtions
    public function login($data = null){
        return self::_login($data);
    }

    public function username_exist($data = null){
        return self::_username_exist($data);
    }

    public function password_exist($username = null, $password = null){
        return self::_password_exist($username, $password);
    }

    //protected funtions
    protected function _login($data = null){
        $this->array    =   ["username" => "{$data}"];
        $this->query    =   "SELECT `uuid` FROM `users` WHERE `username` = :username ";
        $this->uuid     =   $this->_DB->get($this->query, $this->array)->uuid;
        $this->_SESSION->put($this->_CONFIG->get("boann/user"), $this->uuid);
        return true;
    }

    protected function _username_exist($data = null){
        $this->array    =   ["username" => "{$data}"];
        $this->query    =   "SELECT count(`username`) AS `exist` FROM `users` WHERE `username` = :username ";
        if($this->_DB->get($this->query, $this->array)->exist > 0){
            return true;
        };
    }

    protected function _password_exist($username = null, $password = null){
        $this->array    =   ["username" => "{$username}"];
        $this->query    =   "SELECT `password` FROM `users` WHERE `username` = :username ";
        $this->password =   $this->_DB->get($this->query, $this->array)->password;      
        return password_verify($password, $this->password);  
    }
}