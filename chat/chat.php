<?php
	require_once("../control_db.php");

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once("../init.php");

class Chat extends Sagyc{
		public function __construct(){
			date_default_timezone_set("America/Chicago");
			$this->Salud = array();
			$this->dbh = new PDO("mysql:host=".SERVIDOR.";port=".PORT.";dbname=".BDD, MYSQLUSER, MYSQLPASS);
			$this->dbh->query("SET NAMES 'utf8'");
		}

		public function conectado(){
			try{
				$sql="select * from chat_conectados where idpersona='".$_SESSION['idusuario']."'";
				$stmt= $this->dbh->query($sql);
				if($stmt->rowCount()==0){
					$fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

					if(strlen($_SESSION['nick'])>0){
						$nick=$_SESSION['nick'];
					}
					else{
						$nick=$_SESSION['nombre'];
					}
					$sql="insert into chat_conectados (idpersona, ultima, foto, nick, nombre) values ('".$_SESSION['idusuario']."', '$fecha', '".$_SESSION['foto']."', '$nick', '".$_SESSION['nombre']."')";
					$this->dbh->query($sql);
					$_SESSION["tchat"]=$fecha;
				}
				else{
					try{
						if(strlen($_SESSION['nick'])>0){
							$nick=$_SESSION['nick'];
						}
						else{
							$nick=$_SESSION['nombre'];
						}
						$fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
						$sql="update chat_conectados set ultima='$fecha', foto='".$_SESSION['foto']."', nick='$nick', nombre='".$_SESSION['nombre']."' where idpersona='".$_SESSION['idusuario']."'";
						$this->dbh->query($sql);
						$_SESSION["tchat"]=$fecha;
					}
					catch(PDOException $e){
						return "Database access FAILED!".$e->getMessage();
					}
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function inicia(){
			$_SESSION["carga"]=1;
			echo  "<li class='nav-item dropdown'>";
				echo  "<a class='nav-link dropdown-toggle' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
				  <i class='fab fa-rocketchat fa-spin' style='color:#07009e !important;'></i> Chat";
				echo  "</a>";

				echo  "<div id='myUL' class='dropdown-menu' aria-labelledby='navbarDropdown' style='width:200px;max-height:400px !important; overflow: scroll; overflow-x: hidden;'>";
				echo "<div class='row'><div class='col-12'><input type='text' id='myInput' placeholder='Buscar..' title='Buscar' class='form-control' autocomplete='off'></div></div>";
					echo "<div id='conecta_x'>";
						echo self::conectados();
					echo  "</div>";
				echo  "</div>";
			echo  "</li>";
		}
		public function conectados(){
			$this->conectado();
			$fecha2=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-100;
			$sql="select chat_conectados.idpersona,chat_conectados.nick,chat_conectados.foto, chat_conectados.nombre, chat_conectados.ultima from chat_conectados where chat_conectados.idpersona!='".$_SESSION['idusuario']."' order by chat_conectados.ultima desc";
			$sth=$this->dbh->query($sql);
			$row=$sth->fetchAll(PDO::FETCH_OBJ);

			foreach($row as $key){
				echo "<a class='dropdown-item' onclick='register_popup(\"".$key->idpersona."\")'>";
				if(($key->ultima-$fecha2)>0){
					echo "<i class='fab fa-rocketchat' style='color:black;'></i>";
				}
				else{
					echo "<i class='fas fa-user-clock'></i>";
				}
				echo $key->nick;
				echo  "</a>";
			}
		}
		public function carga(){
			$idpersona=$_REQUEST['id'];
			$x="";
			///////////////////////////////////////////////////////
			$fecha=mktime(0,0,0,date("m"),date("d"),date("Y"))-100;
			$sql="select * from chat_conectados where idpersona='$idpersona'";
			$sth = $this->dbh->query($sql);
		 	$pers=$sth->fetch(PDO::FETCH_OBJ);

			echo "<div id='opcion_$idpersona' class='opcionbox'>";
				$arreglo=array();
				$directory="../chat/smileys/";
				$dirint = dir($directory);
				$contar=0;
				while (($archivo = $dirint->read()) !== false){
					if ($archivo != "." && $archivo != ".." && $archivo != "" && (substr($archivo,-4)==".png" || substr($archivo,-4)==".gif")){
						$arreglo[$contar]=$directory.$archivo;
						echo "<img src='chat/smileys/$archivo' width='30px' class='emojiimg mb-2' data-id='$idpersona' data-lugar='chat/smileys/$archivo'>";
						$contar++;
					}
				}
			echo "</div>";

				echo  "<div class='chat-header mb-3' id='head$idpersona'>";
					echo  "<div class='row'>";
						echo  "<div class='col-3'>";
							if(strlen($pers->foto)>0 and file_exists("".$this->f_usuarios."/".$pers->foto)){
								echo "<img src='".$this->f_usuarios."/".$pers->foto."' width='40px' class='rounded-circle'/>";
							}
							else{
								echo "<img src='img/user.jpg' width='40px' class='rounded-circle'/>";
							}
						echo "</div>";
						echo  "<div class='col-6' style='padding: 5px;top: 6px;font-size:12px'>";
							echo  "<label title='".$pers->nick."'>".$pers->nick;
							echo "</label>";

						echo "</div>";
						echo  "<div class='col-3'>";
								echo  "<a class='btn-clipboard' onclick='close_popup(\"$idpersona\")'><i class='fas fa-times-circle'></i></a>";
								echo  "<a id='min$idpersona' class='btn-clipboard' onclick='minimizar(\"$idpersona\")'><i class='fas fa-window-minimize'></i></a>";
								echo  "<a id='max$idpersona' style='display:none;'  class='btn-clipboard' onclick='maximizar(\"$idpersona\")'><i class='fas fa-window-maximize'></i></a>";
						echo  "</div>";
					echo  "</div>";
				echo  "</div>";

				echo  "<div class='card-body contenido'  id='contenido$idpersona'
				ondragenter='return enter(event)'
				ondragover='return over(event)'
				ondragleave='return leave(event)'
				ondrop='return drop(event,".$idpersona.")'>";

				$sql="select * from chat where ((de='".$_SESSION['idusuario']."' and para='$idpersona') or (de='$idpersona' and para='".$_SESSION['idusuario']."')) order by idchat asc";
				$sth = $this->dbh->query($sql);
			 	$men=$sth->fetchAll(PDO::FETCH_OBJ);


				$refresh="";
				foreach ($men as $key){
					if($key->de==$_SESSION['idusuario']){
						echo  "<div class='b2'>";
						echo  "<b>Yo:</b>";
						$refresh="para".$key->paran;
						$nick=$key->paran;
					}
					else{
						echo  "<div class='burbuja'>";
						echo  "<b>".$key->den.":</b>";
						$refresh="para".$key->den;
						$nick=$key->den;
					}
					echo  "<br>".$key->mensaje;
					echo  "<span id='horachat'>";
					echo  $this->fecha($key->envio);
					echo  "</span></div>";
					$_SESSION["tchat"]=$key->envio;
				}
				echo  "</div>";

				echo  "<div class='card-footer popup-messages-footer' id='mensajex$idpersona'>";
					echo  "<div class='row'>";
						echo  "<div class='col-sm-12'>";
							echo "<div contenteditable='true' class='mensaje_chat' data-para='$idpersona' id='mensaje_$idpersona' name='mensaje_$idpersona' onclick='leido($idpersona)'>";
						echo "</div>";

						echo  "</div>";
						echo  "</div>";
						echo  "<div class='row'>";
							echo  "<div class='col-sm-12 btn-footer'>";
								echo  "<div class='btn-group' role='group' aria-label='Basic example' style='font-color:white;'>";

									echo  "<button title='Mandar' class='btn btn-outline-secondary btn-sm' onclick='mensaje_manda(document.getElementById(\"mensaje_$idpersona\").value,\"$idpersona\")'><i class='fas fa-location-arrow'></i></button>";

									echo  "<button class='btn btn-outline-secondary btn-sm emoji' data-id='$idpersona'><i class='far fa-smile-wink'></i></button>";

									echo  "<button class='btn btn-outline-secondary btn-sm btn-file'>";
									echo  "<i class='fas fa-paperclip'></i><input class='form-control' type='file'
											id='subechat_$idpersona'
											name='subechat_$idpersona'
											data-control='subechat_$idpersona'
											data-ruta='tmp'
											data-funcion='carga_archivo'
											data-urlx='chat/chat.php'
											data-id='".$idpersona."'
											data-iddest='$idpersona'
											data-divdest='trabajo'
											data-dest='a_comite/editar.php?id='
											>";
									echo  "</button>";
								echo  "</div>";
							echo  "</div>";
						echo  "</div>";
					echo  "</div>";
				echo  "</div>";
		}
		public function manda(){
			$x="";
			$mensaje=$_REQUEST['texto'];
			$idpersona=$_REQUEST['id'];
			$tam=strlen(trim($mensaje));
			if($tam>0){
				if(strlen($mensaje)>0){
					$fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
					$sql="insert into chat (de,para,mensaje,envio,leido,den) values ('".$_SESSION['idusuario']."','$idpersona','$mensaje','$fecha','0','".$_SESSION['nick']."')";
					$this->dbh->query($sql);
					echo  "<div class='b2'>";
					echo  "<b>Yo:</b>";
					echo  "<br>".$mensaje;
					echo  "<span id='horachat'>$fecha";
					echo  date('Y-m-d H:i:s',$fecha);
					echo  "</span></div>";
				}
			}
			return $x;
		}
		public function nuevos(){
			$arr=array();
			if($_SESSION["carga"]==1){
				$_SESSION["carga"]=0;
				$sql="select * from chat where para='".$_SESSION['idusuario']."' and (envio>'".$_SESSION["tchat"]."' or leido=0) order by envio asc,idchat asc";
			}
			else{
				$sql="select * from chat where para='".$_SESSION['idusuario']."' and (envio>'".$_SESSION["tchat"]."' and leido=0) order by envio asc,idchat asc";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$men=$sth->fetchAll();
			if(isset($_SESSION['idusuario']) and count($men)>0){
				for($i=0;$i<count($men);$i++){
					$x="<div class='burbuja'>";
					$x.= "<b>".$men[$i]['den'].":</b>";
					$x.= "<br>".$men[$i]['mensaje'];
					$x.= "<span id='horachat'>";
					$x.= date('Y-m-d H:i:s',$men[$i]['envio']);
					$x.= "</span></div>";
					$arr[$i] = array('para' => $men[$i]['de'],'texto' => $x,'de' => $_SESSION['idusuario']);
					$_SESSION["tchat"]=$men[$i]['envio'];
				}
				$myJSON = json_encode($arr);
				return $myJSON;
			}
			else{
				$myJSON = json_encode($arr);
				return $myJSON;
			}
		}
		public function leido(){
			$idpersona=$_REQUEST['id'];
			$sql="update chat set leido=1 where para='".$_SESSION['idusuario']."' and de='$idpersona'";
			$this->dbh->query($sql);
		}
		public function carga_archivo(){
			$x="";
			$arreglo =array();
			if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
			if (isset($_REQUEST['archivo'])){$archivo=$_REQUEST['archivo'];}
			if (isset($_REQUEST['original'])){$original=$_REQUEST['original'];}
			$file="";
			$info = new SplFileInfo($archivo);
			$ext=$info->getExtension();

			if($ext=="jpg" or $ext=="png"){
				$file.="<img src=\'tmp/$archivo\' width=\'100%\' />";
			}
			$fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
			$file.="<a class=\'htipo1\' href=\'tmp/".$archivo."\' target=\'_blank\'><i class=\'fas fa-paperclip\'></i>".$original."</a>";
			$sql="insert into chat (de,para,mensaje,envio,den,leido) values ('".$_SESSION['idusuario']."','$id','$file','$fecha','".$_SESSION['nick']."','0')";
			$this->dbh->query($sql);

			echo  "<div class='b2'>";
			echo  "<b>Yo:</b>";
			if($ext=="jpg" or $ext=="png"){
				echo "<img src='tmp/$archivo' width='100%' />";
			}
			echo  "<br><a href='tmp/$archivo' target='_blank'><i class='fas fa-paperclip'></i>$original";
			echo "</a>";
			echo  "<span id='horachat'>$fecha";
			echo  date('Y-m-d H:i:s',$fecha);
			echo  "</span></div>";
			return $x;
		}
		public function subir_archivo(){
			$id=$_GET['id'];
			$ruta=$_GET['ruta'];
			$contarx=0;
			$arr=array();

			foreach ($_FILES as $key){
				$extension = pathinfo($key['name'], PATHINFO_EXTENSION);
				$n = $key['name'];
				$s = $key['size'];
				$string = trim($n);
				$string = str_replace( $extension,"", $string);
				$string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
				$string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string );
				$string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string );
				$string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string );
				$string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string );
				$string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string );
				$string = str_replace( array(' '), array('_'), $string);
				$string = str_replace(array("\\","¨","º","-","~","#","@","|","!","\"","·","$","%","&","/","(",")","?","'","¡","¿","[","^","`","]","+","}","{","¨","´",">","<",";",",",":","."),'', $string );
				$string.=".".$extension;

				$n_nombre=$id."_".$contarx."_".$string;
				$destino="../".$ruta."/".$n_nombre;

				if(move_uploaded_file($key['tmp_name'],$destino)){
					chmod($destino,0666);
					$arr[$contarx] = array("archivo" => $n_nombre,"original" => $n);
				}
				else{

				}
				$contarx++;
			}
			$myJSON = json_encode($arr);
			return $myJSON;
		}
		public function fecha($fecha){
			$fecha = new DateTime(date('Y-m-d H:i:s',$fecha));
			return $fecha->format('d-m-Y h:i:s');
		}
	}

	$db = new Chat();
	if(strlen($function)>0){
		echo $db->$function();
	}

?>
