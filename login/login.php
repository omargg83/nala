<?php
	session_name("chingon");
	@session_start();

	require_once("../init.php");
	class sagyc{
		public $nivel_personal;
		public $nivel_captura;

		public function __construct(){
			date_default_timezone_set("America/Mexico_City");
			try{
				$this->dbh = new PDO("mysql:host=".SERVIDOR.";dbname=".BDD, MYSQLUSER, MYSQLPASS);
				$this->dbh->query("SET NAMES 'utf8'");
			}
			catch(PDOException $e){
				return "Database access FAILED!";
			}
		}
		public function acceso(){
			try{
				if($_SERVER['REQUEST_METHOD']!="POST"){
					return 0;
				}
				$userPOST = htmlspecialchars($_REQUEST["userAcceso"]);
				$passPOST = md5($_REQUEST["passAcceso"]);

				$sql="SELECT * FROM usuarios where user=:user and pass=:pass and activo=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":user",$userPOST);
				$sth->bindValue(":pass",$passPOST);
				$sth->execute();
				$CLAVE=$sth->fetch();

				if(is_array($CLAVE)){
					if($userPOST == $CLAVE['user'] and strtoupper($passPOST)==strtoupper($CLAVE['pass'])){
						$_SESSION['autoriza']=1;
						$_SESSION['nombre']=$CLAVE['nombre'];

						$_SESSION['nick']=$CLAVE['user'];
						$_SESSION['idusuario']=$CLAVE['idusuario'];
						$_SESSION['foto']=$CLAVE['file_foto'];
						$_SESSION['idtienda']=$CLAVE['idtienda'];
						$_SESSION['idsucursal']=$CLAVE['idsucursal'];
						$_SESSION['nivel']=$CLAVE['nivel'];
						$_SESSION['sidebar']=$CLAVE['sidebar'];
						$_SESSION['idcaja']=$CLAVE['idcaja'];

						$sucursal=self::sucursal($CLAVE['idsucursal']);
						$_SESSION['sucursal_nombre']=$sucursal->nombre;

						$fecha=date("Y-m-d");
						list($anyo,$mes,$dia) = explode("-",$fecha);

						$tienda=self::tienda($CLAVE['idtienda']);
						$_SESSION['n_sistema']=$tienda->nombre_sis;
						$_SESSION['a_sistema']=$tienda->activo;

						if($_SESSION['a_sistema']==1){
							$_SESSION['idfondo']=$CLAVE['idfondo'];
						}
						else{
							$_SESSION['idfondo']="";
						}

						$_SESSION['cfondo']="white";
						$_SESSION['foco']=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
						$_SESSION['cfondo']="white";

						$arr=array();
						$arr=array('acceso'=>1);
						return json_encode($arr);
					}
				}

				$arr=array();
				$arr=array('acceso'=>0);
				return json_encode($arr);
				/////////////////////////////////////////////
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function sucursal($id){
			$sql="select * from sucursal where idsucursal=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$id);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		public function tienda($id){
			$sql="select * from tienda where idtienda=:idtienda";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":idtienda",$id);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
	}
	function clean_var($val){
		$val=htmlspecialchars(strip_tags(trim($val)));
		return $val;
	}

	$db = new sagyc();
	echo $db->acceso();

?>
