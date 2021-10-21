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

        case "updateProfile" :
            if($input->exist()){
                $uuid                   =   !empty($input->get("data")["uuid"])                 ? $input->get("data")["uuid"]                           : NULL;
                $ssn                    =   !empty($input->get("data")["ssn"])                  ? escape($input->get("data")["ssn"])                    : NULL;
                $firstname              =   !empty($input->get("data")["firstname"])            ? escape($input->get("data")["firstname"])              : NULL;
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
                        "uuid"          =>  "{$uuid}",
                        "ssn"           =>  "{$ssn}",
                        "firstname"     =>  ucfirst("{$firstname}"),
                        "lastname"      =>  ucfirst("{$lastname}"),
                        "birthdate"     =>  "{$birthdate}",
                        "sex"           =>  "{$sex}",
                        "nationality"   =>  "{$nationality}",
                        "phone_number"  =>  "{$phone_number}",
                        "driverlicense" =>  "{$driverlicense}",
                    ];

                    if($client->updateClient($postArray) > 0){
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

        case "getClient" :
            if($input->exist("get")){
                $uuid   =   !empty($input->get("uuid")) ? $input->get("uuid") : NULL;
                $data   =   $client->getClients($uuid);
                $dataArray = [
                    "uuid"          =>  "{$data->uuid}",
                    "ssn"           =>  "{$data->ssn}",
                    "firstname"     =>  "{$data->firstname}",
                    "lastname"      =>  "{$data->lastname}",
                    "birthdate"     =>  "{$data->birthdate}",
                    "sex"           =>  "{$data->sex}",
                    "nationality"   =>  "{$data->nationality}",
                    "phone_number"  =>  "{$data->phone_number}",
                    "driverlicense" =>  !empty($data->driverlicense) ? true : false,
                ];
                echo json_encode($dataArray);
            }
        break;
        
        case "getClients" :
            echo json_encode($client->getClients());
        break;

        case "lastPeopleHelped" :
            $data = $client->lastPeopleHelped();
            foreach($data as $item){  
                $date     =  new DateTime($item->createdate);              
                $dataArray[]    =    [
                    "firstname"  =>  "{$item->firstname}",
                    "lastname"   =>  "{$item->lastname}",
                    "bsnNumber"  =>  "{$item->ssn}",
                    "createdate" =>  "{$date->format('l d, F Y')}",
                    "createtime" =>  "{$date->format('H:i')}",
                ];
            }

            echo json_encode($dataArray);
           
        break;

        case "updateDocument" :
            if($input->exist()){
                $contentUuid    =   !empty($input->get("data")["contentUuid"])  ?   $input->get("data")["contentUuid"]      : null;
                $subject        =   !empty($input->get("data")["subject"])      ?   escape($input->get("data")["subject"])  : null;
                $content        =   !empty($input->get("data")["content"])      ?   $input->get("data")["content"]          : null;

                if(empty($subject)      === true) { $error = ["Subject is required value"];}
                elseif(empty($content)  === true) { $error = ["Content is required value"];}

                if(empty($input->get("data")) === false and empty($error) === true){
                    $postArray  =   [
                        "contentUuid"   =>  "{$contentUuid}",
                        "subject"       =>  "{$subject}",
                        "content"       =>  "{$content}",
                        "postUpdate"    =>  date("Y-m-d H:i:s"),
                    ];

                    if($client->updateDocument($postArray) > 0){
                        $dataArray  =   [
                            "data"          =>  "success",
                            "dataContent"   =>  "Document is updated",
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

        case "getUpdateDocument" :
            if($input->exist("get")){
                $uuid       =   !empty($input->get("uuid")) ? $input->get("uuid") : NULL;
                $data       = $client->getReadDocument($uuid);
                $dataArray = [
                    "contentUuid"   =>  "{$data->contentUuid}",
                    "clientUuid"    =>  "{$data->clientUuid}",
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
                $data       =   $client->getReadDocument($uuid);
                if(!empty($data)){ 
                    $dataArray  =   [
                        "ssn"           =>  "{$data->ssn}",
                        "firstname"     =>  "{$data->firstname}",
                        "lastname"      =>  "{$data->lastname}",
                        "createdate"    =>  "{$data->createdate}",
                        "UserUuid"      =>  "{$data->UserUuid}",
                        "MeUuid"        =>  "{$me->me()->uuid}",
                        "MeCnumber"     =>  "{$data->MeCnumber}",
                        "MeRank"        =>  "{$data->MeRank}",
                        "MeFname"       =>  "{$data->MeFname}",
                        "MeLname"       =>  "{$data->MeLname}",
                        "subject"       =>  "{$data->subject}",
                        "content"       =>  "{$data->content}",
                        "contentUuid"   =>  "{$data->contentUuid}",
                    ];
                }else{
                    $dataArray  =   [];
                }
                echo json_encode($dataArray);
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
                        $postUpdate  =  new DateTime($data->postUpdate);
                        
                        $dataArray[] =    [
                            "contentUuid" =>  "{$data->contentUuid}",
                            "subject"      =>  "{$data->subject}",
                            "createdate"   =>  "{$date->format('l d, F Y')}",
                            "time"         =>  "{$date->format('H:i')}",
                            
                            "postUpdate"   =>  !empty($data->postUpdate) ? "{$postUpdate->format('l d, F Y')}" :  null,
                            "timeUpdate"   =>  !empty($data->postUpdate) ? "{$postUpdate->format('H:i')}" :  null,
                            
                            "UserUuid"     =>  "{$data->UserUuid}",
                            "MeUuid"       =>  "{$me->me()->uuid}",
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
                $firstname              =   !empty($input->get("data")["firstname"])            ? escape($input->get("data")["firstname"])              : NULL;
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
                        "firstname"     =>  ucfirst("{$firstname}"),
                        "lastname"      =>  ucfirst("{$lastname}"),
                        "birthdate"     =>  "{$birthdate}",
                        "sex"           =>  "{$sex}",
                        "nationality"   =>  "{$nationality}",
                        "phone_number"  =>  "{$phone_number}",
                        "driverlicense" =>  "{$driverlicense}",
                    ];
                    
                    if($client->setClient($postArray) > 0){
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