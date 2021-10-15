<?php 
    define('_BOANN', 1);
    require_once dirname(dirname(dirname(__file__))).'/libraries/import.php';

    $_POST      =   json_decode(file_get_contents("php://input"), true)["0"];
    $action     =   !empty($_GET["action"]) ? $_GET["action"] : null;

    $input      =   NEW \classes\core\input;   
    $login      =   NEW \classes\core\login;   

    switch($action){
        case "login" :
            if($input->exist()){
                $username   =   !empty($input->get("data")["username"]) ? $input->get("data")["username"] : null;
                $password   =   !empty($input->get("data")["password"]) ? $input->get("data")["password"] : null;
                
                if(empty($username)     === true){   $errors = ["Username is required field"]; }
                elseif(empty($password) === true){   $errors = ["Password is required field"]; }
                elseif($login->username_exist($username) < 1 ){   $errors = ["This username is not exist"]; }    
                elseif($login->password_exist($username, $password) < 1){   $errors = ["This password is not exist"]; }
                if(empty($input->exist()) === false && empty($errors) === true){
                    if($login->login($username)){
                        $dataArray  =   [
                            "data"          =>  "success",
                            "dataContent"   =>  "Your Logedin",
                        ];
                    };                    
                }else{
                        $dataArray  =   [
                            "data"          =>  "error",
                            "dataContent"   =>  "{$errors[0]}",
                        ];                    
                }
                        echo json_encode($dataArray);
            }
        break;
    }