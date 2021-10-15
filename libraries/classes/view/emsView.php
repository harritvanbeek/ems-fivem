<?php 
namespace classes\view;

defined('_BOANN') or header("Location:{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["SERVER_NAME"]}");

class emsView{
    public      $_language      =   NULL;
    private     $_DB            =   NULL,
                $_CONFIG        =   NULL,
                $_SESSION       =   NULL,
                $_REDERECT      =   NULL,
                
                $_CONTROLLER    =   "home",
                $_INDEX         =   "index",
                $_PAGE          =   "home",
                               
                $_PARAMS        =   [],
                $_MODEL         =   NULL,
                $_URL           =   NULL; 



    public function __construct(){
        $this->_DB          = NEW \classes\core\db;                                 
        $this->_CONFIG      = NEW \classes\core\config;                                         
        $this->_SESSION     = NEW \classes\core\session;
        $this->_REDERECT    = NEW \classes\core\rederect;
        //$this->_language      = NEW \classes\core\language;      
        $this->_URL           = $this->_parseURL();
        
        if($this->_URL[0]){
            $this->_CONTROLLER  =   $this->_URL[0];
            $this->_PAGE        =   $this->_URL[0];            
        }
       
        if(class_exists('classes\controller\\'.$this->_CONTROLLER) > 0){
            $this->className    =   'classes\controller\\'.$this->_CONTROLLER;
            $this->_CONTROLLER  = NEW $this->className;            
        };

        if( isset($this->_URL[1]) ){
            $this->_MODEL   =   $this->_URL[1];
            unset($this->_URL[1]);
        }

        $this->params = $this->_URL ? array_values($this->_URL) : [];
        call_user_func_array($this->_CONTROLLER, $this->_MODEL, $this->params);
        

        //custom 404
        if(self::urlFolderExist() < 1){
            self::Custom404Pgage();            
        }else{
            unset($this->_URL[0]);
        }

    }

    public function logout(){
        if($this->_SESSION->exist($this->_CONFIG->get("boann/user"))){
                $this->_SESSION->delete($this->_CONFIG->get("boann/user"));             
                $this->_REDERECT->to(SITE);                         
        }
    }

    public function activeUrl($data = ""){
        if($this->_PAGE === $data){
            return "active";
        }
    }

    public function RenderPages(){
        return "view/com_{$this->_PAGE}/{$this->_INDEX}.php";
    }

    protected function Custom404Pgage(){
        require_once "templates/404.php";
        die;
    }

    protected function urlFolderExist(){
        /*if(is_dir("view/com_{$this->_PAGE}")){
            return true;
        }else{
            return false;
        }*/
            return true;
    }

    protected function urlExist(){
        return file_exists("view/com_{$this->_URL[0]}/index.php");
    }

    public function view($view = null){      
        require_once "templates/index.php";
    }


    protected function _parseURL(){
        if(isset($_GET["request"])){
            return explode("/",  filter_var(rtrim($_GET["request"], '/'), FILTER_SANITIZE_URL));            
        }
    }

}