<?php
namespace classes\core;
defined('_BOANN') or header("Location:{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["SERVER_NAME"]}");
class users{

    private $_DB        =   NULL,
            $_CONFIG    =   NULL,
            $_SESSION   =   NULL;

    public function __construct(){
        $this->_DB          = NEW \classes\core\db;
        $this->_CONFIG      = NEW \classes\core\config;
        $this->_SESSION     = NEW \classes\core\session;
    }

    public function trashUser($data = null){
        return self::_trashUser($data);
    }

    public function createUser($data = []){
        return self::_createUser($data);
    }

    public function updatePassword($data = []){
        return self::_updatePassword($data);
    }

    public function updateUser($data = []){
        return self::_updateUser($data);
    }

    public function getUsers(){
        return self::_getUsers();
    }

    public function me(){
        return self::_me();
    }

    public function hash_password($password = null){
        $this->options  =   ['cost' => 10];
        return password_hash($password, PASSWORD_BCRYPT, $this->options);
    }

    protected function _trashUser($data = null){
        $this->array    =   ["uuid" => "{$data}"];
        $this->query    =   "DELETE FROM `users` WHERE `uuid` = :uuid";
        return  $this->_DB->action($this->query, $this->array);
    }

    protected function _createUser($data = []){
        $this->query    =   "INSERT INTO `users` 
                                (`uuid`, `username`, `MeFname`, `MeLname`, `MeRank`, `MeCnumber`, `password`) 
                            VALUES(:uuid, :username, :MeFname, :MeLname, :MeRank, :MeCnumber, :password)
                            ";
        return  $this->_DB->action($this->query, $data);
    }

    protected function _updatePassword($data = []){
        $this->options  =   ['cost' => 10];
        $this->hash     =   password_hash($data["password"], PASSWORD_BCRYPT, $this->options);
        $this->array    =   [
                                "uuid"      =>  "{$data["uuid"]}",
                                "password"  =>  "{$this->hash}",
                            ];
        $this->query    =   "UPDATE `users` 
                                SET 
                                    `password`   = :password                                    
                            WHERE `uuid` = :uuid
                            ";
        return  $this->_DB->action($this->query, $this->array);                
    }

    protected function _updateUser($data = []){
        $this->query = "UPDATE `users` 
                            SET 
                                `MeFname`   = :MeFname, 
                                `MeLname`   = :MeLname, 
                                `MeRank`    = :MeRank, 
                                `MeCnumber` = :MeCnumber 
                        WHERE `uuid` = :uuid
                        ";
        return $this->_DB->action($this->query, $data);        
    }

    protected function _getUsers(){
        $this->query =  "SELECT * FROM `users`";
        return $this->_DB->getAll($this->query);
    }

    protected function _me(){
        $this->uuid  =  $this->_SESSION->get($this->_CONFIG->get("boann/user"));
        
        $this->query =  "SELECT * 
                                FROM `users` 
                                WHERE `uuid` = '{$this->uuid}'";
        return $this->_DB->get($this->query);
    }
}