<?php 
    define('_BOANN', 1);
    require_once dirname(dirname(dirname(__file__))).'/libraries/import.php';

    $_POST      =   json_decode(file_get_contents("php://input"), true)["0"];
    $action     =   !empty($_GET["action"]) ? $_GET["action"] : null;

    $me         =   NEW \classes\core\users;  
    $input      =   NEW \classes\core\input;  
    $client     =   NEW \classes\core\client;  
    $session    =   NEW \classes\core\session;  
    $settings   =   NEW \classes\core\settings;  
    $CONFIG     =   NEW \classes\core\config;  

    switch($action){
        case "me" :
            if(!empty($me->me())){
                $dataArray = [
                    "uuid"          =>  "{$me->me()->uuid}",
                    "username"      =>  "{$me->me()->username}",
                    "primissions"   =>  "{$me->me()->primissions}",
                    "MeFname"       =>  "{$me->me()->MeFname}",
                    "MeLname"       =>  "{$me->me()->MeLname}",
                    "MeCnumber"     =>  "{$me->me()->MeCnumber}",
                    "MeRank"        =>  "{$me->me()->MeRank}",
                ];
                echo json_encode($dataArray);
            }
        break;
        case "getUpdateDocument" :
            if($input->exist("get")){
                $uuid       =   !empty($input->get("uuid")) ? $input->get("uuid") : NULL;
                $data       = $client->getReadDocument($uuid);
                $dataArray = [
                    "clientUuid"    =>  "{$data->uuid}",
                    "ssn"           =>  "{$data->ssn}",
                    "firstname"     =>  "{$data->firstname}",
                    "lastname"      =>  "{$data->lastname}",
                    "subject"       =>  "{$data->subject} (Edit)",
                    "content"       =>  "{$data->content}",
                    "date"          =>  date("Y-m-d H:i:s"),
                    
                    "UserUuid"      =>  "{$me->me()->uuid}",
                    "MeFname"       =>  "{$me->me()->MeFname}",
                    "MeLname"       =>  "{$me->me()->MeLname}",
                    "MeCnumber"     =>  "{$me->me()->MeCnumber}",
                    "MeRank"        =>  "{$me->me()->MeRank}",                                                          
                ];
                echo json_encode($dataArray);
            }
        break;
        
        case "getReadDocument" :
            if($input->exist("get")){
                $uuid       =   !empty($input->get("uuid")) ? $input->get("uuid") : NULL;
                echo json_encode($client->getReadDocument($uuid));
            }
        break;

        case "getAllDocument" :
            if($input->exist("get")){
                $uuid       =   !empty($input->get("uuid")) ? $input->get("uuid") : NULL;
                $clients    =   $client->getAllDocument($uuid);
                 if(empty($clients) === false){
                    foreach($clients as $data){
                        $content     =  strip_tags($data->content); 
                        $content     =  preg_replace( "/\r|\n/", "", $content); 
                        $content     =  mb_strimwidth($content, 0, 230, '...'); 
                        $date        =  new DateTime($data->createdate);
                        
                        $dataArray[] =    [
                            "contentUuid" =>  "{$data->contentUuid}",
                            "subject"      =>  "{$data->subject}",
                            "createdate"   =>  "{$date->format('l d, F Y')}",
                            "time"         =>  "{$date->format('H:i')}",
                            "MeFname"      =>  "{$data->MeFname}",
                            "MeLname"      =>  "{$data->MeLname}",
                            "content"      =>  "{$content}",
                        ];
                    }                        
                 }else{
                        $dataArray =    [];
                 }
                        echo json_encode($dataArray); 
            }
        break;
        
        case "setDocument" :
            if($input->exist()){       
                $clientUuid =   !empty($input->get("data")["clientUuid"])   ? $input->get("data")["clientUuid"]         : null;
                $ssn        =   !empty($input->get("data")["ssn"])          ? $input->get("data")["ssn"]                : null;
                $firstname  =   !empty($input->get("data")["firstname"])    ? $input->get("data")["firstname"]          : null;
                $lastname   =   !empty($input->get("data")["lastname"])     ? $input->get("data")["lastname"]           : null;
                $createdate =   !empty($input->get("data")["date"])         ? $input->get("data")["date"]               : null;
                $UserUuid   =   !empty($input->get("data")["UserUuid"])     ? $input->get("data")["UserUuid"]           : null;
                $MeFname    =   !empty($input->get("data")["MeFname"])      ? $input->get("data")["MeFname"]            : null;
                $MeLname    =   !empty($input->get("data")["MeLname"])      ? $input->get("data")["MeLname"]            : null;
                $MeCnumber  =   !empty($input->get("data")["MeCnumber"])    ? $input->get("data")["MeCnumber"]          : null;
                $MeRank     =   !empty($input->get("data")["MeRank"])       ? $input->get("data")["MeRank"]             : null;
                $subject    =   !empty($input->get("data")["subject"])      ? escape($input->get("data")["subject"])    : null;
                $content    =   !empty($input->get("data")["content"])      ? $input->get("data")["content"]            : null;
                
                if(empty($subject)      === true) { $error = ["Subject is required value"];}
                elseif(empty($content)  === true) { $error = ["Content is required value"];}

                if(empty($input->get("data")) === false and empty($error) === true){
                    $postArray  =   [
                        "contentUuid"   =>  "{$settings->MakeUuid()}",
                        "clientUuid"    =>  "{$clientUuid}",
                        "ssn"           =>  "{$ssn}",
                        "firstname"     =>  "{$firstname}",
                        "lastname"      =>  "{$lastname}",
                        "createdate"    =>  "{$createdate}",
                        "UserUuid"      =>  "{$UserUuid}",
                        "MeFname"       =>  "{$MeFname}",
                        "MeLname"       =>  "{$MeLname}",
                        "MeCnumber"     =>  "{$MeCnumber}",
                        "MeRank"        =>  "{$MeRank}",
                        "subject"       =>  "{$subject}",
                        "content"       =>  "{$content}",
                    ];
                    if($client->setDocument($postArray) > 0){
                        $dataArray  =   [
                            "data"          =>  "success",
                            "dataContent"   =>  "Document is created",
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

        case "getClientInfo" :
            if($input->exist()){
                $ssn    =  !empty($input->get("data")) ? escape($input->get("data")) : NULL;
                $data   =  $client->getClient($ssn);
                
                $dataArray = [
                    "clientUuid"    =>  "{$data->uuid}",
                    "ssn"           =>  "{$data->ssn}",
                    "firstname"     =>  "{$data->firstname}",
                    "lastname"      =>  "{$data->lastname}",
                    "date"          =>  date("Y-m-d H:i:s"),
                    
                    "UserUuid"      =>  "{$me->me()->uuid}",
                    "MeFname"       =>  "{$me->me()->MeFname}",
                    "MeLname"       =>  "{$me->me()->MeLname}",
                    "MeCnumber"     =>  "{$me->me()->MeCnumber}",
                    "MeRank"        =>  "{$me->me()->MeRank}",                    
                    "content"       =>  "",                    
                ];
                echo json_encode($dataArray);                            
            }
        break;

        case "createProfile" :
            if($input->exist()){
                $ssn                    =   !empty($input->get("data")["ssn"])                  ? escape($input->get("data")["ssn"])                    : NULL;
                $fisrtname              =   !empty($input->get("data")["fisrtname"])            ? escape($input->get("data")["fisrtname"])              : NULL;
                $lastname               =   !empty($input->get("data")["lastname"])             ? escape($input->get("data")["lastname"])               : NULL;
                $birthdate              =   !empty($input->get("data")["birthdate"])            ? escape($input->get("data")["birthdate"])              : NULL;
                $sex                    =   !empty($input->get("data")["sex"])                  ? escape($input->get("data")["sex"])                    : NULL;
                $nationality            =   !empty($input->get("data")["nationality"])          ? escape($input->get("data")["nationality"])            : NULL;
                $phone_number           =   !empty($input->get("data")["phone_number"])         ? escape($input->get("data")["phone_number"])           : NULL;
                $driverlicense          =   !empty($input->get("data")["driverlicense"])        ? escape($input->get("data")["driverlicense"])          : NULL;
                
                //errors
                if(empty($ssn)              === true)   { $error = ["Social security Number is a required field"];}
                elseif(empty($firstname)    === true)   { $error = ["Firstname is a required field"];}
                elseif(empty($lastname)     === true)   { $error = ["Lastname is a required field"];}
                elseif(empty($birthdate)    === true)   { $error = ["Birthdate is a required field"];}
                elseif(empty($sex)          === true)   { $error = ["Sex is a required field"];}
                elseif(empty($nationality)  === true)   { $error = ["Nationality is a required field"];}
                
                if(empty($input->get("data")) === false and empty($error) === true){
                    $postArray =    [
                        "uuid"          =>  "{$settings->MakeUuid()}",
                        "ssn"           =>  "{$ssn}",
                        "firstname"     =>  "{$firstname}",
                        "lastname"      =>  "{$lastname}",
                        "birthdate"     =>  "{$birthdate}",
                        "sex"           =>  "{$sex}",
                        "nationality"   =>  "{$nationality}",
                        "phone_number"  =>  "{$phone_number}",
                        "driverlicense" =>  "{$driverlicense}",
                    ];
                    
                    if($search->setClient($postArray) > 0){
                        $dataArray  =   [
                            "data"          =>  "success",
                            "dataContent"   =>  "Profile is created",
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
        
        case "findClient" :
            if($input->exist()){
                $data       =   !empty($input->get("data")) ? escape($input->get("data")) : null;
                $clients    =   $client->findClient(ucfirst($data));

                if(empty($clients) === FALSE){                    
                    foreach($clients as $data){
                        $dataArray[] =  [
                            "uuid"      =>  "{$data->uuid}",
                            "bsnNumber" =>  "{$data->ssn}",
                            "firstname" =>  "{$data->firstname}",
                            "lastname" =>  "{$data->lastname}",
                        ];                    
                    }
                }else{
                    $dataArray = []; 
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