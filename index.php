<?php 
    //chek if https is set
    $protocol   = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    
    /*if( $protocol === "http"){
        header("Location:{$_SERVER["REQUEST_SCHEME"]}s://{$_SERVER["SERVER_NAME"]}");        
    }*/

    define('_BOANN', 1);
    if (defined('_BOANN'))
        {
            //include default settings
            define('BPATH_BASE',    dirname(__FILE__) );
            
            require_once BPATH_BASE . '/includes/defines.php';
            require_once BPATH_BASE . '/includes/framework.php';

            $view   = NEW \classes\view\emsView;
            
            if(!empty($_GET["logout"])){
                $view->logout();
            }else{                
                $view->view($view);                 
            }
        }