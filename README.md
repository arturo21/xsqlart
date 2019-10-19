# xSQLART
# Compatible con PHP 7, 5
## Librería PHP que te ayuda en el manejo de consultas a bases de datos MySQL.

## Reutilización de código con sólo COPIAR y PEGAR. 

## Sin tener que cambiar nombres de identificadores, variables, etc.

# Haz más con menos.

/*
	Copyright (c) 2019 Arturo Vásquez Soluciones de Sistemas 2716
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

# EJEMPLOS

## Instanciar la clase
```php
$dbn=xsqlart::getInstance();
```
## Obtener versión PHP
```php
$dbn->getPhpVersion();
```
## Conectar a base de datos MySQL
```php
	//server
	define("SERVIDOR_BD", "127.0.0.1");
	//db name
	define("NOMBRE_BD", "dbname");
	//user db
	define("USUARIO_BD", "user");
	//password db
	define("CLAVE_BD", "password");
	
	include("xsqlart.class.php");
	
	$db=xsqlart::getInstance();
	$db->run();
```

## Crear una consulta simple
```php
$qrysentence="SELECT * FROM tabla";
if($dbn->Execute($qrysentence)){
	if($dbn->getRows()>0){
		$data=$dbn->getData();
	}
	else{
		return "No hay datos registrados.";
	}
}
```

## Operaciones CRUD Directas
### SELECT -- args: table, fields, [WHERE] -> optional 
```php
	$db->select(
	'perfiles',
	array(
		'campo1',
		'campo2',
		'campo3',
		'campo4',
		'campo5',
		'campo6',
	));
```

## Operaciones con WHERE
```php
	$db->select(
	'perfiles',
	array(
		'campo1',
		'campo2',
		'campo3',
		'campo4',
		'campo5',
		'campo6',
	),
	array(
		'campo'=>'valor'
	));
```

## UPDATE -- args: table, fields='newvalue', [WHERE] -> optional
```php
	$db->update(
	'perfiles',
	array(
	'campo1'=>'valor1',
	'campo2'=>'valor2',
	'campo3'=>'valor3',
	'campo4'=>'valor4',
	'campo5'=>'valor5',
	'campo6'=>'valor6',
	),
	array(
		'campo'=>'valor'
	));
```

## DELETE -- args: table, WHERE
```php
	$db->delete('perfiles',
	array(
		'campo'=>'valor'
	));
```

## Crear una consulta y extraer un array de datos
```php
$qrysentence="SELECT * FROM tabla";
if($dbn->Execute($qrysentence)){
	if($dbn->getRows()>0){
		while($data=$dbn->getData()){
			$field=$data['fieldname'];
			//escribir codigo aqui
		}
	}
	else{
		return "No hay datos registrados.";
	}
}
```

## Generar cadena de caracteres pseudoaleatorios de una longitud determinada
```php
$cantchar="128";
$cadena=$dbn->genHash($cantchar);
```

## Generar HASH SHA512
```php
$cantchar="sdfsdfsdfsdfsdfsdf";
$dbn->hashcad($cantchar);
```

## Generar HASH según algoritmo indicado
```php
$cantchar="asdasdasdasdasdasdasdasdasd";
$dbn->hashcadalgo($cantchar,"SHA512");
```

## Exportar archivo SQL de base de datos
```php
$bd="prueba";
$dbn->ExportarSQL($bd);
```

## Exportar archivo CSV de base de datos por tabla
```php
$archivosalida="tablaprueba.csv";
$sql="SELECT * FROM tabla"
$dbn->ExportCSV($archivosalida,$sql);
```

## Enviar correos con formato HTML
```php
$formato='html';
$dbn->MailSend($sender,$subject,$message,$destino,$formato);
```

# Si deseas apoyarme, 
# Haz Click en el siguiente enlace: 
### --Paypal-- 
[![paypal-btn-image-pay](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HREGZSCAPDECU)

<br />
