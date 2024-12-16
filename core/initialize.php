<?php
    //Usare queste definizioni in caso di hosting su sito online
    // defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
    // defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'localhost:9000'.DS.'travelAPI'); //http://localhost:9000/travelAPI/
    // defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT.DS.'includes');
    // defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT.DS.'core');

    //Load config file
    require_once("../../includes/config.php");
    
    //Load core classes
    require_once("paese.php");
    require_once("viaggio.php");
    
    //Usare questo path in caso di hosting su sito online
    // require_once(INC_PATH.DS."config.php");
    // require_once(CORE_PATH.DS."paese.php");
    
?>