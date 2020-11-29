<?php
	session_name("chingon");
	@session_start();

	if (isset($_REQUEST['function'])){$function=clean_var($_REQUEST['function']);}	else{ $function="";}

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

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
				$passPOST = strip_tags($_REQUEST["passAcceso"]);

				$passPOST=md5("sagyc%chingon$%&/()=".$passPOST);
				$passPOST=hash("sha512",$passPOST);

				$sql="SELECT * FROM usuarios where correo=:user and pass=:pass and activo=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":user",$userPOST);
				$sth->bindValue(":pass",$passPOST);
				$sth->execute();
				$CLAVE=$sth->fetch();

				if(is_array($CLAVE)){
					if($userPOST == $CLAVE['correo'] and strtoupper($passPOST)==strtoupper($CLAVE['pass'])){
						$_SESSION['autoriza']=1;
						$_SESSION['nombre']=$CLAVE['nombre'];

						$_SESSION['nick']=$CLAVE['user'];
						$_SESSION['idusuario']=$CLAVE['idusuario'];
						$_SESSION['idtienda']=$CLAVE['idtienda'];
						$_SESSION['idsucursal']=$CLAVE['idsucursal'];
						$_SESSION['nivel']=$CLAVE['nivel'];
						$_SESSION['sidebar']=$CLAVE['sidebar'];
						$_SESSION['idcaja']=$CLAVE['idcaja'];
						$_SESSION['foto']=$CLAVE['archivo'];

						$sucursal=self::sucursal($CLAVE['idsucursal']);
						$_SESSION['sucursal_nombre']=$sucursal->nombre;
						$_SESSION['matriz']=$sucursal->matriz;

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

		public function rec(){
			if($_SERVER['REQUEST_METHOD']!="POST"){
				return 0;
			}
			$userPOST = htmlspecialchars($_REQUEST["userAcceso"]);

			$sql="SELECT * FROM usuarios where (user=:usuario or correo=:correo) and activo=1";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":usuario",$userPOST);
			$sth->bindValue(":correo",$userPOST);
			$sth->execute();
			$res=$sth->fetch(PDO::FETCH_OBJ);

			if($sth->rowCount()>0){
				if(strlen(trim($res->correo))>0){

					$pass_nuevo=self::genera_random(8);

					$pass=md5("sagyc%chingon$%&/()=".$pass_nuevo);
					$encriptado=hash("sha512",$pass);

					$sql="update usuarios set pass=:pass where idusuario=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":pass",$encriptado);
					$sth->bindValue(":id",$res->idusuario);
					$sth->execute();

					$texto="La nueva contraseña es: <br> $pass_nuevo";
					$texto.="<br></a>";
					return self::correo($res->correo,$texto,"correo");
				}
				else{
					$arreglo=array();
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"no tiene correo registrado en la plantilla");
				}
				return json_encode($arreglo);
			}
			else{
				return 0;
			}
		}
		public function correo($correo, $texto, $asunto){
			/////////////////////////////////////////////Correo
			require '../vendor/autoload.php';
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';

			$mail->Body    = $asunto;
			$mail->Subject = $asunto;
			$mail->AltBody = $asunto;

			$mail->isSMTP();

			////////////cambiar esta configuracion
				$mail->Host = "smtp.gmail.com";						  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication

				$mail->Username = "soportesagyc@gmail.com";       // SMTP username
				$mail->Password = "SAGYC123$";                       // SMTP password  <----------- AGREGAR AQUI EL PASSWORDS

				$mail->SMTPSecure = "ssl";                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465;                                    // TCP port to connect to
				$mail->CharSet = 'UTF-8';

				$mail->From = "soportesagyc@gmail.com";   //////////esto solo muestra el remitente
				$mail->FromName = "Sistema";			//////////// remitente
			//////////hasta aca

			$mail->IsHTML(true);
			$mail->addAddress($correo);

			$mail->msgHTML($texto);
			$arreglo=array();

			if (!$mail->send()) {
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$mail->ErrorInfo);
				return json_encode($arreglo);
			} else {
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>'Se nofiticó al correo: '.$correo.' la nueva contraseña');
				return json_encode($arreglo);
			}
		}
		public function genera_random($length = 15) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
	}
	function clean_var($val){
		$val=htmlspecialchars(strip_tags(trim($val)));
		return $val;
	}

	$db = new sagyc();
	if(strlen($function)>0){
		echo $db->$function();
	}

//c11766f33c25b37d3697365b0c73f9d0
?>
