<?php
/*
	Copyright (c) 2017 Arturo Vásquez Soluciones de Sistemas 2716
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
/* RELEASE NOTES
 * CHANGELOG
 * 1. FIXED FUNCION SetConex
 * 1. FIXED FUNCION SetConex (FIXED: validations DB selection)
 * 2. FIXED FUNCION Execute (FIXED: muestra si hay error en la consulta MySQL y escribe en un archivo de operaciones...)
 * 2. FIXED FUNCION Execute (FIXED: SOLO extrae filas si hay un SELECT en el QUERY)
 * 2. FIXED FUNCION Execute (FIXED: getRows)
 * 2. FIXED FUNCION Execute (FIXED: functions within Execute)
 * 2. FIXED FUNCION Execute (FIXED: MANEJA MAS DE UNA COSULTA anidada SIN INSTANCIAR UN NUEVO OBJETO:: SINGLETON)
 * 2. FIXED FUNCION Execute (FIXED: Reload no devuelve el socket, no hace falta asignarse a una variable)
 * 3. ADDED FUNCION WHERE Y LIKE CON EXPRESIONES REGULARES
 * 4. FIXED SINGLETON:: (Se comporta como tal pero no tiene el constructor...).
 * 5. FIXED ADDED soporte UTF8 para impresión o muesttra de datos
 * 6. FIXED ADDED Colectores de datos (Probados y funcionando :))
 * 7. FIXED ADDED Importacion de datos a MySQL mediante archivos CSV de excel
 * 8. FIXED ADDED Exportacion de BD MySQL Actual y una que sea especificada por el programador
 * 9. ADDED funciones de Fechas y conversion de fechas a Integer
 * 10. ADDED funcion de ExportCSV para exportar archivos a CSV
 * 11. ADDED funcion para ver el numero de campos de una tabla
 * 12. ADDED libreria seguridad.class.php (INCLUDED)
 * 13. DEPRECATED ConnectarBD_SC, ConectarBD
 * 14. FIXED Funciones para mostrar mensajes de error
 * 15. ADDED Funcion Historial LOG de errores
 * 16. ADDED Funciones varias, mejora en funcion Execute
 * 16. ADDED Funcion saveSettings, mejora en funcion Execute
 * 16. ADDED Funcion Reload, mejora en funcion Execute
 * 16. ADDED Funcion setConexSocket, mejora en funcion Execute
 * 16. ADDED Funcion encbase64, decbase64. Soporte para base64
 * 17. ADDED Cambio de sintaxis al invocar cada función, por modificacion de "return 0;" por "return;" para rapidez en codificacion
 * 18. FIXED ExportSQL Cambio de algoritmo
 * 19. DEPRECATED ExportSQL_dif
 * 20. ADDED Array de consultas y Array de filas 
 * 21. FIXED Funcion setConex
 * 23. FIXED CHANGED ImportCSV Cambio de algoritmo
 * 23. FIXED CHANGED Hacer referencia a funciones internas para reutilizar código
 * 24. INTEGRATED API para escritura de errores mediante archivo LOG (seguridad.class.php)
 * 25. ADDED HASH Support
 * 26. BUGFIX iteración infinita al realizar select dentro de un while - agregando array de numeros de filas y consultas realizadas
 * 27. ADDED Funcion saveSetConex (Guarda los datos y conecta. en euna sola línea)
 * 28 PHP 7 compatibility en proceso;
*/
error_reporting("E_ERROR");
define("XSQLART_OPERATIONS_FILE", "operations.log");
define("XSQLART_PHP_EXTENSION", ".php");
define("XSQLART_SQL_EXTENSION", ".sql");
define("XSQLART_PY_EXTENSION", ".py");
define("XSQLART_HTML_EXTENSION", ".html");
define("XSQLART_XML_EXTENSION", ".xml");
define("XSQLART_PERL_EXTENSION", ".pl");
define("XSQLART_ROOT_DIR", "/");
define("XSQLART_MYSQL_PORT", "3306");
class xsqlart{
	protected $numcons=0;
	////////ARRAY DE NUMEROS DE FILAS/////////
	//para no afectar el numero de filas de la consulta inmediatamente anterior//
	//ni afectar la consulta en si...//
	protected $queryarray=array();
	protected $querycont=0;
	protected $rowarray=array();
	protected $rowcont=0;
	////////////////////////////////////////////////////////////////////////////
	protected $limitesql;
	protected $wheresql1;
	protected $relasql;
	protected $wheresql2;
	protected $tabla;
	protected $campodata;
	protected $tablareport;
	protected $resultsql;
	protected $tipotab;
	protected $pedido;
	protected $resp;
	protected $condicional;
	//ID de conexion //////
	protected $conexion;
	//////////////////////
	protected $bd;
	protected $db;
	protected $puerto;
	protected $servidor;
	protected $usrbd;
	protected $contbd;
	protected $lastped;
	protected $currency;
	protected $lastcons;
	protected $lastfilas;
	protected $idinsert;
	protected $nomlogin;
	protected $clavelogin;
	protected $nivellogin;
	protected $mdclavlogin;
	protected $tablaulogin;
	protected $niveladmin;
	protected $nivelusuario;
	protected $collationbd;
	protected $charsetbd;
	//////////////////////////// EMAIL MESSAGES ////////////////////
	protected $nemailmessage;
	protected $emailmessage;
	protected $numarchivosmail;
	protected $anioString;
	protected $mesString;
	protected $diaString;
	protected $dateString;
	protected $phpversion;
	protected $archivosmail=array();
	protected $archivosmail_t=array();
	protected $archivosmail_s=array();
	protected $archivosmail_tf=array();
	/////////////////////////////////////////////////////////////////
	protected $charcampos=array();
	protected $campos=array();
	protected $valores=array();
	protected $valupda=array();
	protected $camupda=array();
	protected $links=array();
	protected $consults=array();
	protected $codifica;
	//colector de arrays de datos llamados por getAllData()
	protected $nomecolector=array();
	protected $numcolectors=0;
	protected $datanumcolector=array();
	/////////////////////////////////////////////////////////////////
	protected $fileauth;
	protected $prefijoclv;
	protected $constantes;
	protected $version;
	//Función para conectar a BD/////////////////////////////////////
	////////////////////////////////////////////////////////////////
	public function setPHPVersion($phpv){
		$this->phpversion=$phpv;
		return;
	}
	public function setDB($db){
		$this->db=$db;
		return;		
	}
	public function setServer($serveridor){
		$this->servidor=$serveridor;
		return;
	}
	public function setPort($puertobd){
		if(empty($puertobd)){
			$this->puerto=XSQLART_MYSQL_PORT;
		}
		else {
			$this->puerto=$puertobd;
		}
		return;
	}
	public function setUsuario($usuariobd){
		$this->usrbd=$usuariobd;
		return;
	}
	public function setClaveUsuario($claveusr){
		$this->contbd=$claveusr;
		return;
	}
	public function getPhpVersion(){
		$ver = (float)phpversion();
		echo("VERSION PHP ".$ver);
		if ($ver >= 7.0) {
		    return 7;
		}
		elseif ($ver >=5.0 && $ver < 7.0) {
		    return 5;
		}
		else{
		    return $ver;
		}
	}
	public function getTable(){
		return $this->tabla;
	}
	public function getDB(){
		return $this->db;
	}
	public function getServer(){
		return $this->servidor;
	}
	public function getPort(){
		return $this->puerto;
	}
	public function getUsuario(){
		return 	$this->usrbd;
	}
	public function getClaveUsuario(){
		return $this->contbd;
	}
	public function Reload(){
		$this->closeConn();
		$this->setConex();
	}
	public function saveSettings($usuario,$clave,$server,$bd){
		$puerto=XSQLART_MYSQL_PORT;
		if($usuario!=''){
			$this->setUsuario($usuario);
			if($clave!=''){
				$this->setClaveUsuario($clave);
				if($server!=''){
					$this->setServer($server);
					if($bd!=''){
						$this->setDB($bd);
						if($puerto!=''){
							$this->setPort($puerto);
						}
						else{
							return -5; 
						}
					}
					else{
						return -4; 
					}
				}
				else{
					return -3; 
				}
			}
			else{
				return -2; 
			}
		}
		else{
			return -1; 
		}
		return;
	}
	public function saveSetConex($usuario,$clave,$server,$bd){
		$puerto=XSQLART_MYSQL_PORT;
		if($usuario!=''){
			$this->setUsuario($usuario);
			if($clave!=''){
				$this->setClaveUsuario($clave);
				if($server!=''){
					$this->setServer($server);
					if($bd!=''){
						$this->setDB($bd);
						if($puerto!=''){
							$this->setPort($puerto);
							$this->setConex();
						}
						else{
							return -5; 
						}
					}
					else{
						return -4; 
					}
				}
				else{
					return -3; 
				}
			}
			else{
				return -2; 
			}
		}
		else{
			return -1; 
		}
		return;
	}
	public function setConexSocket($socket){
		$tmp=$socket;
		if(!$tmp){
			echo("ERROR, no pudo conectarse a la base de datos");
		}
		else{
			$this->conexion=$tmp;
		}
		return 0; 
	}
	public function setConex(){
		$this->closeConnID($this->getIDConn());
		if(!$this->getUsuario()){
			echo("Error: User not loaded");
		}
		else {
			$usuario=$this->getUsuario();
		}
		if(!$this->getClaveUsuario()){
			echo("Error: Password not loaded");
		}
		else {
			$clave=$this->getClaveUsuario();
		}
		if(!$this->getDB()){
			echo("Error: Database not loaded");
		}
		else {
			$bd=$this->getDB();
		}
		if(!$this->getServer()){
			echo("Error: Server not loaded");
		}
		else {
			$server=$this->getServer();
		}
		$this->setConexSocket(mysqli_connect($server,$usuario,$clave,$bd));
	}
	public function closeConn(){
		if($this->getIDConn()!=-1){
			if($this->getIDConn()!=''){
				$this->closeConnID($this->getIDConn());
			}
		}
	}
	public function closeConnID($ID){
		if($ID!=''){
			mysqli_close($ID);
		}
		else{
			return -1;
		}
	}
	public function getConexSocket(){
		return $this->conexion;
	}
	public function getSocket(){
		return $this->getConexSocket();
	}
	public function showConexSocket(){
		if($this->conexion!=''){
			$this->printCad($this->conexion);
		}
		else{
			return -1;
		}
	}
	public function setCodif($codificacion){
		$codificacion=strtolower($codificacion);

		if($codificacion=='utf8'){
			$this->codifica=$codificacion;		
		}
		else{
			$this->printCad(utf8_decode("Codificación no soportada"));
		}
	}
	public function getCodif(){
		if($this->codifica!=''){
			return $this->codifica;		
		}
		else{
			return -1;
		}
	}
	/****************************************************************************************************
	 * 																									*
	 * 																									*
	 * 											START DATA COLECTOR FUNCTIONS							*
	 * 																									*
	 * 																									*
	 * **************************************************************************************************/	
	public function seekDataColector($nombre){
		if($nombre!=''){
			$encontrado=-2;
			for($i=0;$i<=($this->numcolectors-1);$i++){
				if($this->nomecolector[$i]==$nombre){
					$encontrado=0;
					return $encontrado;
				}
			}
			
			if($encontrado==-2){
				return $encontrado;
			}
		}
		else{
			return -1;
		}
	}
	//set Working Colector
	public function setWColector($nombre){
		if($nombre!=''){
			if($this->seekDataColector($nombre)){
				$this->nombrewcol=$nombre;
				return;
			}
			else{
				return -2;
			}
		}
		else{
			return -1;
		}
	}
	//get Working Colector
	public function getWColector(){
		if($this->nombrewcol!=''){
			return $this->nombrewcol;
		}
		else{
			return -1;
		}		
	}
	//INTERNO de la funcion (no para el desarrollador)
	function seekIDColector($nombre){
		$encontrado=-2;
		for($i=0;$i<=($this->numcolectors-1);$i++){
			if($this->nomecolector[$i]==$nombre){
				$encontrado=$i;
				return $encontrado;
			}
		}
		
		if($encontrado==-2){
			return $encontrado;
		}
	}
	//////////////////////////////////
	public function setDataColector($nombre){
		$arrayDatos=array();
		/* setDataColector busca la ultima sentencia SQL la ejecuta 
		 * y almacena los datos pertinentes en un Array,
		 * luego los almacena en el colector de datos para su posterior operación*/
		if(!$this->seekDataColector($nombre)){
			//utilizando sin argumentos la funcion Execute obtiene la ultima consulta MySQL Hecha
			if($this->Execute()){
				if($this->getRows()>0){
					while($data=$this->getData()){
						$arrayDatos[]=$data;			
					}
					// y vacia los datos pertinentes en el ArrayDatos
					$this->nomecolector[$this->numcolectors]=$nombre;
					$this->datanumcolector[$this->numcolectors]=$arrayDatos;
					$this->numcolectors++;
					return;
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
			return -1;
		}
	}
	public function setDataColector_dif($nombre,$arrayDatos){
		if(!$this->seekDataColector($nombre)){
			$this->nomecolector[$this->numcolectors]=$nombre;
			$this->datanumcolector[$this->numcolectors]=$arrayDatos;
			$this->numcolectors++;
			return;
		}
		else{
			return -1;
		}
	}
	public function setVariable($nombre,$arrayDatos){
		if(!$this->seekDataColector($nombre)){
			$this->nomecolector[$this->numcolectors]=$nombre;
			$this->datanumcolector[$this->numcolectors]=$arrayDatos;
			$this->numcolectors++;
			return;
		}
		else{
			$id=$this->seekIDColector($nombreColector);
			if($id!=''){
				$this->datanumcolector[$id]=$arrayDatos;
			}
			return 1;
		}
	}
	//devuelve un array si existe el colector
	public function getDataColector($nombre){
		if($this->seekDataColector($nombre)){
			$id=$this->seekIDColector($nombre);
			return $this->datanumcolector[$id];
		}
		else{
			return -1;
		}
	}
	//mostrar informacion precisa alamacenada en los colectores
	public function showDataInColector($nombreColector,$campo,$campomos,$valorStr){
		if($this->seekDataColector($nombreColector)){
			$id=$this->seekIDColector($nombreColector);
			if($valorStr!=''){
				//mostrar informacion precisa alamacenada en los colectores
				//listado
				$this->printCad("<table border='1'>");
					$this->printCad("<thead>");
						$this->printCad("<th>".$campomos."</th>");
					$this->printCad("</thead>");
					
					$this->printCad("<tbody>");
						//trabaja con Arrays Tridimensionales
						foreach($this->datanumcolector[$id] as $llave => $valor){
							if($valorStr==$this->datanumcolector[$id][$llave][$campo]){
								$this->printCad("<tr>");
									$this->printCad("<td>".$this->datanumcolector[$id][$llave][$campomos]."</td>");
								$this->printCad("</tr>");
							}
						}
					$this->printCad("</tbody>");
				$this->printCad("</table>");			
			}
			else{
				//mostrar informacion precisa alamacenada en los colectores
				//listado
				$this->printCad("<table border='1'>");
					$this->printCad("<thead>");
						$this->printCad("<th>".$campo."</th>");
					$this->printCad("</thead>");
					
					$this->printCad("<tbody>");
						foreach($this->datanumcolector[$id] as $llave => $valor){
							$this->printCad("<tr>");
								$this->printCad("<td>".$this->datanumcolector[$id][$llave][$campo]."</td>");
							$this->printCad("</tr>");
						}
					$this->printCad("</tbody>");
				$this->printCad("</table>");
				
			}
		}
		else{
			return -1;
		}	
	}
	public function getDataInColector($nombreColector,$campo,$valorStr){
		//obtiene alguna informacion y la devuelve en forma de Array, no la imprime
		if($this->seekDataColector($nombreColector)){
			$id=$this->seekIDColector($nombreColector);
			$datos=array();
			if($valorStr!=''){
				foreach($this->datanumcolector[$id] as $llave => $valor){
					if($valorStr==$this->datanumcolector[$id][$llave][$campo]){
						$datos[]=$this->datanumcolector[$id][$llave][$campo];
					}
				}
			}
			else{
				foreach($this->datanumcolector[$id] as $llave => $valor){
					$datos[]=$this->datanumcolector[$id][$llave][$campo];
				}
			}
			return $datos;
		}
		else{
			return -1;
		}	
	}
	public function eraseDataColector($nombre){
		//elimina el colector y su informacion de el
		if($this->seekDataColector($nombre)){
			$id=$this->seekIDColector($nombre);
			$this->nomecolector[$id]=0;
			$this->datanumcolector[$id]=0;
			$this->datanumcolector=$this->orderVector($this->datanumcolector);
			$this->nomecolector=$this->orderVector($this->nomecolector);
			$this->numcolectors--;		
		}
	}
	//ordena un vector con el metodo shellSort
	public function orderVector($vector){
		$i=0;
		$j=0;
		$incrmnt=0;
		$temp=0;
		$size=count($vector);
  		$incrmnt=$size/2;
		while ($incrmnt>0){
			for ($i=$incrmnt;$i<$size;$i++){
				$j = $i;
				$temp=$vector[$i];
				while (($j>=$incrmnt) && ($vector[$j-$incrmnt]>$temp)){
					$vector[$j]=$vector[$j-$incrmnt];
					$j=$j-$incrmnt;
				}
				$vector[$j]=$temp;
    		}
    		$incrmnt/=2;
		}
		return $vector;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	/****************************************************************************************************
	 * 																									*
	 * 																									*
	 * 											END DATA COLECTOR FUNCTIONS								*
	 * 																									*
	 * 																									*
	 * **************************************************************************************************/
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//Establecer una moneda para mostrar cantidades
	public function setCurr($curr){
		if($curr!=''){
			$this->currency=$curr;
			return;
		}
		else{
			return -1;
		}
	}

	public function getCurr(){
		return $this->currency;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function condition($conditionu,$rel,$conditiond){
		//patron /^[A-Za-z0-9_]*(<|>|=!|<=|>=)[A-Za-z0-9_]*$/
		$patron="/^([A-Za-z0-9_ ]*)(>|<|>=|<=|!=|==|=|LIKE|NOT|EQUAL|<>)\'([A-Za-z0-9_ ]*)\'/";
		$sqlconditquery=$conditionu." ".$rel." ".$conditiond;
		if(preg_match($patron,$sqlconditquery)){
			$this->Execute($sqlconditquery);
			return;
		}
		else{
			return -1;
		}
	}
	public function setIDConn(){
		$this->conexion=$this->getSocket();
	}
	public function getIDConn(){
		return $this->getSocket();
	}
	public function setIDInsert($id){
		$this->idinsert=$id;
		return;
	}
	public function getIDInsert(){
		if($this->idinsert!=''){
			$idins=$this->idinsert;
			return $idins;
		}
		else{
			return -1;
		}
	}
	public function setLastQuery($queryBD){
		//establece el ultimo Query para trabajar con este
		$this->lastped=$queryBD;
		return;
	}
	public function getLastQuery(){
		//obtiene el ultimo Query para trabajar con este
		return $this->lastped;
	}
	public function setLastQueryStatement($query){
		//establece el ultimo Query para trabajar con este
		$this->lastcons=$query;
		return;
	}
	public function getLastQueryStatement(){
		//obtiene el ultimo Query para trabajar con este
		return $this->lastcons;
	}
	public function showCountRows($tabla){
		if($this->Execute("SELECT COUNT(*) FROM ".$tabla)){
			$this->printCad($this->getSQLResult());
		}
	}
	public function getCountRows($tabla){
		if($this->Execute("SELECT COUNT(*) FROM ".$tabla)){
			return $this->getSQLResult();
		}
	}
	public function showCountRowsCond($tabla,$condicion){
		if($this->Execute("SELECT COUNT(*) FROM ".$tabla." WHERE ".$condicion)){
			$this->printCad($this->getSQLResult());
		}
	}
	public function getCountRowsCond($tabla,$condicion){
		if($this->Execute("SELECT COUNT(*) FROM ".$tabla." WHERE ".$condicion)){
			return $this->getSQLResult();
		}
	}
	public function getAllDatabases(){
		if($this->Execute("SHOW DATABASES")){
			return $this->getSQLResult();
		}
	}
	public function getAllTables(){
		if($this->Execute("SHOW TABLES")){
			if($this->getRows()>0){
				while($datserv=$dbn->getData()){
					$modulos[]=array("tabla"=>$datserv[0]);
				}
				echo(json_encode($modulos));
			}
			else{
				return -1;
			}
		}
	}
	public function lookupDuplicates($table,$field){
		if($table!='' && $field!=''){
			if($this->Execute("SELECT ".$field.", COUNT(*) duplicates FROM ".$table." GROUP BY ".$field." HAVING duplicates > 1")){
				return $this->getRows();
			}
			else{
				$sql="SELECT ".$field.", COUNT(*) duplicates FROM ".$table." GROUP BY ".$field." HAVING duplicates > 1";
				return $sql;
			}
		}
		else{
			return -1;
		}
	}
	public function getSQLResult(){
		//para cuando hay resultado unico
		return json_encode($this->getLastQuery());
	}
	public function getResultLengths(){
		//para cuando hay resultado unico
		$result=$this->getLastQuery();
		if($result){
			return $result->lengths;
		}
		else {
			return -1;
		}
	}
	public function Execute($query){
		$this->appendOperMsg("Iniciando conexion a la Bd...");
		//Reinicia Conexion y ejecuta sentencia SQL
		$this->Reload();
		//Reinstanciar la clase y conexion a la BD
		$conn=$this->getIDConn();
		$this->appendOperMsg("Ok","DB","root");
		$this->appendOperMsg("Obteniendo ID de la conexion...","DB","root");
		if($conn){
			if($query!=''){
				$this->setLastQueryStatement($query);
			}
			else{
				$query=$this->getLastQueryStatement();
			}
			$this->queryarray[$this->querycont]=mysqli_query($conn,$query);
			$this->setLastQuery($this->queryarray[$this->querycont]);
			if($this->getLastQuery()!=''){
				if($this->getLastQueryStatement()!=''){
					$consarr=$this->getSeparar($this->getLastQueryStatement(),' ');
					$this->appendOperMsg("Consulta Realizada: ".$this->getLastQueryStatement(),"DB","root");
					$consfinal=trim($consarr[0]);
					if($consfinal=='INSERT'){
						$pedido=$this->getLastQuery();
						$this->setIDInsert($pedido->insert_id);
						$this->appendOperMsg("Se ha hecho un Insert. ID=".$this->getIDInsert(),"DB","root");
					}
					else{
						if($consfinal=='SELECT'){
							$this->appendOperMsg("Se ha hecho un SELECT. Filas obtenidas=".$this->getRows(),"DB","root");
						}
					}
					if($this->setError(mysqli_error())){
						$this->printCad($this->getError());
						$this->appendOperMsg("Error Consulta ".$this->getError(),"DB","root");
						return -10;
					}
					else{
						return;
					}
					return;
				}
				else{
					$consarr=$this->getSeparar($this->getLastQueryStatement(),' ');
					$this->appendOperMsg("Consulta Realizada: ".$this->getLastQueryStatement(),"DB","root");
					$consfinal=trim($consarr[0]);
					if($consfinal=='INSERT'){
						$pedido=$this->getLastQuery();
						$this->setIDInsert($pedido->insert_id);
						$this->appendOperMsg("Se ha hecho un Insert. ID=".$this->getIDInsert(),"DB","root");
					}
					else{
						if($consfinal=='SELECT'){
							$this->appendOperMsg("Se ha hecho un SELECT. Filas obtenidas=".$this->getRows(),"DB","root");
						}
					}
					if($this->setError(mysqli_error())){
						$this->appendOperMsg("Error Consulta ".$this->getError(),"DB","root");
						$this->printCad($this->getError());
						return -11;
					}
					else{
						return;
					}
					return;
				}
				if($this->setError(mysqli_error())){
					$this->printCad($this->getError());
					$this->appendOperMsg("Error Consulta ".$this->getError(),"DB","root");
					return -12;
				}
				else{
					return;
				}
				$this->numcons++;
				$this->querycont++;
				return;
			}
			else{
				if($this->setError(mysqli_error())){
					$this->appendOperMsg("Error Consulta ".$this->getError(),"DB","root");
					$this->printCad($this->getError());
					return -13;
				}
				else{
					return;
				}
			}
			if($this->setError(mysqli_error())){
				$this->appendOperMsg("Error Consulta ".$this->getError(),"DB","root");
				$this->printCad($this->getError());
				return -14;
			}
			else{
				return;
			}
		}
		else{
			if($this->setError(mysqli_error())){
				$this->appendOperMsg("Error Consulta ".$this->getError(),"DB","root");
				$this->printCad("ERROR DE CONEXION ".$this->getError());
				return -15;
			}
			else{
				return;
			}
		}
		if($this->setError(mysqli_error())){
			$this->appendOperMsg("Error Consulta ".$this->getError(),"DB","root");
			$this->printCad($this->getError());
			return -16;
		}
		else{
			return;
		}
	}
	public function getRows(){
		$result=$this->getLastQuery();
		if($result){
			$filas=$result->num_rows;
			$this->updateNumRows($filas);
			return $filas;
		}
		elseif(!$result){
			return $this->rowarray[$this->rowcont];
		}
		else{
			return -1;
		}
	}

	function updateNumRows($rwosn){
		if($rwosn!='' && $this->rowarray[$this->rowcont]!=''){
			if($this->rowarray[$this->rowcont]!=$rwosn){
				$this->rowarray[$this->rowcont]=$rwosn;
			}
			$this->rowcont++;
		}
		else{
			return -2;
		}
	}
	public function setError($error){
		if($error!=''){
			$this->nderror=$error;
			return;
		}
		else{
			return -1;
		}
	}
	public function setErrorZero(){
		$this->nderror='';
		return;
	}
	public function getError(){
		if($this->lastcons!=''){
			$error="<h3>".$this->nderror."</h3>";
		}
		else{
			$error="<h3>".$this->nderror."</h3>";
		}
		return $this->nderror;
	}
	public function formatH($cadena,$formato){
		switch($formato){
			case '1':
				return "<h1>".$cadena."</h1>";
				break;
			case '2':
				return "<h2>".$cadena."</h2>";
				break;
			case '3':
				return "<h3>".$cadena."</h3>";
				break;
			case '4':
				return "<h4>".$cadena."</h4>";
				break;
			case '5':
				return "<h5>".$cadena."</h5>";
				break;
			case '6':
				return "<h6>".$cadena."</h6>";
				break;
		}
		return;
	}
	public function showError(){
		if($this->lastcons!=''){
			$cadena="<h3>".$this->getError()."</h3>";
			$this->printCad($this->formatH($cadena,"3"));
		}
		else{
			$cadena="<h3>".$this->getError()."</h3>";
			$this->printCad($this->formatH($cadena,"3"));
		}
		return;
	}
	public function deUtf8($mensaje){
		if($mensaje!=''){
			$msjcod=utf8_decode($mensaje);
		}
		else{
			return -1;
		}
		return $msjcod;
	}
	public function enUtf8($mensaje){
		if($mensaje!=''){
			$msjcod=utf8_encode($mensaje);
		}
		else{
			return -1;
		}
		return $msjcod;
	}
	/******************************************************************************
	 * 																			  *
	 *                                   SENDING MAILS						      *
	 * 																			  *
	 * 																			  *
	 * ***************************************************************************/
	//Envia email sin formato + Attachments
	public function emailSend($sender,$subject,$message,$destino){
		//dirección del remitente
		$headers .= "From: ".$sender."\r\n";
		//dirección de respuesta, si queremos que sea distinta que la del remitente
		$headers .= "Reply-To: ".$sender."\r\n";
		$message=$this->deUtf8($message);
		if(mail($destino,$subject,$message,$headers)){
			$error="";
			$this->setErrorZero();
			return;
		} 
		else{
			$error="No envio el email";
			$this->setError($error);
			return -1;
		}
	}
	//Establece el mensaje del email
	public function setEmailMessage($message){
		$this->emailmessage=$message;
		return;
	}
	//Obtiene el mensaje del email
	public function getEmailMessage(){
		if($this->emailmessage!=''){
			return $this->emailmessage; 
		}
		else{
			return -1;
		}
	}
	//Show message on the screen
	public function showEmailMessage(){
		if($this->emailmessage!=''){
			$this->printCad(($this->emailmessage)); 
			return;
		}
		else{
			return -1;
		}
	}
	//add files array files email with attachments
	public function AddFilesToMail($file){
		$this->archivosmail[$this->numarchivosmail]=$_FILES[$file]['name'];
		$this->archivosmail_t[$this->numarchivosmail]=$_FILES[$file]['type'];
		$this->archivosmail_s[$this->numarchivosmail]=$_FILES[$file]['size'];
		$this->archivosmail_tf[$this->numarchivosmail]=$_FILES[$file]['tmp_file'];
		$this->numarchivosmail++;
	}
	public function uploadFilesToMail(){
		$message="";
		if($this->numarchivosmail>0){
		    // preparing attachments
		    for($x=0;$x<count($this->archivosmail);$x++){
		        $file = fopen($this->archivosmail_tf[$x],"rb");
		        $data = fread($this->archivosmail,filesize($this->archivosmail_tf[$x]));
		        fclose($file);
		        $data = chunk_split(base64_encode($data));
		        $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$this->archivosmail[$x]\"\n" . 
		        "Content-Disposition: attachment;\n" . " filename=\"$this->archivosmail[$x]\"\n" . 
		        "Content-Transfer-Encoding: base64\n\n".$data."\n\n";
		        $message .= "--{$mime_boundary}\n";
		    }
		}
		return $message;
	}
	public function MailSend($sender,$subject,$message,$destino){
		//SEND EMAIL HTML ISO ENCODING
		//Con codificacion UTF8
		//dirección del remitente
		//para el envío en formato HTML
		$eol="\r\n";
	    $semi_rand=md5(time());
	    $mime_boundary = "==Multipart_Boundary_a{$semi_rand}a";
		//headers 
		$headers.="From: ".$sender."\r\n";
	    $headers.="MIME-Version: 1.0".$eol;
	    $headers.="Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol;
	    $headers.="Content-Transfer-Encoding: 7bit".$eol;
	    $headers.="This is a MIME encoded message.".$eol;
		$headers.="Content-Type: text/plain; charset=\"iso-8859-1\"".$eol;
		$headers.="Reply-To: ".$sender.$eol;
		$headers.='X-Mailer: PHP/' . phpversion();
		$adjuntos=$this->uploadFilesToMail();
	    //message
		$message=$this->deUtf8($message).$eol;
		$message.=$adjuntos;
		if(mail($destino,$subject,$message,$headers)){
			$msg="Mail Enviado!!";
			$this->setErrorZero();
			$this->printCad($msg);
			return;
		} 
		else{
			$error="Ocurrió un error al enviar mail. Vuelva a intentarlo...";
			$this->setError($error);
			$this->showError($error);
			return -1;
		}
	}
	/******************************************************************************
	 * 
	 *                                   END SENDING MAILS
	 * 
	 * 
	 * ***************************************************************************/
		public function printCad($mensaje){
			echo($mensaje);
		}
	/******************************************************************************
	 * 
	 *                         PRINT STRING WITH UTF8 ENCODING
	 * 
	 * 
	 * ***************************************************************************/
		public function dePrinto($mensaje){
			if($this->codifica!=''){
				if($this->codifica=='utf8'){
					$msjcod=$this->deUtf8($mensaje);
					$this->printCad($msjcod);
					return;
				}
			}
			else{
				return -1;
			}
		}
		public function enPrinto($mensaje){
			if($this->codifica!=''){
				if($this->codifica=='utf8'){
					$msjcod=$this->enUtf8($mensaje);
					$this->printCad($msjcod);
					return;
				}
			}
			else{
				return -1;
			}
		}
		public function Printo($option,$string){
			if($option=='decode'){
				$this->printCad($this->deUtf8($string));
			}
			elseif($option=='encode'){
				$this->printCad($this->enUtf8($string));
			}
		}
		public function ShowAsNum($numbers,$simostrar){
			if($simostrar=1 || $simostrar="y" || $simostrar="si"){
				return $this->getCurr()." ".number_format($numbers, 2, ',', '.');
			}
			else{
				return number_format($numbers, 2, ',', '.');
			}
		}
		//GET DB DATA
		public function getData(){
			$result=$this->getLastQuery();
			if($result){
				if($this->getRows()>0){
					$data=$result->fetch_array(MYSQLI_BOTH);
					return $data;
				}
				else{
					return "NO DATA FOUND";
				}
			}
			else{
				return -1;
			}
		}
		public function getAllData(){
			$arrayresult=array();
			$pedido=$this->getLastQuery();
			if($pedido){
				$arrayresult=$pedido->fetch_all(MYSQLI_BOTH);
				return $arrayresult;		
			}
			else{
				return -1;
			}
		}
		public function getDataNumFields(){
			$result=$this->getLastQuery();
			if($result){
				$data=$result->field_count;
				return $data;
			}
			else {
				return -1;
			}
		}
		function arrayShow($var){
			print_r($var);
			return;
		}
		//DEVUELVE UN ARRAY CON LOS CAMPOS DE LA TABLA INDICADA
		public function getDataFields(){
			$conn=$this->getIDConn();
			if($conn){
				$sql='SHOW COLUMNS FROM table_name';
				$this->Execute($sql);
				$data=$res->getAllData();
				echo(json_encode($data));
			}
		}
		public function getStringToInt($date){
			$dato=intval($date);
			return $dato; 
		}
		public function showStringToInt($date){
			$result=$this->getStringToInt($date);
			$this->printCad($result);
			return;
		}
		public function getSeparar($cad,$separador){
			$data=explode($separador,$cad);
			return $data; 
		}
		public function getAnio($date){
			$data=$this->getSeparar($date, "-");
			$anio=$data[0];
			return $anio; 	
		}
		public function getMes($date){
			$data=$this->getSeparar($date, "-");
			$mes=$data[1];
			return $mes; 		
		}
		public function getDia($date){
			$data=$this->getSeparar($date, "-");
			$dia=$data[2];
			return $dia;		
		}
		public function getTime($date){
			$data=time();
			return $data;
		}
		public function getIntAnio($date){
			$data=$this->getAnio($date);
			$data=intval($data);
			return $data; 	
		}
		public function getIntMes($date){
			$data=$this->getMes($date);
			$data=intval($data);
			return $data; 		
		}
		public function getIntDia($date){
			$data=$this->getDia($date);
			$data=intval($data);
			return $data;
		}
		public function showAnio($date){
			$data=$this->getAnio($date);
			$this->printCad($data);
			return;
		}
		public function showMes($date){
			$data=$this->getMes($date);
			$this->printCad($data);
			return; 		
		}
		public function showDia($date){
			$data=$this->getDia($date);
			$this->printCad($data);
			return;		
		}
		public function getAllDataFields(){
			$datos=array();
			$limite=$this->limitesql;
			$conn=$this->conexion;
			if($conn){
				$tablai=$this->tabla;
				$numargs = func_num_args();
			    $argulist = func_get_args();
			    $query="SELECT ";
			    
			    if($argulist[0]!='*'){
				    for($i=0;$i<$numargs;$i++){
				    	if($i!=($numargs-1)){
				    		$query.=$argulist[$i].", ";
				    	}
				    	else{
				    		$query.=$argulist[$i];
				    	}
				    }
			    }
			    else{
			    	$query.="* ";
			    }
			    
			    if($this->wheresql1!='' && $this->relasql!='' && $this->wheresql2!=''){
			    	$query.=" FROM ".$tablai." WHERE ".$this->wheresql1.$this->relasql.$this->wheresql2;
			    }
			    else{
			    	$query.=" FROM ".$tablai;
			    }
		    
				if($limite!=''){
			    	$query.=" LIMIT ".$limite;
			    }
		    	
				if($this->Execute($query)){
					return;
				}
				else{
					reuturn -1;
				}
			}
		}
	/****************************************************************************************************
	 * 																									*
	 * 																									*
	 * 											IMPORTACION/EXPORTACION DE DATOS						*
	 * 																									*
	 * 																									*
	 * **************************************************************************************************/
		public function ImportSQL($usuario, $passwd, $db){
			$executa="mysql -u $usuario -p $passwd $db";  
			system($executa, $resultado);  
			if(!$resultado){
				echo("<H1>Error ejecutando comando: $executa</H1>\n");  
			} 
		}
		public function ImportCSV($archivo,$BD,$tabla){
			$conn=$this->conexion;
			$con=$conn;
			if($conn){
				// $databasehost="localhost";
				// $databaseusername="test";
					$databasenam=$BD;
					$bd_aux=$this->getBD();
					$databasetable=$tabla;
					$tabla_aux=$this->getTable();
					$databasepassword="";
					$fieldseparator=",";
					$lineseparator="\n";
					$csvfile=$archivo;
				/********************************/
				/* Would you like to add an ampty field at the beginning of these records?
				/* This is useful if you have a table with the first field being an auto_increment integer
				/* and the csv file does not have such as empty field before the records.
				/* Set 1 for yes and 0 for no. ATTENTION: don't set to 1 if you are not sure.
				/* This can dump data in the wrong fields if this extra field does not exist in the table
				/********************************/
				$addauto = 0;
				/********************************/
				
				/* Would you like to save the mysql queries in a file? If yes set $save to 1.
				/* Permission on the file should be set to 777. Either upload a sample file through ftp and
				/* change the permissions, or execute at the prompt: touch output.sql && chmod 777 output.sql
				/********************************/
				$save = 1;
				$outputfile = "output.sql";
				/********************************/
				if (!file_exists($csvfile)) {
			        echo "File not found. Make sure you specified the correct path.\n";
			        exit;
				}
				$file = fopen($csvfile,"r");
				if (!$file) {
			        echo("Error opening data file.\n");
			        exit;
				}
				$size=filesize($csvfile);
				if(!$size){
			        echo "File is empty.\n";
			        exit;
				}
				$csvcontent = fread($file,$size);
				fclose($file);
				//$con = @mysqli_connect($databasehost,$databaseusername,$databasepassword) or die(mysqli_error());
				@mysqli_select_db($databasename) or die(mysqli_error());
				$lines=0;
				$queries="";
				$linearray = array();
				foreach(split($lineseparator,$csvcontent) as $line){
				        $lines++;
				        $line = trim($line," \t");
				        $line = str_replace("\r","",$line);
				        /************************************
				        	This line escapes the special character. remove it if entries are already escaped in the csv file
				        ************************************/
				        $line = str_replace("'","\'",$line);
				        /*************************************/
				        $linearray = explode($fieldseparator,$line);
				        $linemysql = implode("','",$linearray);
				        if($addauto){
				        	$query="INSERT INTO $databasetable values('','$linemysql');";
				        }
				        else{
				        	$query="INSERT INTO $databasetable values('$linemysql');";
				        }
				        $queries.=$query."\n";			
				        @mysqli_query($query);
				}
				//@mysqli_close($con);
				if($save){
			        if(!is_writable($outputfile)){
		                echo("File is not writable, check permissions.\n");
			        }
			        else{
		                $file2 = fopen($outputfile,"w");
		                if(!$file2){
							echo("Error writing to the output file.\n");
		                }
		                else{
		                    fwrite($file2,$queries);
		                    fclose($file2);
		                }
			        }
				}
				echo("Found a total of $lines records in this csv file.\n");
			}
		}
		public function ExportCSV($archivo,$sql){
			$conn=$this->conexion;
			if($conn){
				if($this->Execute($sql)){
					$campos=$this->getDataNumFields();
					$csv_terminated = "\n";
					    $csv_separator = ";";
					    $csv_enclosed = '"';
					    $csv_escaped = "\\";
					    $sql_query = $sql;
					 
					    // Gets the data from the database
					    $fields_cnt = $this->getDataNumFields();
					    $schema_insert = '';
					 
					    for ($i = 0; $i < $fields_cnt; $i++){
					        $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
					            stripslashes(mysqli_field_name($this->lastped, $i))) . $csv_enclosed;
					        $schema_insert .= $l;
					        $schema_insert .= $csv_separator;
					    } // end for
					 
					    $out = trim(substr($schema_insert, 0, -1));
					    $out .= $csv_terminated;
					 
					    // Format the data
					    while ($row = $this->getData()){
					        $schema_insert = '';
					        for ($j = 0; $j < $fields_cnt; $j++){
					            if ($row[$j] == '0' || $row[$j] != ''){
					 
					                if ($csv_enclosed == ''){
					                    $schema_insert .= $row[$j];
					                } 
					                else{
					                    $schema_insert .= $csv_enclosed .
					                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
					                }
					            } 
					            else{
					                $schema_insert .= '';
					            }
					 
					            if ($j < $fields_cnt - 1){
					                $schema_insert .= $csv_separator;
					            }
					        } // end for
					 
					        $out .= $schema_insert;
					        $out .= $csv_terminated;
					    } // end while
				}
			}
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Content-Transfer-Encoding: binary");
			header("Content-type: application/force-download");
			header("Content-Disposition: attachment; filename=$filename");
			// Change the folder you want this uploaded to
		    $folder = date("j_M_Y_G_i_s");
		    $back_up_filename = $folder . '_REPORTE.csv';
		    
		    if(!file_exists($folder))
		    {
		        mkdir($folder);
		    }
		    file_put_contents($back_up_filename, $out);
		}
		public function ExportarSQL($bd){
			$link = $this->conexion;
			mysqli_select_db($bd,$link);
			//get all of the tables
			if($tables=='*'){
				$tables = array();
				$result = mysqli_query('SHOW TABLES');
				while($row = mysqli_fetch_row($result)){
					$tables[] = $row[0];
				}
			}
			else{
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			}
			//cycle through
			foreach($tables as $table){
				$result = mysqli_query('SELECT * FROM '.$table);
				$num_fields = mysqli_num_fields($result);
				$return.= 'DROP TABLE '.$table.';';
				$row2 = mysqli_fetch_row(mysqli_query('SHOW CREATE TABLE '.$table));
				$return.= "\n\n".$row2[1].";\n\n";
				for ($i = 0; $i < $num_fields; $i++){
					while($row = mysqli_fetch_row($result)){
					$return.= 'INSERT INTO '.$table.' VALUES(';
						for($j=0; $j<$num_fields; $j++){
							$row[$j] = addslashes($row[$j]);
							$row[$j] = ereg_replace("\n","\\n",$row[$j]);
							if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
							if ($j<($num_fields-1)) { $return.= ','; }
						}
						$return.= ");\n";
					}
				}
				$return.="\n\n\n";
			}
			//save file
			$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
			fwrite($handle,$return);
			fclose($handle);
		}
		public function ExportarSQL_dif($host,$user,$pass,$bd,$link){
			$link = mysqli_connect($host,$user,$pass);
			mysqli_select_db($bd,$link);
			//get all of the tables
			if($tables=='*'){
				$tables = array();
				$result = mysqli_query('SHOW TABLES');
				while($row = mysqli_fetch_row($result)){
					$tables[] = $row[0];
				}
			}
			else{
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			}
			//cycle through
			foreach($tables as $table){
				$result = mysqli_query('SELECT * FROM '.$table);
				$num_fields = mysqli_num_fields($result);
				$return.= 'DROP TABLE '.$table.';';
				$row2 = mysqli_fetch_row(mysqli_query('SHOW CREATE TABLE '.$table));
				$return.= "\n\n".$row2[1].";\n\n";
				for ($i = 0; $i < $num_fields; $i++){
					while($row = mysqli_fetch_row($result)){
					$return.= 'INSERT INTO '.$table.' VALUES(';
						for($j=0; $j<$num_fields; $j++){
							$row[$j] = addslashes($row[$j]);
							$row[$j] = ereg_replace("\n","\\n",$row[$j]);
							if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
							if ($j<($num_fields-1)) { $return.= ','; }
						}
						$return.= ");\n";
					}
				}
				$return.="\n\n\n";
			}
			//save file
			$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
			fwrite($handle,$return);
			fclose($handle);
		}
	/****************************************************************************************************
	 * 																									*
	 * 																									*
	 * 											END IMPORTACION DE DATOS								*
	 * 																									*
	 * 																									*
	 * **************************************************************************************************/	
	public function showDataTableReport(){
		$datares=array();
		$argulist=array();
		$subtits=array();
		$numsubtit=0;
		$numargu=0;
		$camposnt=array();
		$retfunc;
		
		$conn=$this->getIDConn();
		if($conn){
			$consulta=$this->lastcons;		
			$pedido=mysqli_query($consulta,$conn);
			if($pedido!=''){
				//obtener numero de argumentos
			    $numargs = func_num_args();
			    $argulist = func_get_args();
			    $this->printCad("<table>");
			    $this->printCad("<thead>");
			    for($i=0;$i<$numargs;$i++){
			    	$argu=$argulist[$i];
			    	
			    	//validar si el campo existe
			    	$tabla=$this->tablareport;
			    	if($tabla==''){
			    		return -2;
			    	}
			    	else{
				    	if($this->isCampo($tabla,$argulist[$i])!=0){
				    		$this->printCad("<th>".strtoupper(utf8_decode($argu))."</th>");
				    		$subtits[]=$argu;
							$this->numsubtit++;
				    	}
				    	else{
				    		$camposnt[]=$argu;
				    		$this->numargu++;
				    	}
			    	}
			    }

			    $this->printCad("</thead>");
			    if($this->numsubtit!=$this->numargu){
			    	return -3;
			    }

				$this->lastped=$pedido;
				$filas=mysqli_num_rows($pedido);
				if($filas>0){
					$this->printCad("<tbody>");
					while($datanop=mysqli_fetch_array($pedido)){
						$this->datares[]=$datanop;
					}
					
					for($i=0;$i<=$filas;$i++){
						$this->printCad("<tr>");
						$numcol=$this->numsubtit;
						for($u=0;$u<$numcol;$u++){
							$this->printCad("<td>");
							$thcamp=$camposnt[$u];
							$this->printCad(utf8_decode($this->datares[$i][$thcamp]));
							$this->printCad("</td>");
						}
						$this->printCad("<tr>");
					}			
					$this->printCad("<tr>");
					$this->printCad("<td>");
					$this->printCad("N. Registros: ".$filas);
					$this->printCad("</td>");
					$this->printCad("<tr>");
					$this->printCad("</tbody>");
					$this->printCad("</table>");
					return;
				}
			}
			else{
				$this->printCad(mysqli_error());
			}
		}
		else{
			return -1;
		}		
	}
	
	//Ejecuta dos sentencias SQL para seleccion e insercion de datos con validacion de filas
	public function TwoSQLSentences($q1,$q2,$cond){
		//ejecutar dos sentencias: una de seleccion y otra de insert o delete o update u otra select;
		//0:FILAS==0
		//1:FILAS>0
		$condi=intval($cond);
		switch ($condi) {
			case '0':
				if($this->Execute($q1)){
					if($this->getRows()==0){
						if($this->Execute($q2)){
							$this->printCad("Registro procesado exitosamente");
						}
						else {
							echo($this->getError());
						}
					}
					else{
						echo("No hay registros.");
					}
				}
				else{
					echo("Error en la consulta.");
					echo($this->getError());
				}
				break;
			case '1':
				if($this->Execute($q1)){
					if($this->getRows()>0){
						if($this->Execute($q2)){
							$this->printCad("Registro procesado exitosamente");
						}
						else {
							echo($this->getError());
						}
					}
					else{
						echo("No hay registros.");
					}
				}
				else{
					echo("Error en la consulta.");
					echo($this->getError());
				}
				break;
		}
	}
	
	public function getNumCampos(){
		return $this->numcampos;
	}

	public function getCharCampos(){
		return $this->charcampos;
	}
	
	public function setTable($tablabd){
		$this->tabla=$tablabd;
		return;
	}
	
	public function setTableReport($tablarep){
		$this->tablareport=$tablarep;
		return;
	}
	
	public function setSentence($sentence){
		$this->othercons=$sentence;
		return;
	}
	
	public function getSentence(){
		return $this->othercons;
	}
	
	//////////////////////////////////////////////////////
	public function setCampNomLogin($camponom){
		$this->nomlogin=$camponom;
		return;
	}
	
	public function setCampClaveLogin($clavenom){
		$this->clavelogin=$clavenom;
		return;
	}
	
	public function setCampNivelLogin($nivelnom){
		$this->nivellogin=$nivelnom;
		return;
	}
	
	public function setTablaLogin($tablogin){
		$this->tablaulogin=$tablogin;
		return;
	}
	
	public function setNivelAdmin($nivadm){
		$this->niveladmin=$nivadm;
		return;	
	}

	public function setNivelUsuario($nivusr){
		$this->nivelusuario=$nivusr;
		return;
	}
	
	//////////////////////////////////////////////////////////////////////
	public function getCampNomLogin(){
		return $this->nomlogin;
	}
	
	public function getCampClaveLogin(){
		return $this->clavelogin;
	}
	
	public function getCampNivelLogin(){
		return $this->nivellogin;
	}
	
	public function getTablaLogin(){
		return $this->tablaulogin;
	}
	
	public function getNivelAdmin(){
		return 	$this->niveladmin;
	}

	public function getNivelUsuario(){
		return $this->nivelusuario;
	}
	
	public function uploadFile($req){
		$file=$req["file"]["name"];
		$termfilearr=explode(".",$file);
		$namefinal=hash('sha1', $file);
		$strfinal=$namefinal.$termfilearr[1];
		if(!is_dir("uploads/")){
			mkdir("uploads/", 0777);
			if($file && move_uploaded_file($req["file"]["tmp_name"], "uploads/".$strfinal)){
				echo $file;
			}
		}
		else{
			if($file && move_uploaded_file($req["file"]["tmp_name"], "uploads/".$strfinal)){
				echo $file;
			}
		}
		return;
	}
	//////////////////////////////////////////////////////////////////////
	function LoginEncripted($usuariovl,$clavevl,$cifrado){
		//realizar la consulta de login
		$conn=$this->getIDConn();	
		//realizar la consulta de login
		$camponom=$this->nomlogin;
		$clavenom=$this->clavelogin;
		$nivelnom=$this->nivellogin;
		$tablogin=$this->tablaulogin;
		$nivadm=$this->niveladmin;
		$nivusr=$this->nivelusuario;
		$sel="SELECT * FROM ".$tablogin." WHERE ".$camponom."='".$usuariovl."' AND ".$clavenom."='".$this->hashcadalgo($cifrado,$clavevl)."'";
		$pedido=mysqli_query($sel,$conn);
		if($pedido){
			$filas=mysqli_num_rows($pedido);
			if($filas>0){
				$_SESSION['usuarioMD5']=$usuariovl;
			}
		}
	}
	
	function setCollate($collate){
		$this->collationbd=$collate;
	}

	function setChrSet($charset){
		$this->charsetbd=$charset;	
	}

	function getCollate(){
		return $this->collationbd;
	}

	function getChrSet(){
		return $this->charsetbd;	
	}
	
	function createDB($bd){
		//realizar la asignacion de conexion
		$conn=$this->getIDConn();
		$charset=$this->charsetbd;
		$collate=$this->collationbd;
				
		if($conn!=''){
			if($charset!=''){
				if($collate!=''){
					$q="CREATE DATABASE ".$bd." CHARACTER SET '".$charset."' COLLATE '".$collate."'";
					$pedido=mysqli_query($q,$conn);
					if($pedido){
						return;
					}
					else{
						return -1;
					}
				}
				else{
					$this->printCad("NO SE HA DECLARADO CODIFICACION DE LA TABLA");
				}
			}
			else{
				$this->printCad("NO SE HA DECLARADO CHARSET");
			}
		}
		else{
			$this->printCad("NO EXISTE CONEXION CON LA BD");
		}
	}
	function scanDir($dir){
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
		    $files[] = $filename;
		}
		
		sort($files);
		rsort($files);
		
		return $files;
	}

	function opScanDir($dir){
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
		    $files[] = $filename;
		}
		sort($files);
		print_r($files);
		rsort($files);
		print_r($files);
	}
	
	//TABLA CAMPOS VALUES Mejorada!!!!!!!!!!!!!!!!!!!!!!!!!...y Funciona!
	function InsertInto_str(){
		$conn=$this->getIDConn();
		$numargs = func_num_args();
	    $argulist = func_get_args();
	    $camposins=array();
	    $valoresins=array();
	    $camposreal=array();
	    $this->numvalins=0;
	    $this->numcamins=0;
	    if($conn){
	    	//ver si existe campo
	    	$tablai=$argulist[0];
	    	for($u=1;$u<$numargs;$u++){
    			$base=$this->bd;
				$retfunc=$this->isCampo($tablai,$argulist[$u]);
				///////////RETFUNC (lo que devuelve);
		    	if($retfunc==-1){
		    		$valoresins[]=$argulist[$u];
					$this->numvalins++;
		    	}
		    	
		    	if($retfunc==0){
		    		$camposins[]=$argulist[$u];
		    		$this->numcamins++;
		    	}
	    	}
	    	if($this->numvalins==$this->numcamins){
		    	$selins="INSERT INTO ".$tablai."(";
				for($r=0;$r<=$this->numcamins-1;$r++){
					if($r<($this->numcamins-1)){
						$selins.=$camposins[$r].",";
					}
					elseif($r==($this->numcamins)){
						$selins.=$camposins[$r].')';
					}
					else{
						$selins.=$camposins[$r].') VALUES(';
						break;
					}
				}
				
		    	for($r=0;$r<=$this->numvalins;$r++){
					if($r<($this->numvalins-1)){
						$selins.="'".$valoresins[$r]."',";
					}
					elseif($r==($this->numvalins)){
						$selins.=$valoresins[$r].')';
					}
					else{
						$selins.="'".$valoresins[$r]."')";
						break;
					}
				}
				return $selins;
	    	}
	    	else{
				$this->setError("La cantidad de campos y valores es desigual!");
	    	}
	    }
	    else{
			$this->nderror="ERROR -1: NO HAY CONEXION";
	    }
	}
	
	function isCampo($tabla,$campo){
		$fieldsa=array();
		$conn=$this->getIDConn();
		if($conn){
			if($tabla!='' && $this->bd!='' && $campo!=''){
				$base=$this->bd;
			}
			else{
				return -1;
			}
			
			$query="SELECT * FROM ".$tabla;
			
			if($query!=''){
				$pedaux=mysqli_query($conn,$query);
			}
			else{
				$pedaux=mysqli_query($conn,$this->lastcons);
			}
			
			if($pedaux){
				if($query!=''){
					$this->lastcons=$query;
					$this->rowarray[$this->rowcont]=mysqli_num_rows($pedaux);
					$numfields=mysqli_num_fields($pedaux);

					
					for($i=0;$i<$numfields;$i++){
						$fieldsa[]=mysqli_field_name($pedaux,$i);
					}
					for($i=0;$i<$numfields;$i++){
						if($campo==$fieldsa[$i]){
							$camporesult=$fieldsa[$i];
							break;
						}
					}
					
					if(	$camporesult!=''){
						return;
					}
					else{
						return -1;
					}
				}
				else{
					$this->lastcons=$query;
					$this->rowarray[$this->rowcont]=mysqli_num_rows($pedaux);
					$numfields=mysqli_num_fields($pedaux);

					
					for($i=0;$i<$numfields;$i++){
						$fieldsa[]=mysqli_field_name($pedaux,$i);
					}
					
					for($i=0;$i<$numfields;$i++){
						if($fieldsa[$i]==$campo){
							$camporesult=$fieldsa[$i];
							break;
						}
					}
					
					if(	$camporesult!=''){
						return;
					}
					else{
						return -1;
					}			
				}
			}
			else{
				$error="<h3>".mysqli_error()."</h3>";
				return $error;
			}
		}
		else{
			return -1;
		}
	}
    /**************************************************************************
	 * 																		  *
	 *                                   SEGURIDAD							  *
	 *																		  * 
	 * 																		  *
	 * ************************************************************************/
	public function defConst($nombre,$valor){
		define($nombre,$valor);
	}
	
	public function genClaveValid(){
		$patron="/[A-Z]{4}\d{4}[a-z]{4}[A-Z]{2}\D{4}/";
		$cadena='';
		for($i=1;$i<=4;$i++){
			//letras MAYUSCULAS
			$num=rand(65,90);
			$cadena=$cadena.chr($num);
		}
		
		for($i=1;$i<=4;$i++){
			//numeros
			$num=rand(48,57);
			$cadena=$cadena.chr($num);
		}
		
		for($i=1;$i<=4;$i++){
			//letras minusculas
			$num=rand(97,122);
			$cadena=$cadena.chr($num);
		}
		
		for($i=1;$i<=2;$i++){
			//letras MAYUSCULAS
			$num=rand(65,90);
			$cadena=$cadena.chr($num);
		}
		
		for($i=1;$i<=4;$i++){
			//Caracteres especales
			$num=rand(33,125);
			$cadena=$cadena.chr($num);
		}
		
		return $cadena;
	}
	
	public function setPreClave($nombre){
		$this->prefijoclv=$nombre;
	}

	public function getPreClave(){
		if($this->prefijoclv!=''){
			return $this->prefijoclv;
		}
		else{
			return -1;
		}
	}
	
	public function genHashREG(){
		$cadena=$this->getPreClave();
		if($cadena!=''){
			for($i=1;$i<=5;$i++){
				//letras MAYUSCULAS
				$num=rand(65,90);
				$cadena=$cadena.chr($num);
			}
			
			for($i=1;$i<=2;$i++){
				//numeros
				$num=rand(48,57);
				$cadena=$cadena.chr($num);
			}
			
			for($i=1;$i<=3;$i++){
				//Caracteres especiales
				$num=rand(33,125);
				$cadena=$cadena.chr($num);
			}
			
			$cadena=md5($cadena);
			
			return $cadena;
		}
		else{
			return -1;
		}
	}
	
	public function genHash($cantchar){
		$cadena='';
		for($i=1;$i<=$cantchar;$i++){
			//Caracteres especales
			$num=rand(33,125);
			$cadena.=chr($num);
		}
		return $cadena;
	}

	public function hashcadalgo($algo,$cadena){
		$res='';
		$res=hash($algo,$cadena);
		return $res;
	}

	public function hashcad($cadena){
		$resd='';
		$algor='sha512';
		$resd=$this->hashcadalgo($algor,$cadena);
		return $resd;
	}
	public function cnvIdentityHTML($cadena){ 
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);     
    }
	public function genID(){
		//Crea una clave de 128 caracteres
		$clavegen='';
		for($i=1;$i<=512;$i++){
			//Caracteres Especiales
			$num=rand(33,125);
			$clavegen.=chr($num);
		}
		return $clavegen;
	}
	public function encMD5($clave){
		return md5($clave);
	}
	public function encbase64($clave){
		return base64_encode($clave);
	}
	public function decbase64($clave){
		return base64_decode($clave);
	}
	public function redir($dir){
		if($dir!=''){
			header($dir);
		}
		else{
			return -1;
		}
	}
	public function validateFile($root){
		if(file_exists($root)){
			return;
		}
		else{
			return -1;
		}
	}
	///////////////WRITE OPERATIONS MESSAGES INTO A FILE
	public function writeOperMsg($mensaje){
		$archivo=fopen(XSQLART_OPERATIONS_FILE,'a');
		if($archivo){
			fwrite($archivo,$mensaje);
			fclose($archivo);	
			return;
		}
		else{
			return -1;
		}
	}
	public function appendOperMsg($mensaje,$tipocambio,$usuario){
		$header=date("d-m-Y H:i:s")."--- ";
		$mensajecomp=$header.$usuario."-->".$tipocambio."->".$mensaje."\r\n";
		$this->writeOperMsg($mensajecomp);
		return;
	}	
	//Funciones Generales para obtener datos del dominio-HOSTING///////////////////////////////////////////////////////
	public function getDomain(){
		return $_SERVER["SERVER_NAME"];
	}

	public function getDirScript(){
		return $_SERVER["PHP_SELF"];
	}

	public function getIPServer(){
		return $_SERVER["SERVER_ADDR"];
	}

	public function getRootScript(){
		return $this->getDomain().$this->getDirScript();
	}

	public function getPermalink(){
		return $this->getRootScript();
	}
	/////////////////////////////////////////////////////////////
}
?>
