<?php
$server=3;
$_SESSION['des']=0;
$_SESSION['pagina']=50;

if($server==2){
  //////////localhost
  define("MYSQLUSER", "root");
  define("MYSQLPASS", "root");
  define("SERVIDOR", "localhost");
  define("BDD", "sagycrmr_nala");
  define("PORT", "3306");
}
else if($server==3){
  //////////localhost
  define("MYSQLUSER", "sagyccom_esponda");
  define("MYSQLPASS", "esponda123$");
  define("SERVIDOR", "sagyc.com.mx");
  define("BDD", "sagycrmr_nala");
  define("PORT", "3306");
}
else if($server==4){
  //////////localhost 2
  define("MYSQLUSER", "root");
  define("MYSQLPASS", "root");
  define("SERVIDOR", "localhost");
  define("BDD", "sagycrmr_nala");
  define("PORT", "8889");
}
?>
