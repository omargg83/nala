<?php
	session_name("admin");
	@session_start();
	if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}
	if (isset($_REQUEST['ctrl'])){$ctrl=$_REQUEST['ctrl'];}	else{ $ctrl="";}

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("America/Mexico_City");
	$_SESSION['des']=1;

	require_once("init.php");
	class Sagyc{
		public $nivel_personal;
		public $nivel_captura;
		public $limite=300;

		public function __construct(){
			date_default_timezone_set("America/Mexico_City");
			try{
				$this->Salud = array();
				$this->dbh = new PDO("mysql:host=".SERVIDOR.";port=".PORT.";dbname=".BDD, MYSQLUSER, MYSQLPASS);
				$this->dbh->query("SET NAMES 'utf8'");
			}
			catch(PDOException $e){
				return "Database access FAILED!";
			}
		}

		public function login(){
			$arreglo=array();
			if(isset($_SESSION['idpersona']) and $_SESSION['autoriza'] == 1) {
				$valor=$_SESSION['idfondo'];
				$arreglo=array('sess'=>"abierta", 'fondo'=>$valor);
			}
			else {
				///////////////////////////login
				$valor=$_SESSION['idfondo'];
				$arreglo=array('sess'=>"cerrada", 'fondo'=>$valor);
				//////////////////////////fin login
			}
			return json_encode($arreglo);
		}
		public function salir(){
			$_SESSION['autoriza'] = 0;
			$_SESSION['idpersona']="";
		}
		public function ses(){
			if($_SESSION['autoriza']==1){
				$arr=array();
				$arr=array('sess'=>"abierta");
				return json_encode($arr);
			}
			else{
				$arr=array();
				$arr=array('sess'=>"cerrada");
				return json_encode($arr);
			}
		}

		public function insert($DbTableName, $values = array()){
			$arreglo=array();
			try{
				$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				foreach ($values as $field => $v)
				$ins[] = ':' . $field;

				$ins = implode(',', $ins);
				$fields = implode(',', array_keys($values));
				$sql="INSERT INTO $DbTableName ($fields) VALUES ($ins)";
				$sth = $this->dbh->prepare($sql);
				foreach ($values as $f => $v){
					$sth->bindValue(':' . $f, $v);
				}
				if ($sth->execute()){
					$last=$this->lastId = $this->dbh->lastInsertId();
					$arreglo+=array('id'=>$last);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>'');
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$e->getMessage());
				return json_encode($arreglo);
			}
		}
		public function update($DbTableName, $id = array(), $values = array()){
			$arreglo=array();
			try{
				$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$x="";
				$idx="";
				foreach ($id as $field => $v){
					$condicion[] = $field.'= :' . $field."_c";
				}

				$condicion = implode(' and ', $condicion);
				foreach ($values as $field => $v){
					$ins[] = $field.'= :' . $field;
				}
				$ins = implode(',', $ins);

				$sql2="update $DbTableName set $ins where $condicion";
				$sth = $this->dbh->prepare($sql2);
				foreach ($values as $f => $v){
					$sth->bindValue(':' . $f, $v);
				}
				foreach ($id as $f => $v){
					if(strlen($idx)==0){
						$idx=$v;
					}
					$sth->bindValue(':' . $f."_c", $v);
				}
				if($sth->execute()){
					$arreglo+=array('id'=>$idx);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>'');
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$e->getMessage());
				return json_encode($arreglo);
			}
		}
		public function borrar($DbTableName, $key, $id){
			$arreglo=array();
			try{
				$sql="delete from $DbTableName where $key=$id";
				$sth = $this->dbh->prepare($sql);
				$a=$sth->execute();
				if($a){
					$arreglo+=array('id1'=>$id);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>'');
					$arreglo+=array('id2'=>'');
					$arreglo+=array('id3'=>'');
					return json_encode($arreglo);
				}
				else{
					$arreglo+=array('id1'=>$id);
					$arreglo+=array('error'=>1);
					$b=$sth->errorInfo();
					$arreglo+=array('terror'=>$b[2]);
					$arreglo+=array('id2'=>'');
					$arreglo+=array('id3'=>'');
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo+=array('id1'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$e->getMessage());
				return json_encode($arreglo);
			}
		}
		public function tienda($id){
			try{

				$sql="select * from tienda where idtienda=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
	}


	if(strlen($ctrl)>0){
		$db = new Sagyc();
		if(strlen($function)>0){
			echo $db->$function();
		}
	}

	function clean_var($val){
		$val=htmlspecialchars(strip_tags(trim($val)));
		return $val;
	}
?>
