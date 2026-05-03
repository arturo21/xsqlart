<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba XSQLART.php - Versión 2026</title>
</head>
<body>
    <?php
        // Definición de constantes de conexión
        define("SERVIDOR_BD", "127.0.0.1");
        define("NOMBRE_BD", "sistemapmod");
        define("USUARIO_BD", "arturo");
        define("CLAVE_BD", "Arat5uro");

        require_once("xsqlart.class.php");
        
        // Obtener instancia de la clase
        $db = xsqlart::getInstance();
        
        // Configuración de parámetros
        $db->setServer(SERVIDOR_BD);
        $db->setDB(NOMBRE_BD);
        $db->setUsuario(USUARIO_BD);
        $db->setClaveUsuario(CLAVE_BD);
        
        // Establecer conexión
        if (!$db->setConex()) {
            die("Error al conectar con la base de datos.");
        }

        /* 
         * NOTA: En la versión optimizada, se recomienda usar Execute() con 
         * valores sanitizados para mantener el código ligero y eficiente.
         */

        // --- Ejemplo de INSERT ---
        $v1 = $db->sanitize('valor1');
        $v2 = $db->sanitize('valor2');
        $queryInsert = "INSERT INTO perfiles (campo1, campo2) VALUES ('$v1', '$v2')";
        if ($db->Execute($queryInsert)) {
            echo "Registro insertado correctamente.<br>";
        }

        // --- Ejemplo de UPDATE ---
        $nuevoValor = $db->sanitize('valor_editado');
        $perfil = $db->sanitize('arturo');
        $queryUpdate = "UPDATE perfiles SET campo1 = '$nuevoValor' WHERE perfil = '$perfil'";
        $db->Execute($queryUpdate);

        // --- Ejemplo de DELETE ---
        $queryDelete = "DELETE FROM perfiles WHERE perfil = '$perfil'";
        $db->Execute($queryDelete);

        echo "<br>--- Resultados de la Consulta ---<br>";

        // --- Ejemplo de SELECT ---
        $querySelect = "SELECT campo1, campo2, campo3 FROM perfiles";
        if ($db->Execute($querySelect)) {
            // Obtener todos los registros de una vez
            $resultados = $db->getAllData();
            
            foreach ($resultados as $row) {
                echo "Campo 1: " . $row['campo1'] . " | Campo 2: " . $row['campo2'] . "<br>";
            }
            
            echo "Total de filas: " . $db->getRows();
        }

        echo "<br><br>";
    ?>
</body>
</html>