<?php
//SOFTWARE ABSTRACTION LAYER ARTOS 
	/*
	 * 0: MkDir (Crear Directorio) (HECHO)
	 * 1: Chgrp	(Cambiar Grupo)(HECHO)
	 * 2: Write (Escribir)(HECHO)
	 * 3: Chmod	(cambiar permisos)(HECHO)
	 * 4: Chown	(Cambiar propietario del archivo o directorio)
	 * 5: MkFile (Crear Archivo)(HECHO)
	 * 6: SelPaq (Seleccionar paquete)
	 * 7: InstPaq  (Instalar paquete)
	 * 8: Rempaq (Remover paquete)
	 * 81: Shpaq (Buscar paquete)
	 * 82: Mkpaq (Crear paquete) 
	 * 9 rm (remove directorio(s) o archivo(s))
	 * 10: ls (listar directorios o archivos) (HECHO)
	 * 11: dir (listar directorios o archivos) (HECHO)
	 * 12: app (listar aplicaciones en ejecucion)
	 * 13: vuser (ver usuarios del sistema)
	 * 14: vgroup (ver grupos del sistema)
	 * 15: callb (llamar a una funcion del sistema)
	 * 16: insbd (Insertar Datos en la base de datos)
	 * 17: updbd (Actualizar Datos en la base de datos)
	 * 18: delbd (Eliminar Datos en la base de datos)
	 * 19: selbd (Seleccionar consulta de datos en la base de datos)
	 * 20: commands (Ejecutar)(HECHO)
	 * 		parametro -a: para ejecutar aplicaciones
	 * 		parametro -c: para ejecutar comandos
	 * 		parametro -h: para ayuda (Para todos los comandos)
	 * 21: commandsA (Unicamente para Ejecutar Aplicacion)
	 * 22: start (Iniciar SAL)
	 * 23: stop (Detener SAL)
	 * 24: restart (Reiniciar SAL)
	 * 25: salconf (Editar configuración de SAL)
	 * 26: appin (aplicaciones instaladas)
	 * 27: appdl (aplicaciones eliminadas recientemente)
	 * 28: depdl (eliminar paquetes huerfanos)
	 * //////////////////ARCHIVOS//////////////////////////////
	 * 29: ReadFile	(Leer en Archivo)
	 * 30: WriteFile (Escribir en Archivo)
	 * 31: OpFile (Abrir archivo)
	 * 32: ClFile (Cerrar Archivo)
	 * 33: ReadLineFile	(Leer Linea en Archivo)
	 * 34: ReadfToEOF	(Leer Linea en Archivo)
	 * 35: vprop Ver propiedades del sistema
	 * 36: cd Cambiar de directorio(HECHO)
	 * 37: df Ver espacio ocupado por el sistema
	 * 38: ds Ver espacio ocupado por N archivo o directorio
	 * 39: cd + show directory (ls + cd)(HECHO)
	 * 40: vdir ver directorio actual(HECHO)
	 * 41: sval buscar valor de una variable (HECHO)
	 * 42: read leer del teclado y almacenar en una variable
	 * */
class sal{
	protected $llamadas;
	protected $funciones;
	protected $llamadanum;
	protected $cantllamadas;
	protected $cantfunciones;
	protected $usuario;
	protected $promptd="ArtOS";
	protected $diractual;
	//Variables para ASM de ARTOS"xsqlart.class.php"
	//llamada al nucleo para ejecucion va a ser EXEC();=0x80h
	protected $args1;
	protected $args2;
	protected $ordenum;
	function setUsr($usur){
		$this->usuario=$usur;
		return 0;
	}
	function getUsr(){
		if($this->usuario!=''){
			return $this->usuario; 
		}
		else{
			return -1;
		}
	}
	function read($var,$contenido){
		if(preg_match("/^[@A-Za-z0-9]+/",$var,$match)){
			if($match[0]!=''){
				if(preg_match("/^[\$@A-Za-z0-9\"\'_-]+/",$contenido,$match)){
					if($match[0]!=''){
						$$var=$contenido;
						$this->defineVar($var,${$var});
						return 0;
					}
					else{
						return -4;
					}
				}
				else{
					return -3;
				}
			}
			return -2;
		}
		else{
			return -1;
		}
	}
	function setDir($direcotrio){
		if(is_dir($direcotrio)){
			$this->diractual=$direcotrio;
		}
		else{
			$this->diractual=getcwd();
		}
		$file=fopen("directorio_actual.txt","w");
		if($file){
			fwrite($file,$this->diractual);
			fclose($file);
			return 0;
		}
		else{
			return -1;
		}
	}
	function getDir(){
		if($this->diractual!=''){
			return $this->diractual; 
		}
		else{
			$this->diractual=file_get_contents("directorio_actual.txt");
			return $this->diractual; 
		}
	}
	function setOrder($ordenac){
		if(is_dir($direxactual)){
			$this->ordenum=$ordenac;
			return 0;
		}
		else{
			return -1;
		}
	}
	function getOrder(){
		if($this->ordenum!=''){
			return $this->ordenum; 
		}
		else{
			return -1;
		}
	}
	function showUsr(){
		if($this->usuario!=''){
			$this->printCall($this->usuario); 
		}
		else{
			return -1;
		}	
	}
	function setPrompt($prompt){
		$this->promptd=$prompt;
		return 0;
	}
	function getPrompt(){
		if($this->promptd!=''){
			return $this->promptd; 
		}
		else{
			return -1;
		}
	}
	function Strcallback($cadena){
		if($cadena!=''){
			$this->Strcall($cadena);		
		}
		else{
			return -1;
		}
	}
	function Strcall($cadena){
		if($this->existsCall($cadena)==0){
			$this->passParser($cadena);
		}
	}
	public function callback($call){
		$this->call($call);	
	}
	function call($numcal){
		if($this->existsCall($numcal)==0){
			$this->passParser($numcal);
		}
		else{
			return -1;
		}
	}
	////////////////////ASM DE ARTOS//////////////////////////
	function AddToStack($registro,$valor){
		if($this->IsValInRegistry()!=0){
		
		}
		else{
			return -1;
		}
	}
	
	function RmToStack(){
		if($this->IsValInRegistry()==0){
		
		}
		else{
			return -1;
		}
	}
	
	function pop($registro){
		if($this->IsValInRegistry()==0){
		
		}
		else{
			return -1;
		}
	}
	
	function push($registro){
		if($this->IsValInRegistry()==0){
		
		}
		else{
			return -1;
		}
	}
	
	 function IsValInRegistry($val){
		//devuelve el valor del registro si esta lleno
	}
	
	 function IsRegistryEmpty($val){
		//dvuelve si el registro esta vacio o no
	}
	
	 function callSystem(){
		
	}
	public function deleteFile($enlacefile){
		// error -1 la variable está vacía
		 // error -2 el archivo no existe 
		 // error -3 no se pudo eliminar el archivo, devuelve array de errores
		if(!empty($enlacefile)){
			if(file_exists($enlacefile)){
				if(unlink($enlacefile)){
					return;
				}
				else {
					return error_get_last();
				}
			}
			else {
				return -2;
			}
		}
		else{
			return -1;
		}
	}	
	 function sistema($cadena){
		if($cadena!=''){
			$this->commands($cadena);
			$this->goPrompt();
		}
		else{
			$this->goPrompt();
		}
	}
	/*.
	 * 7: InstPaq  (Instalar paquete)
	 * 8: Rempaq (Remover paquete)
	 * 81: Shpaq (Buscar paquete)
	 * 82: Mkpaq (Crear paquete) 
	 * */
	function comprime_varios_zip($directorio){
		
	}
	function comprime_uno_zip($directorio){
		
	}
	function descomprimezip($directorio){
		
	}
	
	function installpaq($directorio,$paquete){
		/*
		 * Encontrar en la carpeta que se descomprima
		 * los siguientes archivos:
		 * index_carpeta.php
		 * estilo_carpeta.css
		 * funciones_carpeta.js
		 * index_carpeta.xml
		 * bd_carpeta.sql
		 */
	}
	
	function selectpaq($paquete){
		
	}
	
	function searchpaq($paquete){
		
	}
	
	function removepaq($paquete){
		
	}
	////////////////////////////////////////////////////////////////////////////////////
	 function commands($cadena){
		if(is_numeric($cadena)){
			//ASM ARTOS
			$this->setOrder($cadena);
		}
		else{
			$args=$this->passParser($cadena);
			if($args[0]=='dir' || $args[0]=='ls'){
				if($args[1]!=''){
					$this->dir($args[1]);				
				}
				else{
					$direc=$this->getDir();
					$this->dir($direc);
				}
			}
			elseif($args[0]=='cd' || $args[0]=='sd'){
				if($args[1]!=''){
					$this->cd($args[1]);
				}
				else{
					$this->writeCall("\nFALTA UN ARGUMENTO\n");
				}
			}
			elseif($args[0]=='mkdir'){
				if($args[1]!=''){
					$this->mkdir_art($args[1]);
				}
				else{
					$this->writeCall("\nFALTA UN ARGUMENTO\n");
				}
			}
			elseif($args[0]=='mkfile'){
				if($args[1]!=''){
					$this->mkfile($args[1]);
				}
				else{
					$this->writeCall("\nFALTA UN ARGUMENTO\n");
				}
			}
			elseif($args[0]=='chmod'){
				if($args[1]!='' && $args[2]!=''){
					$this->chmod_art($args[1],$args[2]);
				}
				else{
					$this->writeCall("\nFALTA UN ARGUMENTO\n");
				}
			}
			elseif($args[0]=='chgroup'){
				if($args[1]!='' && $args[2]!=''){
					$this->chgroup_art($args[1],$args[2]);
				}
				else{
					$this->writeCall("\nFALTA UN ARGUMENTO\n");
				}
			}
			elseif($args[0]=='clear'){
				$this->clear();
			}
			elseif($args[0]=='vdir'){
				$this->writeCall($this->getDir());
			}
			elseif($args[0]=='print' || $args[0]=='echo' || $args[0]=='write'){
				if($args[1]!=''){
					for($i=1;$i<(count($args));$i++){
						$tmp.=" ".$args[$i];
					}
					$this->writeCall($tmp);
				}
				else{
					$this->writeCall("\nFALTA UN ARGUMENTO\n");
				}
			}
			//read($var,$contenido){
			elseif($args[0]=='read'){
				$string.=$args[1];
				for($i=2;$i<(count($args));$i++){
					$string.=" ".$args[$i];
				}
				///^(([\"\'\$\.A-Za-z0-9\-\*\/\d+\{\}\[\]\(\)\s\?\¿])+)([;])$/ EJEM= "sdfsdf";
				///^(([\"\'\$\.A-Za-z0-9\-\*\/\d+\{\}\[\]\(\)\s\?\¿])+)(((([\,])([@A-Za-z0-9_-\s]+)+)+)+)+([;])$/ EJEM: read "cual es su nombre",@nombre,@apellido;
				if(preg_match("/^(([\"\'\$\.A-Za-z0-9\-\*\/\d+\{\}\[\]\(\)\s\?\¿])+)(((([\,])([@A-Za-z0-9_-\s]+)+)+)+)+([;])$/",$string,$coincde)){
					if($coincde[0]!=''){
						$cadenavalid=$coincde[0];
						$coincde[3]=str_replace(" ","",$coincde[3]);
						//$coincde[3]=str_replace(",","\n",$coincde[3]);
						$variables=$coincde[3];
						//str_replace ( mixed $search , mixed $replace , mixed $subject [, int &$count ] ) 
						$vars=explode(",",$variables);
						//validacion de las variables, ver si todas tienen el @}
						for($w=1;$w<=(count($vars)-1);$w++){
							if(preg_match("/^[@][A-Za-z0-9_-]+/",$vars[$w],$conc)){
								//escribir la programacion aqui
								//$this->writeCall($vars[$w]." CORRECTO\n");
							}
							else{
								$this->writeCall(" Error de sintaxis, variable: ".$vars[$w]."\n");
							}
						}
						
						
						if($w==(count($vars)-1)){
							
						}
					}
				}
				else{
					$this->writeCall("\nFALTA UN ARGUMENTO\n");
				}
			}			
			//VARIABLES DE ENTORNO
			elseif(preg_match("/^(@[a-z]*)(=|<|>|<=|>=|!=)(@[a-z]*|\w|[A-Za-z0-9-_\"\']{1}\w*[A-Za-z0-9-_\"\']{1})/",$args[0],$coincde)){
				if($coincde[0]!=''){
					print_r($coincde);
					$cadena=$coincde[3];
					$cadena=str_replace("\"","",$cadena);
					$variable=$coincde[1];
					$this->defineVar($variable,$cadena);
					$this->writeCall($this->seekVar_val($variable));
				}
				else{
					$this->writeCall("No hubo eoincidencias1");
				}
			}
			//OPERACIONES MATEMATICAS CON NUMEROS SIN VARIABLES
			//"/^[\$][\(]([0-9\.]{1,})+([\+\*\-\/])[\)][;]$/" 
			// acepta $(23.32*-/+)
			elseif(preg_match("/^[\$][\(]((([0-9\.]{1,})+([\+\-\/\*]))+([a-z@0-9\.]{1,})*)[\)][;]$/",$args[0],$coincde)){
				if($coincde[0]!=''){
					print_r($coincde);
					echo("Funcion no alfanumerica");
				}
				else{
					$this->writeCall("No hubo coincidencias3");
				}
			}
			elseif($args[0]=='sval'){
				if($args[1]!=''){
					if(preg_match("/^@[a-z]*/",$args[1],$coincde)){
						if($coincde[0]!=''){
							$variable=$coincde[0];
							$valorVal=$this->seekVar_val($variable);
							$cadena=$coincde[0]."=".$valorVal;
							$this->writeCall($cadena);
						}
					}		
				}
			}
			elseif($args[0]=='vali'){
				if($args[1]!=''){
					if(preg_match("/^@[a-z]*/",$args[1],$coincde)){
						if($coincde[0]!=''){
							$variable=$coincde[0];
							$valorVal=$this->seekVar_val($variable);
							$cadena=$valorVal;
							$this->writeCall($cadena);
						}
					}
					else{
						$this->writeCall("No coincide");
					}
				}
			}
			else{
				$this->writeCall("NO EXISTE ESE COMANDO ¬¬ \n\n");
			}
		}
	}
	//////////////////////Archivos de texto plano
	function seekVar($var){
		//Se almacena el archivo en un Array
		$file=file("variables.txt");
		//
		$fp=fopen("variables.txt","r");
		if($fp){
			if(preg_match("/@[A-Za-z0-9_-]*/",$var,$cciden)){
				if($cciden[0]!=''){
					$i=0;
					$contenido=file("variables.txt");
					$encontrado=0;
					for($i=0;$i<count($contenido);$i++){
						$partes=explode(",",$contenido[$i]);
						if($partes[0]==$var){
							$encontrado=1;
							return 0;
						}
					}
					
					if($encontrado==0){
						return -1;
					}
				}
				else{
					return -2;
				}
			}
			else{
				return -3;
			}
		}
		else{
			return -4;
		}
	}
	
	function seekVar_return($var){
		//Se almacena el archivo en un Array
		$file=file("variables.txt");
		//
		$fp=fopen("variables.txt","r");
		if($fp){
			if(preg_match("/@[A-Za-z0-9_-]*/",$var,$cciden)){
				if($cciden[0]!=''){
					$i=0;
					$contenido=file("variables.txt");
					$encontrado=0;
					for($i=0;$i<count($contenido);$i++){
						$partes=explode(",",$contenido[$i]);

						if($partes[0]==$var){
							$encontrado=1;
							return $i;
						}
					}
					
					if($encontrado==0){
						return -34;
					}
				}
				else{
					return -2;
				}
			}
			else{
				return -3;
			}
		}
		else{
			return -4;
		}
	}
	
	function seekVar_val($var){
		$fp=fopen("variables.txt","r");
		if($fp){
			if(preg_match("/@[A-Za-z0-9_-]*/",$var,$cciden)){
				if($cciden[0]!=''){
					$i=0;
					$contenido=file("variables.txt");
					$encontrado=0;
					for($i=0;$i<count($contenido);$i++){
						$partes=explode(",",$contenido[$i]);
						if($partes[0]==$var){
							$encontrado=1;
							return $partes[1];
						}
					}
					
					if($encontrado==0){
						return -35;
					}
				}
				else{
					return -2;
				}
			}
			else{
				return -3;
			}
		}
		else{
			return -4;
		}
	}
	
	function defineVar($var,$valor){		
		$fp=fopen("variables.txt","r");
		if($fp){
			$contenido=file("variables.txt");
		}
		if($this->seekVar($var)!=0){
			$fp=fopen("variables.txt","a");
			if($fp){
				$valor=str_replace("\"","",$valor);
				$valor=str_replace("'","",$valor);
				$cadena=$var.','.$valor."\n";
				fwrite($fp,$cadena);
				fclose($fp);
			}
			return 0;
		}
		elseif($this->seekVar($var)==0){
			$this->resetVar($var,$valor);
		}
	}
	
	function rewriteVar($arrayVar){
		$fp=fopen("variables.txt","w");
		if($fp){
			for($i=0;$i<=(count($arrayVar)-1);$i++){
				if($arrayVar[$i]!=''){
					$cadena=$arrayVar[$i]."\n";
					fwrite($fp,$cadena);
				}
			}
		}
		if(fclose($fp)){
			return 0;		
		}
		else{
			return -36;
		}
	}
	
	function resetVar($var,$valor){
		if($this->seekVar($var)==0){
			$file_arr=file("variables.txt");
			$id=$this->seekVar_return($var);
			$cadena=$var.','.$valor;
			$file_arr[$id]=$cadena;
			$this->rewriteVar($file_arr);
			return 0;
		}
		else{
			return -1;
		}
	}
	/////////////////////////////////////////////////////
	function goPrompt(){
		$prompt=$this->getPrompt();
		$this->printCall($prompt."> ");
	}
	
	function passParser($cadena){
		$coincrev=array();
		//expresiones regulares para cada una de las llamadas al sistema (FORMATO)
			if(!is_numeric($cadena)){
				$argscad=explode(" ",$cadena);
				/*
				 * EXPRESIONES REGULARES
				 * OPCODE ARG1 ARG2
				// $patron general= \w{3,15}\s\w{3,15}\s\w{3,15}
				 * $patron1= \w{3,15}\s\w{3,15}\s\w{3,15} 3 palabras
				 * * $patron2= \w{3,15}\s\w{3,15} 2 palabras
				 * * $patron3= \w{3,15} 1 palabra
				 * $patronif= \{if ([a-z0-9]+)\}([^\{]*)(?:\{else\})?([^\{]*)\{/if\}
				 */
				$patron1='\w{3,15}\s\w{3,15}\s\w{3,15}';// 3 palabras
				$patron2= '\w{3,15}\s\w{3,15}' ;//2 palabras
				$patron3= '\w{3,15}'; //1 palabra
				$patronif='\{if ([a-z0-9]+)\}([^\{]*)(?:\{else\})?([^\{]*)\{/if\}';
				if(count($argscad)>2){
					if($argscad[2]!=''){
						return $argscad;				
					}
					else{
						return -1;
					}
				}
				
				if(preg_match_all($patron1,$cadena,$coinc)==0){
					$coincrev[0]=$coinc[0][0];
					$coincrev[1]=$coinc[0][1];
					$coincrev[2]=$coinc[0][2];
				}
				else{
					if(preg_match_all($patron2,$cadena,$coinc)==0){
						$coincrev[0]=$coinc[0][0];
						$coincrev[1]=$coinc[0][1];
					}
					else{
						if(preg_match_all($patron3,$cadena,$coinc)==0){
							$coincrev[0]=$coinc[0][0];
						}
						else{
							echo("NO EXISTE ESE COMANDO");
							return -1;
						}
					}					
				}
				
				if($argscad!=''){
					return $argscad;				
				}
				else{
					return -1;
				}
			}
	}
	
	 function existsCall($numcal){
		//funcion que valida si existe esa llamada a la API
	}
	
	/////////////LLAMADAS AL SISTEMA///////////////////////////////////////////////////
	
	 function printCall($cadena){
		echo("\n\n".$cadena);
		return 0;
	}

	 function writeCall($cadena){
		echo("\n\n".$cadena);
		return 0;		
	}
	
	 function mkdir_art($direcotrio){
		if(!file_exists($direcotrio)){
			mkdir($direcotrio,0777);
		}
	}
	
	 function chgroup_art($direcotrio,$grupo){
		if(!file_exists($direcotrio)){
			chgrp($direcotrio,$grupo);
		}
	}

	function chmod_art($direcotrio,$permisos){
		if(!file_exists($direcotrio)){
			chmod($direcotrio,$permisos);
		}
	}
	
	 function mkfile($direcotrio){
		$op=fopen($direcotrio,"w+");
		if($op){
			fclose($op);
		}
		else{
			return -1;
		}
	}
	
	function dir($directorio){
		if($directorio!=''){
			$i=0;
			if(is_dir($directorio)){
			    if ($dh = opendir($directorio)) {
			        while (($file = readdir($dh)) !== false) {
			        	if(strlen($file)>2){
			        		$this->writeCall($directorio."/".$file."\n");
			        	}
			            $i++;
			        }
			        closedir($dh);
			        return 0;
			    }
				else{
					$this->writeCall("0.ERROR, NO SE PUDO LEER: ".$directorio."\n\n");
				}
			}
			else{
				$this->writeCall("1.ERROR, NO ES UN DIRECTORIO: ".$directorio."\n\n");
			}
		}
		else{
			$this->writeCall($this->getDir());
			$this->dir($this->getDir());
		}
	}
	
	function ls($directorio){
		if($directorio!=''){
			$i=0;
			if (is_dir($directorio)) {
			    if ($dh = opendir($directorio)) {
			        while (($file = readdir($dh)) !== false) {
			        	if(strlen($file)>2){
			        		$this->writeCall($directorio."/".$file."\n");
			        	}
			            $i++;
			        }
			        closedir($dh);
			        $this->writeCall("\n\n");
			        return 0;
			    }
				else{
					$this->writeCall("0l.ERROR, NO SE PUDO LEER: ".$directorio."\n\n");
				}
			}
			else{
				$this->writeCall("1l.ERROR, NO ES UN DIRECTORIO: ".$directorio."\n\n");
			}
		}
		else{
			$this->writeCall("2l.ERROR, NO ES UN DIRECTORIO: ".$directorio."\n\n");
		}
	}
	
	function clear(){
		$this->writeCall("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");
	}
	
	function cd($dir){
		$this->setDir($dir);
	}
	
	function cds($orden){
		$this->setDir($orden);
		$dirac=$this->getDir();
		$this->ls($orden);
	}
}
?>
