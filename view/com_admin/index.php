<?php 
    define('_BOANN', 1);
    require_once dirname(dirname(dirname(__file__))).'/libraries/import.php';

    $_POST      =   json_decode(file_get_contents("php://input"), true)["0"];
    $action     =   !empty($_GET["action"]) ? $_GET["action"] : null;

    $users      =   NEW \classes\core\users;  
    $input      =   NEW \classes\core\input; 
    $session    =   NEW \classes\core\session;  
    $settings   =   NEW \classes\core\settings;  
    $CONFIG     =   NEW \classes\core\config; 

    switch($action){
        case "trashUser" :
            if($input->exist()){
                $uuid       =       !empty($input->get("data")["uuid"]) ? $input->get("data")["uuid"] : null;
                if($uuid    !==     $users->me()->uuid){
                    if($users->trashUser($uuid) > 0){
                        $dataArray  =   [
                            "data"          =>  "success",
                            "dataContent"   =>  "User is deleted",
                        ];
                    }else{
                        $dataArray  =   [
                            "data"          =>  "error",
                            "dataContent"   =>  "Database issu try later again",
                        ];
                    };
                }else{
                    $error = ["You cant delete your own account"];
                    $dataArray  =   [
                        "data"          =>  "error",
                        "dataContent"   =>  "{$error[0]}",
                    ];
                };
                    echo json_encode($dataArray);                   
            }    
        break;

        case "createUser" :
            if($input->exist()){
                $username           =   !empty($input->get("data")["username"])         ? $input->get("data")["username"]       : NULL;
                $MeFname            =   !empty($input->get("data")["MeFname"])          ? $input->get("data")["MeFname"]        : NULL;
                $MeLname            =   !empty($input->get("data")["MeLname"])          ? $input->get("data")["MeLname"]        : NULL;
                $MeRank             =   !empty($input->get("data")["MeRank"])           ? $input->get("data")["MeRank"]         : NULL;
                $MeCnumber          =   !empty($input->get("data")["MeCnumber"])        ? $input->get("data")["MeCnumber"]      : NULL;
                $newPassword        =   !empty($input->get("data")["newPassword"])      ? $input->get("data")["newPassword"]    : NULL;
                $repeatPassword     =   !empty($input->get("data")["repeatPassword"])   ? $input->get("data")["repeatPassword"] : NULL;
                
                //errors
                if(empty($username)             === true){ $error = ["Username is a required field"];}
                elseif(empty($MeFname)          === true){ $error = ["Fistname is a required field"];}
                elseif(empty($MeLname)          === true){ $error = ["Lastname is a required field"];}
                elseif(empty($MeRank)           === true){ $error = ["Rank is a required field"];}
                elseif(empty($MeCnumber)        === true){ $error = ["Callsign is a required field"];}
                elseif(empty($newPassword)      === true){ $error = ["New password is a required field"];}
                elseif(empty($repeatPassword)   === true){ $error = ["Repeat password is a required field"];}
                elseif($newPassword !== $repeatPassword) { $error = ["Passwords are not the same"];}
                if(empty($input->get("data")) === false AND empty($error) === true){
                    
                    $dataArray  =   [
                        "uuid"          =>  "{$settings->MakeUuid()}",
                        "username"      =>  "{$username}",
                        "MeFname"       =>  "{$MeFname}",
                        "MeLname"       =>  "{$MeLname}",
                        "MeRank"        =>  "{$MeRank}",
                        "MeCnumber"     =>  "{$MeCnumber}",
                        "password"      =>   $users->hash_password("{$newPassword}"),
                    ];

                    if($users->createUser($dataArray) > 0){
                        $dataArray  =   [
                            "data"          =>  "success",
                            "dataContent"   =>  "User is created",
                        ];
                    }else{
                        $dataArray  =   [
                            "data"          =>  "error",
                            "dataContent"   =>  "Database issu try later again",
                        ];
                    };
                }else{
                    $dataArray  =   [
                        "data"          =>  "error",
                        "dataContent"   =>  "{$error[0]}",
                    ];
                } 
                    echo json_encode($dataArray); 
            }
        break;
        
        case "updateUser" :
            if($input->exist()){
                $uuid               =   !empty($input->get("data")["uuid"])             ? $input->get("data")["uuid"]           : NULL;
                $MeFname            =   !empty($input->get("data")["MeFname"])          ? $input->get("data")["MeFname"]        : NULL;
                $MeLname            =   !empty($input->get("data")["MeLname"])          ? $input->get("data")["MeLname"]        : NULL;
                $MeRank             =   !empty($input->get("data")["MeRank"])           ? $input->get("data")["MeRank"]         : NULL;
                $MeCnumber          =   !empty($input->get("data")["MeCnumber"])        ? $input->get("data")["MeCnumber"]      : NULL;
                $newPassword        =   !empty($input->get("data")["newPassword"])      ? $input->get("data")["newPassword"]    : NULL;
                $repeatPassword     =   !empty($input->get("data")["repeatPassword"])   ? $input->get("data")["repeatPassword"] : NULL;
                
                //errors
                if(empty($MeFname)          === true){ $error = ["Fistname is a required field"];}
                elseif(empty($MeLname)      === true){ $error = ["Lastname is a required field"];}
                elseif(empty($MeRank)       === true){ $error = ["Rank is a required field"];}
                elseif(empty($MeCnumber)    === true){ $error = ["Callsign is a required field"];}
                if(empty($input->get("data")) === false AND empty($error) === true){
                    if(!empty($newPassword)){                        
                        if(empty($repeatPassword)          === true){ $error = ["Repeat password is a required field"];}
                        elseif($newPassword !== $repeatPassword)    { $error = ["Passwords are not the same"];}
                        elseif($newPassword === $repeatPassword){
                            $postArray = [
                                "uuid"      =>  "{$uuid}",
                                "password"  =>  "{$newPassword}",
                            ];
                            if($users->updatePassword($postArray) > 0){
                                $dataArray  =   [
                                    "data"          =>  "success",
                                    "dataContent"   =>  "Password is updated",
                                ];
                            }else{
                                $dataArray  =   [
                                    "data"          =>  "error",
                                    "dataContent"   =>  "Database issu try later again",
                                ];
                            };                            
                        }   

                        if(!empty($error)){
                            $dataArray  =   [
                                "data"          =>  "error",
                                "dataContent"   =>  "{$error[0]}",
                            ];             
                        }               
                    }else{
                            $postArray = [
                                "uuid"      =>  "{$uuid}",
                                "MeFname"   =>  "{$MeFname}",
                                "MeLname"   =>  "{$MeLname}",
                                "MeRank"    =>  "{$MeRank}",
                                "MeCnumber" =>  "{$MeCnumber}",
                            ];
                            if($users->updateUser($postArray) > 0){
                                $dataArray  =   [
                                    "data"          =>  "success",
                                    "dataContent"   =>  "User is updated",
                                ];
                            }else{
                                $dataArray  =   [
                                    "data"          =>  "error",
                                    "dataContent"   =>  "Database issu try later again",
                                ];
                            };                            
                    }
                }else{
                            $dataArray  =   [
                                "data"          =>  "error",
                                "dataContent"   =>  "{$error[0]}",
                            ];
                }
                            echo json_encode($dataArray);           
            }
        break;

        case "getUsers" :
            $userdata   =   $users->getUsers();
            if($userdata){
                foreach($userdata as $user){
                    $dataArray[]    = [
                        "uuid"      =>  "{$user->uuid}",
                        "username"  =>  "{$user->username}",
                        "MeFname"   =>  "{$user->MeFname}",
                        "MeLname"   =>  "{$user->MeLname}",
                        "MeRank"    =>  "{$user->MeRank}",
                        "MeCnumber" =>  "{$user->MeCnumber}",
                    ];
                }
                echo json_encode($dataArray);            
            }
        break;
        
        default :
            if(empty($session->get($CONFIG->get("boann/user"))) === true){
                $dataArray  =    ["data"  =>  "false"];
                echo json_encode($dataArray);
            };
        break;
    }