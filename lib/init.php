<?php
$server=1;
if($server==1){
  /////////remoto
  define("MYSQLUSER", "saludpublica");
  define("MYSQLPASS", "saludp123$");
  define("SERVIDOR", "172.16.0.20");
  define("BDD", "salud");
}
else if($server==2){
  //////////localhost
  define("MYSQLUSER", "root");
  define("MYSQLPASS", "root");
  define("SERVIDOR", "localhost");
  define("BDD", "salud");
}

?>
