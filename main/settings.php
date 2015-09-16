<?php
    global $conf_host;
    $activate = TRUE;
    $data_base = TRUE;
        $type_db = "MySQL"; //MySQL
        define('host', 'localhost');
        define('user', 'root');
        define('pw', 'Sistema');
        define('db', 'sistema');
    $INSTALLED_APPS = [
    "general"
    ];
    $conf_host = [
      'host' => 'http://localhost/practicas/',
      'lang' => 'es'
    ];
   if ($data_base){require_once 'db.php';}
   login_app($INSTALLED_APPS);
   $objects = [
      "principal"=> new principal(),
      "maestro"
   ];
    if ($activate) {
      require_once 'url.php';
    }else{
      $objects['principal']->system_off();
    }
?>