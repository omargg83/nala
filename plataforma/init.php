<?php
$server=3;
$_SESSION['des']=0;
if($server==1){
  /////////remoto
  define("MYSQLUSER", "saludpublica");
  define("MYSQLPASS", "saludp123$");
  define("SERVIDOR", "172.16.0.20");
  define("BDD", "sagycrmr_sagyc");
  define("PORT", "3306");
}
else if($server==2){
  //////////localhost
  define("MYSQLUSER", "root");
  define("MYSQLPASS", "root");
  define("SERVIDOR", "localhost");
  define("BDD", "sagycrmr_sagyc");
  define("PORT", "3306");
}
else if($server==3){
  //////////localhost
  define("MYSQLUSER", "sagyccom_esponda");
  define("MYSQLPASS", "esponda123$");
  define("SERVIDOR", "sagyc.com.mx");
  define("BDD", "sagycrmr_sagyc");
  define("PORT", "3306");
}
else if($server==4){
  //////////localhost 2
  define("MYSQLUSER", "root");
  define("MYSQLPASS", "root");
  define("SERVIDOR", "localhost");
  define("BDD", "sagycrmr_sagyc");
  define("PORT", "8889");
}
?>
