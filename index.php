<html>
	<head>
		<meta charset="UTF-8">
		<title>Prueba XSQLART.php</title>
	</head>
	<body>
		<?php
			define("SERVIDOR_BD", "127.0.0.1");
			define("NOMBRE_BD", "sistemapmod");
			define("USUARIO_BD", "arturo");
			define("CLAVE_BD", "Arat5uro");
			include("xsqlart.class.php");
			
			$db=xsqlart::getInstance();
			$db->run();
			
			$db->insert('perfiles',array(
			'campo1'=>'valor1',
			'campo2'=>'valor2',
			'campo3'=>'valor3',
			'campo4'=>'valor4',
			'campo5'=>'valor5',
			'campo6'=>'valor6',
			));
			
			$db->update('perfiles',array(
			'campo1'=>'valor1',
			'campo2'=>'valor2',
			'campo3'=>'valor3',
			'campo4'=>'valor4',
			'campo5'=>'valor5',
			'campo6'=>'valor6',
			),
			array(
				'perfil'=>'arturo'
			));
			$db->delete('perfiles',
			array(
				'perfil'=>'arturo'
			));
			
			$db->insert('perfiles',array(
			'campo1'=>'valor1',
			'campo2'=>'valor2',
			'campo3'=>'valor3',
			'campo4'=>'valor4',
			'campo5'=>'valor5',
			'campo6'=>'valor6',
			),
			array(
				'perfil'=>'arturo'
			));
			echo("<br><br>");
			$db->select('perfiles',array(
				'campo1',
				'campo2',
				'campo3',
				'campo4',
				'campo5',
				'campo6',
			),
			array(
				'perfil'=>'arturo'
			));
			echo("<br><br>");
			$db->select('perfiles',array(
				'campo1',
				'campo2',
				'campo3',
				'campo4',
				'campo5',
				'campo6',
			));
			$db->select('perfiles',"asdasdasdasd",
			array(
				'perfil'=>'arturo'
			));
			echo("<br><br>");
		?>
	</body>
</html>
