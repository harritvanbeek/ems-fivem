<?php
namespace classes\core;
defined('_BOANN') or header("Location:{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["SERVER_NAME"]}");
class client{

    private $_DB        =   NULL,
            $_CONFIG    =   NULL,
            $_SESSION   =   NULL;

    public function __construct(){
        $this->_DB          = NEW \classes\core\db;
        $this->_CONFIG      = NEW \classes\core\config;
        $this->_SESSION     = NEW \classes\core\session;
    }

    public function getReadDocument($data = null){
        return self::_getReadDocument($data);
    }

    public function getAllDocument($data = null){
        return self::_getAllDocument($data);
    }

    public function setDocument($data = []){
        return self::_setDocument($data);
    }

    public function getClient($data = null){
        return self::_getClient($data);
    }

    public function setClient($data = []){
        return self::_setClient($data);
    }
    
    protected function _getReadDocument($data = null){
        $this->array =  ["contentUuid" => "{$data}"];
        $this->query = "SELECT * FROM `clientdata` WHERE `contentUuid` = :contentUuid";
        return $this->_DB->get($this->query, $this->array);
    }

    protected function _getAllDocument($data = null){
        $this->query = "SELECT * FROM `clientdata` ORDER BY `clientdata`.`createdate` DESC ";
        return $this->_DB->getAll($this->query);
    }

    protected function _setDocument($data = []){
        $this->query =  "INSERT INTO `clientdata` 
            (`contentUuid`, `clientUuid`, `ssn`, `firstname`, `lastname`, `createdate`, `UserUuid`, `MeFname`,`MeLname`, `MeCnumber`, `MeRank`, `subject`, `content`) 
            VALUES (:contentUuid, :clientUuid, :ssn, :firstname, :lastname, :createdate, :UserUuid, :MeFname, :MeLname, :MeCnumber, :MeRank, :subject, :content) ";     
        return $this->_DB->action($this->query, $data);
    }

    protected function _getClient($data = null){
        $this->query    =   "SELECT * FROM `clients` WHERE `clients`.`ssn` = '{$data}'";        
        return $this->_DB->get($this->query);
    }

    protected function _setClient($data = []){
        $this->query    =   "INSERT INTO `clients` 
                                (`uuid`, `ssn`, `firstname`, `lastname`, `birthdate`, `sex`, `nationality`,  `phone_number`, `driverlicense`) 
                                VALUES(:uuid, :ssn, :firstname, :lastname, :birthdate, :sex, :nationality, :phone_number, :driverlicense) "; 
        return $this->_DB->action($this->query, $data);        
    }

    public function findClient($data = null){
        return self::_findClient($data);
    }

    protected function _findClient($data = null){
        $this->query    =   "SELECT *  
                                FROM `clients`
                                WHERE  
                                    `clients`.`firstname` LIKE '%{$data}%'
                                    OR `clients`.`lastname` LIKE '%{$data}%'
                            ";
        return $this->_DB->getAll($this->query);
    }
}