# xsqlart

/*
	Copyright (c) 2017 Arturo Vásquez Soluciones de Sistemas 2716
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

#Conectar a base de datos MySQL
`<addr>`
$dbn=new xsqlart();
$dbn->setCodif('utf8');
$dbn->saveSetConex(USUARIO_BD,CLAVE_BD,SERVIDOR_BD,NOMBRE_BD);
`</addr>`

#Crear una consulta simple
if($dbn->Execute($qrysentence)){
	if($dbn->getRows()>0){
		$data=$dbn->getData();
	}
	else{
		return "No hay datos registrados.";
	}
}

#Crear una consulta y extraer un array de datos
if($dbn->Execute($qrysentence)){
	if($dbn->getRows()>0){
		while($data=$dbn->getData()){
			//escribir codigo aqui
		}
	}
	else{
		return "No hay datos registrados.";
	}
}