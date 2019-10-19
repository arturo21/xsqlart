<html>
	<head>
		<meta charset="UTF-8">
		<title>Prueba XSQLART.php</title>
	</head>
	<body>
		<?php
			// define("SERVIDOR_BD", "127.0.0.1");
			// define("NOMBRE_BD", "sistemapmod");
			// define("USUARIO_BD", "arturo");
			// define("CLAVE_BD", "Arat5uro");
			include("xsqlart.class.php");
			
			$db=xsqlart::getInstance();
			$db->run();
		?>
	</body>
</html>
