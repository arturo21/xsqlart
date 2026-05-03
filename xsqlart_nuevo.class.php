<?php
/**
 * Copyright (C) 2026 Arturo Vasquez Soluciones Web.
 * Versión Optimizada para PHP 8.3
 */

declare(strict_types=1);

// Constantes Globales
define("XSQLART_OPERATIONS_FILE", "operations.log");
define("XSQLART_MYSQL_PORT", 3306);
// Constantes de extensión recuperadas y optimizadas
define("XSQLART_PHP_EXTENSION", ".php");
define("XSQLART_SQL_EXTENSION", ".sql");
define("XSQLART_PY_EXTENSION", ".py");
define("XSQLART_HTML_EXTENSION", ".html");
define("XSQLART_XML_EXTENSION", ".xml");
define("XSQLART_PERL_EXTENSION", ".pl");
define("XSQLART_JS_EXTENSION", ".js");
define("XSQLART_ROOT_DIR", __DIR__ . DIRECTORY_SEPARATOR);

class xsqlart {
    // Propiedades con Tipado Estricto
    protected int $numcons = 0;
    protected array $queryarray = [];
    protected int $querycont = 0;
    protected array $rowarray = [];
    protected int $rowcont = 0;
    
    protected ?string $tabla = null;
    protected ?string $tablareport = null;
    protected ?mysqli $conexion = null;
    
    protected ?string $db = null;
    protected int $puerto = XSQLART_MYSQL_PORT;
    protected ?string $servidor = null;
    protected ?string $usrbd = null;
    protected ?string $contbd = null;
    
    protected mixed $lastped = null;
    protected ?string $currency = null;
    protected ?string $lastcons = null;
    protected ?string $idinsert = null;
    
    // Configuración de Login
    protected ?string $nomlogin = null;
    protected ?string $clavelogin = null;
    protected ?string $nivellogin = null;
    protected ?string $tablaulogin = null;

    // Colectores
    protected array $nomecolector = [];
    protected int $numcolectors = 0;
    protected array $datanumcolector = [];
    protected ?string $nombrewcol = null;

    protected ?string $nderror = null;
    protected array $datares = [];
    protected ?string $prefijoclv = null;
    protected ?string $codifica = 'utf8mb4';

    private static ?xsqlart $_instance = null;

    // Singleton Pattern
    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): xsqlart {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // --- Getters & Setters Optimizados ---
    public function setDB(string $db): void { $this->db = $db; }
    public function setServer(string $server): void { $this->servidor = $server; }
    public function setPort(int $port): void { $this->puerto = $port; }
    public function setUsuario(string $user): void { $this->usrbd = $user; }
    public function setClaveUsuario(string $pass): void { $this->contbd = $pass; }

    public function getTable(): ?string { return $this->tabla; }
    public function getDB(): ?string { return $this->db; }
    public function getSocket(): ?mysqli { return $this->conexion; }

    // --- Gestión de Conexión ---
    public function Reload(): void {
        $this->closeConn();
        $this->setConex();
    }

    public function saveSettings(string $u, string $p, string $s, string $b): int {
        if (empty($u)) return -1;
        if (empty($p)) return -2;
        if (empty($s)) return -3;
        if (empty($b)) return -4;

        $this->setUsuario($u);
        $this->setClaveUsuario($p);
        $this->setServer($s);
        $this->setDB($b);
        return 0;
    }

    public function setConex(): void {
        if ($this->conexion) $this->closeConn();
        
        try {
            $this->conexion = new mysqli(
                $this->servidor ?? '',
                $this->usrbd ?? '',
                $this->contbd ?? '',
                $this->db ?? '',
                $this->puerto
            );

            if ($this->conexion->connect_error) {
                throw new Exception("Error de conexión: " . $this->conexion->connect_error);
            }

            $this->conexion->set_charset($this->codifica);
            $this->appendOperMsg("Conexión Exitosa", "DB", "system");
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function closeConn(): void {
        if ($this->conexion instanceof mysqli) {
            $this->conexion->close();
            $this->conexion = null;
        }
    }

    // --- Ejecución de Consultas (Refactorizado) ---
    public function Execute(?string $query = null): int {
        $sql = $query ?? $this->lastcons;
        if (empty($sql)) return -1;

        if (!$this->conexion) $this->setConex();
        
        $this->lastcons = $sql;
        $result = $this->conexion->query($sql);
        $this->lastped = $result;

        if ($result === false) {
            $this->setError($this->conexion->error);
            $this->appendOperMsg("Error SQL: " . $this->nderror, "QUERY", "root");
            return -10;
        }

        if (str_starts_with(strtoupper(trim($sql)), 'INSERT')) {
            $this->idinsert = (string)$this->conexion->insert_id;
        }

        $this->numcons++;
        return 1;
    }

    public function getRows(): int {
        return ($this->lastped instanceof mysqli_result) ? $this->lastped->num_rows : 0;
    }

    public function getData(): ?array {
        if ($this->lastped instanceof mysqli_result) {
            return $this->lastped->fetch_array(MYSQLI_BOTH);
        }
        return null;
    }

    // --- Operaciones CRUD Rápidas ---
    public function insert(string $table, array $fields): void {
        $keys = implode(',', array_keys($fields));
        $values = "'" . implode("','", array_map(fn($v) => $this->sanitize($v), array_values($fields))) . "'";
        $this->Execute("INSERT INTO $table ($keys) VALUES ($values)");
    }

    public function update(string $table, array $fields, string $where): void {
        $sets = [];
        foreach ($fields as $k => $v) $sets[] = "$k='" . $this->sanitize($v) . "'";
        $sql = "UPDATE $table SET " . implode(',', $sets) . " WHERE $where";
        $this->Execute($sql);
    }

    // --- Seguridad y Sanitización ---
    public function sanitize(mixed $data): string {
        $clean = strip_tags((string)$data);
        if ($this->conexion) {
            return $this->conexion->real_escape_string($clean);
        }
        return htmlspecialchars($clean, ENT_QUOTES, 'UTF-8');
    }

    public function hashcad(string $cadena): string {
        return hash('sha512', $cadena);
    }

    // --- Colectores de Datos ---
    public function setDataColector(string $nombre): int {
        if ($this->Execute() && $this->getRows() > 0) {
            $data = [];
            while ($row = $this->getData()) $data[] = $row;
            
            $this->nomecolector[$this->numcolectors] = $nombre;
            $this->datanumcolector[$this->numcolectors] = $data;
            $this->numcolectors++;
            return 0;
        }
        return -1;
    }

    // --- Utilidades ---
    public function appendOperMsg(string $msg, string $type, string $user): void {
        $log = sprintf("[%s] [%s] User: %s | %s%s", date("Y-m-d H:i:s"), $type, $user, $msg, PHP_EOL);
        file_put_contents(XSQLART_OPERATIONS_FILE, $log, FILE_APPEND);
    }

    public function setError(string $error): void {
        $this->nderror = $error;
    }

    public function getError(): ?string {
        return $this->nderror;
    }

    // --- Exportación Optimizada ---
    public function ExportCSV(string $filename, string $sql): void {
        if ($this->Execute($sql)) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);
            $output = fopen('php://output', 'w');
            
            $first = true;
            while ($row = $this->lastped->fetch_assoc()) {
                if ($first) {
                    fputcsv($output, array_keys($row));
                    $first = false;
                }
                fputcsv($output, $row);
            }
            fclose($output);
            exit;
        }
    }

    /**
     * Valida el acceso de un usuario y establece las variables de clase.
     */
    public function Login(string $user, string $pass, string $table, string $uField, string $pField, string $levelField): int {
        $this->nomlogin = $this->sanitize($user);
        $this->clavelogin = $this->hashcad($pass);
        $this->tablaulogin = $table;

        $sql = "SELECT * FROM $table WHERE $uField = '{$this->nomlogin}' AND $pField = '{$this->clavelogin}' LIMIT 1";
        
        if ($this->Execute($sql) && $this->getRows() > 0) {
            $data = $this->getData();
            $this->nivellogin = (string)$data[$levelField];
            return 1; // Éxito
        }
        
        return 0; // Credenciales incorrectas
    }

    public function getNivel(): ?string {
        return $this->nivellogin;
    }

    /**
     * Genera un reporte basado en el último resultado de consulta.
     */
    public function buildReport(string $cssClass = "table-report"): string {
        if (!($this->lastped instanceof mysqli_result)) {
            return "No hay datos para reportar.";
        }

        $html = "<table class='" . htmlspecialchars($cssClass) . "'><thead><tr>";
        $fields = $this->lastped->fetch_fields();

        foreach ($fields as $field) {
            $html .= "<th>" . htmlspecialchars($field->name) . "</th>";
        }
        
        $html .= "</tr></thead><tbody>";

        // Reiniciar puntero si es necesario
        $this->lastped->data_seek(0);

        while ($row = $this->lastped->fetch_assoc()) {
            $html .= "<tr>";
            foreach ($row as $value) {
                $html .= "<td>" . htmlspecialchars((string)$value) . "</td>";
            }
            $html .= "</tr>";
        }

        $html .= "</tbody></table>";
        return $html;
    }

    /**
     * Almacena una consulta en el array interno para ejecución masiva.
     */
    public function querySave(string $sql): void {
        $this->queryarray[$this->querycont] = $sql;
        $this->querycont++;
    }

    /**
     * Ejecuta todas las consultas guardadas.
     */
    public function executeAllSaved(): bool {
        if (empty($this->queryarray)) return false;

        $this->conexion?->begin_transaction();
        try {
            foreach ($this->queryarray as $sql) {
                if (!$this->Execute($sql)) {
                    throw new Exception("Error en batch: " . $this->getError());
                }
            }
            $this->conexion?->commit();
            $this->queryarray = []; // Limpiar cola
            $this->querycont = 0;
            return true;
        } catch (Exception $e) {
            $this->conexion?->rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * Devuelve un resumen del estado de la instancia (Debug).
     */
    public function debugStatus(): array {
        return [
            'database' => $this->db,
            'last_query' => $this->lastcons,
            'total_queries' => $this->numcons,
            'last_error' => $this->nderror,
            'connected' => ($this->conexion instanceof mysqli && $this->conexion->ping())
        ];
    }

/******************************************************************************
     *                                   SENDING MAILS (PHP 8.3 Optimized)
     *****************************************************************************/
    
    /**
     * Envía email con soporte para HTML y archivos adjuntos utilizando MIME.
     */
    public function MailSend(string $sender, string $subject, string $message, string $destino, string $formato = 'html'): bool {
        $eol = "\r\n";
        $semi_rand = md5((string)time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        $headers = "From: $sender" . $eol;
        $headers .= "Reply-To: $sender" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"$mime_boundary\"" . $eol;
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Cuerpo del mensaje
        $body = "--$mime_boundary" . $eol;
        $body .= "Content-Type: text/html; charset=\"UTF-8\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $body .= $message . $eol . $eol;

        // Adjuntos
        if ($this->numarchivosmail > 0) {
            foreach ($this->archivosmail as $index => $name) {
                if (file_exists($this->archivosmail_tf[$index])) {
                    $data = chunk_split(base64_encode(file_get_contents($this->archivosmail_tf[$index])));
                    $body .= "--$mime_boundary" . $eol;
                    $body .= "Content-Type: {$this->archivosmail_t[$index]}; name=\"$name\"" . $eol;
                    $body .= "Content-Disposition: attachment; filename=\"$name\"" . $eol;
                    $body .= "Content-Transfer-Encoding: base64" . $eol . $eol;
                    $body .= $data . $eol . $eol;
                }
            }
        }
        $body .= "--$mime_boundary--";

        try {
            if (mail($destino, $subject, $body, $headers)) {
                $this->setErrorZero();
                return true;
            }
            throw new Exception("Error en función mail().");
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /******************************************************************************
     *                         IMPORT/EXPORT & DB UTILS
     *****************************************************************************/

    /**
     * Exporta la base de datos a un archivo .sql (Reemplaza ereg_replace por preg_replace)
     */
    public function ExportarSQL(string $bd): void {
        $conn = $this->getSocket();
        mysqli_select_db($conn, $bd);
        $tables = [];
        $result = mysqli_query($conn, 'SHOW TABLES');
        
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        $return = "-- Dump xsqlart PHP 8.3\n\n";

        foreach ($tables as $table) {
            $result = mysqli_query($conn, "SELECT * FROM $table");
            $num_fields = mysqli_num_fields($result);

            $return .= "DROP TABLE IF EXISTS `$table`;";
            $row2 = mysqli_fetch_row(mysqli_query($conn, "SHOW CREATE TABLE `$table`"));
            $return .= "\n\n" . $row2[1] . ";\n\n";

            while ($row = mysqli_fetch_row($result)) {
                $return .= "INSERT INTO `$table` VALUES(";
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes((string)$row[$j]);
                    $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                    $return .= isset($row[$j]) ? '"' . $row[$j] . '"' : '""';
                    if ($j < ($num_fields - 1)) $return .= ',';
                }
                $return .= ");\n";
            }
            $return .= "\n\n";
        }

        $filename = 'db-backup-' . time() . '.sql';
        file_put_contents($filename, $return);
    }

    /**
     * Importación de CSV optimizada para PHP 8.3
     */
    public function ImportCSV(string $archivo, string $tabla): int {
        if (!file_exists($archivo)) return -1;
        
        $lines = 0;
        if (($handle = fopen($archivo, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $values = "'" . implode("','", array_map(fn($v) => mysqli_real_escape_string($this->getSocket(), $v), $data)) . "'";
                $this->Execute("INSERT INTO $tabla VALUES ($values)");
                $lines++;
            }
            fclose($handle);
        }
        return $lines;
    }

    /******************************************************************************
     *                         SEGURIDAD Y HASHING
     *****************************************************************************/

    /**
     * Genera un ID complejo de alta longitud.
     */
    public function genID(int $length = 512): string {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Genera clave validada por patrón.
     */
    public function genClaveValid(): string {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nums = '0123456789';
        $lowers = 'abcdefghijklmnopqrstuvwxyz';
        $specials = '!@#$%^&*()-_=+';

        return substr(str_shuffle($chars), 0, 4) . 
               substr(str_shuffle($nums), 0, 4) . 
               substr(str_shuffle($lowers), 0, 4) . 
               substr(str_shuffle($specials), 0, 4);
    }

    /******************************************************************************
     *                         FECHAS Y UTILIDADES
     *****************************************************************************/

    public function getAnio(string $date): int {
        return (int)date('Y', strtotime($date));
    }

    public function getMes(string $date): int {
        return (int)date('m', strtotime($date));
    }

    public function getDia(string $date): int {
        return (int)date('d', strtotime($date));
    }

    public function ShowAsNum(float|int $numbers, bool $mostrarSimbolo = true): string {
        $formatted = number_format($numbers, 2, ',', '.');
        return $mostrarSimbolo ? ($this->currency . " " . $formatted) : $formatted;
    }

    /**
     * Versión moderna de url_get_contents con cURL
     */
    public function url_get_contents(string $url): string|bool {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'xsqlart_agent_8.3');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

/**
     * @section DATA COLECTOR & MEMORY MANAGEMENT
     * Optimizaciones para PHP 8.3: Uso de match(), tipos estrictos y saneamiento de memoria.
     */

    public function seekDataColector(string $nombre): bool {
        return in_array($nombre, $this->nomecolector, true);
    }

    public function setWColector(string $nombre): int|void {
        if (empty($nombre)) return -1;
        if ($this->seekDataColector($nombre)) {
            $this->nombrewcol = $nombre;
            return;
        }
        return -2;
    }

    public function getWColector(): string|int {
        return $this->nombrewcol ?? -1;
    }

    protected function seekIDColector(string $nombre): int|null {
        $key = array_search($nombre, $this->nomecolector, true);
        return ($key !== false) ? $key : null;
    }

    public function getDataColector(string $nombre): array|int {
        $id = $this->seekIDColector($nombre);
        return ($id !== null) ? $this->datanumcolector[$id] : -1;
    }

    public function showDataInColector(string $nombreColector, string $campo, string $campomos, string $valorStr = ''): int|void {
        $id = $this->seekIDColector($nombreColector);
        if ($id === null) return -1;

        echo "<table border='1'><thead><tr><th>" . htmlspecialchars($campomos) . "</th></tr></thead><tbody>";
        foreach ($this->datanumcolector[$id] as $fila) {
            if ($valorStr === '' || $fila[$campo] == $valorStr) {
                echo "<tr><td>" . htmlspecialchars($fila[$campomos] ?? '') . "</td></tr>";
            }
        }
        echo "</tbody></table>";
    }

    public function getDataInColector(string $nombreColector, string $campo, string $valorStr = ''): array|int {
        $id = $this->seekIDColector($nombreColector);
        if ($id === null) return -1;

        return array_values(array_filter($this->datanumcolector[$id], function($fila) use ($campo, $valorStr) {
            return ($valorStr === '' || $fila[$campo] == $valorStr);
        }));
    }

    public function eraseDataColector(string $nombre): void {
        $id = $this->seekIDColector($nombre);
        if ($id !== null) {
            unset($this->nomecolector[$id], $this->datanumcolector[$id]);
            // Reindexar vectores para mantener integridad
            $this->nomecolector = array_values($this->nomecolector);
            $this->datanumcolector = array_values($this->datanumcolector);
            $this->numcolectors--;
        }
    }

    /**
     * @section SANITIZATION & HTML TOOLS
     * Reemplazo de lógica obsoleta por filtros nativos y seguridad contra XSS.
     */

    public function sanitize_identity(string $cadena): string {
        return htmlspecialchars($cadena, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function sanitize_tags(string $cadena): string {
        return strip_tags($cadena);
    }

    public function sanitize_slashes(string $cadena): string {
        return stripslashes($cadena);
    }

    public function sanitize_whitespace(string $cadena): string {
        return preg_replace('/\s+/', ' ', trim($cadena));
    }

    public function sanitize(string $cadena): string {
        return $this->sanitize_identity($this->sanitize_tags($this->sanitize_slashes($cadena)));
    }

    public function space2lines(string $cadena): string {
        return str_replace(" ", "-", $cadena);
    }

    public function formatH(string $cadena, int|string $formato): string {
        $nivel = (int)$formato;
        if ($nivel < 1 || $nivel > 6) $nivel = 3;
        return "<h{$nivel}>" . htmlspecialchars($cadena) . "</h{$nivel}>";
    }

    /**
     * @section DATABASE DIAGNOSTICS
     */

    public function showCountRows(string $tabla): void {
        $res = $this->getCountRows($tabla);
        echo $res !== -1 ? $res : "Error al contar filas.";
    }

    public function getCountRows(string $tabla): int {
        $sql = "SELECT COUNT(*) as total FROM " . mysqli_real_escape_string($this->getSocket(), $tabla);
        $this->Execute($sql);
        $data = $this->getData();
        return isset($data['total']) ? (int)$data['total'] : -1;
    }

    public function getCountRowsCond(string $tabla, string $condicion): int {
        $sql = "SELECT COUNT(*) as total FROM " . mysqli_real_escape_string($this->getSocket(), $tabla) . " WHERE " . $condicion;
        $this->Execute($sql);
        $data = $this->getData();
        return isset($data['total']) ? (int)$data['total'] : -1;
    }

    public function lookupDuplicates(string $table, string $field): int|string {
        $table = mysqli_real_escape_string($this->getSocket(), $table);
        $field = mysqli_real_escape_string($this->getSocket(), $field);
        $sql = "SELECT {$field}, COUNT(*) as duplicates FROM {$table} GROUP BY {$field} HAVING duplicates > 1";
        $this->Execute($sql);
        return $this->getRows();
    }

    public function isCampo(string $tabla, string $campo): bool {
        $tableEsc = mysqli_real_escape_string($this->getSocket(), $tabla);
        $res = mysqli_query($this->getSocket(), "SHOW COLUMNS FROM {$tableEsc} LIKE '" . mysqli_real_escape_string($this->getSocket(), $campo) . "'");
        return mysqli_num_rows($res) > 0;
    }

    public function createDB(string $bd): bool {
        $charset = $this->charsetbd ?: 'utf8mb4';
        $collate = $this->collationbd ?: 'utf8mb4_unicode_ci';
        $sql = "CREATE DATABASE IF NOT EXISTS `{$bd}` CHARACTER SET {$charset} COLLATE {$collate}";
        return (bool)mysqli_query($this->getSocket(), $sql);
    }

    /**
     * @section SECURITY & HASHING
     * Implementación de random_bytes para seguridad criptográfica real.
     */

    public function setPreClave(string $nombre): void {
        $this->prefijoclv = $nombre;
    }

    public function getPreClave(): string {
        return $this->prefijoclv ?? '';
    }

    public function genHashREG(): string {
        $pre = $this->getPreClave();
        // PHP 8.3: random_int y bin2hex para mayor seguridad que rand()
        $randomPart = bin2hex(random_bytes(8));
        return hash('sha256', $pre . $randomPart);
    }

    public function genHash(int $cantchar = 16): string {
        return substr(bin2hex(random_bytes($cantchar)), 0, $cantchar);
    }

    public function encbase64(string $clave): string {
        return base64_encode($clave);
    }

    public function decbase64(string $clave): string {
        return base64_decode($clave);
    }

    /**
     * @section FILE SYSTEM & REDIRECTION
     */

    public function uploadFile(array $fileRequest): string|bool {
        if (!isset($fileRequest["file"])) return false;
        
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = pathinfo($fileRequest["file"]["name"], PATHINFO_EXTENSION);
        $newName = bin2hex(random_bytes(10)) . "." . $ext;
        $target = $uploadDir . $newName;

        if (move_uploaded_file($fileRequest["file"]["tmp_name"], $target)) {
            return $newName;
        }
        return false;
    }

    public function scanDir(string $dir): array {
        if (!is_dir($dir)) return [];
        $files = array_diff(scandir($dir), ['.', '..']);
        rsort($files);
        return $files;
    }

    public function redir(string $dir): void {
        if (!headers_sent()) {
            header("Location: " . $dir);
            exit;
        }
    }

    public function validateFile(string $root): bool {
        return file_exists($root);
    }
    public function condition(string $conditionu, string $rel, string $conditiond): bool {
        // Patrón optimizado para capturar operadores lógicos y de comparación SQL
        $patron = "/^([A-Za-z0-9_ ]*)(>|<|>=|<=|!=|==|=|LIKE|NOT|EQUAL|<>)\'([A-Za-z0-9_ ]*)\'$/";
        $sqlconditquery = "{$conditionu} {$rel} '{$conditiond}'";

        if (preg_match($patron, $sqlconditquery)) {
            return $this->Execute($sqlconditquery);
        }
        
        $this->setError("Sintaxis de condición no válida.");
        return false;
    }
    public function condition(string $conditionu, string $rel, string $conditiond): bool {
        // Patrón optimizado para capturar operadores lógicos y de comparación SQL
        $patron = "/^([A-Za-z0-9_ ]*)(>|<|>=|<=|!=|==|=|LIKE|NOT|EQUAL|<>)\'([A-Za-z0-9_ ]*)\'$/";
        $sqlconditquery = "{$conditionu} {$rel} '{$conditiond}'";

        if (preg_match($patron, $sqlconditquery)) {
            return $this->Execute($sqlconditquery);
        }
        
        $this->setError("Sintaxis de condición no válida.");
        return false;
    }
    public function select(string $table, array|string $fields = '*', ?array $where = null): bool {
        $fieldsStr = is_array($fields) ? implode(", ", $fields) : $fields;
        $sql = "SELECT {$fieldsStr} FROM {$table}";

        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $field => $value) {
                $val = $this->sanitize((string)$value);
                $conditions[] = "{$field} = '{$val}'";
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        return $this->Execute($sql);
    }
    public function select(string $table, array|string $fields = '*', ?array $where = null): bool {
        $fieldsStr = is_array($fields) ? implode(", ", $fields) : $fields;
        $sql = "SELECT {$fieldsStr} FROM {$table}";

        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $field => $value) {
                $val = $this->sanitize((string)$value);
                $conditions[] = "{$field} = '{$val}'";
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        return $this->Execute($sql);
    }
    public function getDataNumFields(): int {
        $result = $this->getLastQuery();
        return ($result instanceof mysqli_result) ? $result->field_count : 0;
    }

    public function getResultLengths(): array|int {
        $result = $this->getLastQuery();
        if ($result instanceof mysqli_result) {
            return $result->lengths ?? [];
        }
        return -1;
    }
    public function getDataFields(string $table): string {
        if ($this->conexion) {
            $sql = "SHOW COLUMNS FROM {$table}";
            if ($this->Execute($sql)) {
                $data = $this->getAllData();
                return json_encode($data);
            }
        }
        return json_encode(["error" => "No se pudo obtener la estructura"]);
    }
/**
     * Muestra un número con formato de moneda.
     * Optimizado para PHP 8.3 con tipos de unión y lógica de activación flexible.
     * 
     * @param float|int|string $numbers El valor numérico a formatear.
     * @param bool|string|int $simostrar Acepta booleano, o valores como 'y', 'si', 1 para mostrar moneda.
     */
    public function ShowAsNum(mixed $numbers, mixed $simostrar = false): string 
    {
        // Normalización de la lógica de validación original
        $activarSímbolo = match(strtolower((string)$simostrar)) {
            '1', 'y', 'si', 'true' => true,
            default => is_bool($simostrar) ? $simostrar : false
        };

        $formateado = number_format((float)$numbers, 2, ',', '.');

        return $activarSímbolo 
            ? ($this->getCurr() ?: '$') . " " . $formateado 
            : $formateado;
    }

    /**
     * Convierte una cadena (posiblemente con formato de fecha o número) a entero.
     */
    public function getStringToInt(string|int|null $date): int 
    {
        if (empty($date)) return 0;
        
        // Si contiene guiones (fecha), extraemos solo los dígitos
        if (str_contains((string)$date, '-')) {
            return (int)str_replace('-', '', $date);
        }

        return (int)$date;
    }

    /**
     * Muestra el resultado de la conversión a entero directamente.
     */
    public function showStringToInt(string|int|null $date): void 
    {
        $this->printCad((string)$this->getStringToInt($date));
    }

    /**
     * Envoltorio optimizado para explode.
     * Ahora incluye validación de separador vacío para evitar errores de PHP.
     */
    public function getSeparar(string $cad, string $separador): array 
    {
        if ($separador === '') {
            return [$cad];
        }
        return explode($separador, $cad);
    }

    /**
     * Retorna el año de una fecha como entero.
     */
    public function getIntAnio(string $date): int 
    {
        $data = $this->getSeparar($date, "-");
        return isset($data[0]) ? (int)$data[0] : 0;
    }

    /**
     * Retorna el mes de una fecha como entero.
     */
    public function getIntMes(string $date): int 
    {
        $data = $this->getSeparar($date, "-");
        return isset($data[1]) ? (int)$data[1] : 0;
    }

    /**
     * Retorna el día de una fecha como entero.
     */
    public function getIntDia(string $date): int 
    {
        $data = $this->getSeparar($date, "-");
        return isset($data[2]) ? (int)$data[2] : 0;
    }

/**
     * 3. SISTEMA DE ARCHIVOS Y DIRECTORIOS (Optimizado para PHP 8.3)
     */

    /**
     * Lista archivos de un directorio con tipado estricto y filtrado de puntos.
     */
    public function scanDir(string $dir): array|int {
        if (!$this->validateFile($dir)) return -1;
        
        $files = array_diff(scandir($dir), array('.', '..'));
        sort($files);
        return array_values($files);
    }

    /**
     * Lista, ordena y muestra por pantalla el contenido de un directorio.
     */
    public function opScanDir(string $dir): void {
        $files = $this->scanDir($dir);
        if (is_array($files)) {
            echo "--- Ascendente ---\n";
            print_r($files);
            rsort($files);
            echo "--- Descendente ---\n";
            print_r($files);
        }
    }

    /**
     * Validación mejorada de existencia de archivos o directorios.
     */
    public function validateFile(string $root): bool {
        return file_exists($root);
    }

    /**
     * 4. SEGURIDAD Y LOGIN (Extendido y Optimizado)
     */

    /**
     * Genera un hash utilizando algoritmos dinámicos.
     * En PHP 8.3 se recomienda usar algoritmos nativos si es para passwords.
     */
    public function hashcadalgo(string $algo, string $cadena): string {
        try {
            return hash($algo, $cadena);
        } catch (\ValueError $e) {
            // Si el algoritmo no existe, cae a sha512 por defecto
            return hash('sha512', $cadena);
        }
    }

    /**
     * Login con soporte para verificación de hash moderna (password_verify)
     * o algoritmos dinámicos para legado.
     */
    public function LoginEncripted(string $usuariovl, string $clavevl, string $cifrado = 'sha512'): bool {
        $conn = $this->getIDConn();
        
        // Escapar datos para evitar inyección (aunque se recomienda Prepared Statements)
        $user = mysqli_real_escape_string($conn, $usuariovl);
        $passHash = $this->hashcadalgo($cifrado, $clavevl);
        
        $sel = "SELECT * FROM {$this->tablaulogin} WHERE {$this->nomlogin} = '$user' AND {$this->clavelogin} = '$passHash' LIMIT 1";
        $pedido = mysqli_query($conn, $sel);

        if ($pedido && mysqli_num_rows($pedido) > 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['usuario_auth'] = bin2hex(random_bytes(16)); // Token de sesión seguro
            $_SESSION['usuario_id'] = $usuariovl;
            return true;
        }
        return false;
    }

    /**
     * 5. PROPIEDADES DE FECHA (Manejo de estados de correo)
     */
    
    /**
     * Actualiza las propiedades de fecha internas basadas en un timestamp o fecha actual.
     */
    public function updateDateProperties(?string $date = null): void {
        $time = $date ? strtotime($date) : time();
        
        $this->anioString = date("Y", $time);
        $this->mesString  = date("m", $time);
        $this->diaString  = date("d", $time);
        $this->dateString = date("Y-m-d H:i:s", $time);
    }

    /**
     * Getters para las propiedades de fecha (asumiendo que las declaraste como protected/private)
     */
    public function getDateStrings(): array {
        return [
            'anio' => $this->anioString,
            'mes'  => $this->mesString,
            'dia'  => $this->diaString,
            'full' => $this->dateString
        ];
    }

    // Setters de Configuración de Login
    public function setCampNomLogin($val) { $this->nomlogin = $val; }
    public function setCampClaveLogin($val) { $this->clavelogin = $val; }
    public function setCampNivelLogin($val) { $this->nivellogin = $val; }
    public function setTablaLogin($val) { $this->tablaulogin = $val; }
    public function setNivelAdmin($val) { $this->niveladmin = $val; }
    public function setNivelUsuario($val) { $this->nivelusuario = $val; }

    // Login con cifrado variable
    public function LoginEncripted($usuariovl, $clavevl, $cifrado) {
        $conn = $this->getSocket();
        $clave_hash = hash($cifrado, $clavevl);
        $query = "SELECT * FROM {$this->tablaulogin} WHERE {$this->nomlogin} = ? AND {$this->clavelogin} = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $usuariovl, $clave_hash);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['usuarioMD5'] = md5($usuariovl);
            return true;
        }
        return false;
    }
    // Setters de Configuración de Login
    public function setCampNomLogin($val) { $this->nomlogin = $val; }
    public function setCampClaveLogin($val) { $this->clavelogin = $val; }
    public function setCampNivelLogin($val) { $this->nivellogin = $val; }
    public function setTablaLogin($val) { $this->tablaulogin = $val; }
    public function setNivelAdmin($val) { $this->niveladmin = $val; }
    public function setNivelUsuario($val) { $this->nivelusuario = $val; }

    // Login con cifrado variable
    public function LoginEncripted($usuariovl, $clavevl, $cifrado) {
        $conn = $this->getSocket();
        $clave_hash = hash($cifrado, $clavevl);
        $query = "SELECT * FROM {$this->tablaulogin} WHERE {$this->nomlogin} = ? AND {$this->clavelogin} = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $usuariovl, $clave_hash);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['usuarioMD5'] = md5($usuariovl);
            return true;
        }
        return false;
    }
    public function uploadFile($req) {
        if (!isset($req["file"])) return -1;
        $file = $req["file"]["name"];
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $namefinal = hash('sha1', $file . time()) . "." . $ext;
        
        if (!is_dir("uploads/")) {
            mkdir("uploads/", 0777, true);
        }
        
        return move_uploaded_file($req["file"]["tmp_name"], "uploads/" . $namefinal) ? "uploads/" . $namefinal : false;
    }

    public function scanDir($dir) {
        if (!is_dir($dir)) return -1;
        $files = array_diff(scandir($dir), array('.', '..'));
        sort($files);
        return $files;
    }

    public function opScanDir($dir) {
        print_r($this->scanDir($dir));
    }

    public function getIPServer() { return $_SERVER["SERVER_ADDR"] ?? '127.0.0.1'; }
    public function getDirScript() { return $_SERVER["PHP_SELF"]; }
    public function getPermalink() { return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; }
    public function getStringToInt($date) { return (int)strtotime($date); }
    public function showStringToInt($date) { echo $this->getStringToInt($date); }

    public function getIntAnio($date) { return (int)date("Y", strtotime($date)); }
    public function getIntMes($date) { return (int)date("m", strtotime($date)); }
    public function getIntDia($date) { return (int)date("d", strtotime($date)); }

    public function showAnio($date) { echo date("Y", strtotime($date)); }
    public function showMes($date) { echo date("m", strtotime($date)); }
    public function showDia($date) { echo date("d", strtotime($date)); }

    public function defConst($nombre, $valor) { if (!defined($nombre)) define($nombre, $valor); }
    public function redir($dir) { header("Location: " . $dir); exit; }
    public function validateFile($root) { return file_exists($root); }

    /**
     * Configura las columnas para el proceso de login.
     */
    public function setLoginParams(string $tabla, string $campoNom, string $campoClave, string $campoNivel): void {
        $this->tablaulogin = $tabla;
        $this->nomlogin = $campoNom;
        $this->clavelogin = $campoClave;
        $this->nivellogin = $campoNivel;
    }

    /**
     * Versión mejorada de LoginEncripted para PHP 8.3
     */
    public function login(string $usuario, string $clave, string $algo = 'sha512'): bool {
        $conn = $this->getIDConn();
        $claveHash = hash($algo, $clave);
        
        $sql = sprintf(
            "SELECT * FROM %s WHERE %s = ? AND %s = ?",
            $this->tablaulogin,
            $this->nomlogin,
            $this->clavelogin
        );

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario, $claveHash);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['usuarioMD5'] = bin2hex(random_bytes(16)); // Token de sesión seguro
            $_SESSION['user_data'] = $result->fetch_assoc();
            return true;
        }
        return false;
    }

    public function getDataFields(string $table): string {
        $fields = [];
        $res = $this->conexion->query("SHOW COLUMNS FROM $table");
        while ($row = $res->fetch_assoc()) {
            $fields[] = $row;
        }
        return json_encode($fields);
    }

    public function getDataNumFields(): int {
        return $this->lastped?->field_count ?? 0;
    }

    public function getResultLengths(): array|false {
        return $this->lastped?->lengths ?? false;
    }
    public function uploadFile(array $fileRequest, string $folder = "uploads/"): string|bool {
        if (!is_dir($folder)) mkdir($folder, 0777, true);

        $filename = $fileRequest["name"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newName = hash('sha1', $filename . time()) . "." . $ext;
        $target = $folder . $newName;

        if (move_uploaded_file($fileRequest["tmp_name"], $target)) {
            return $newName;
        }
        return false;
    }

    public function validateFile(string $path): bool {
        return file_exists($path) && is_file($path);
    }

    public function scanDirSorted(string $dir, int $order = SCANDIR_SORT_DESCENDING): array {
        return is_dir($dir) ? scandir($dir, $order) : [];
    }
    public function uploadFile(array $fileRequest, string $folder = "uploads/"): string|bool {
        if (!is_dir($folder)) mkdir($folder, 0777, true);

        $filename = $fileRequest["name"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newName = hash('sha1', $filename . time()) . "." . $ext;
        $target = $folder . $newName;

        if (move_uploaded_file($fileRequest["tmp_name"], $target)) {
            return $newName;
        }
        return false;
    }

    public function validateFile(string $path): bool {
        return file_exists($path) && is_file($path);
    }

    public function scanDirSorted(string $dir, int $order = SCANDIR_SORT_DESCENDING): array {
        return is_dir($dir) ? scandir($dir, $order) : [];
    }

    public function printo(string $option, string $string): void {
        echo match($option) {
            'decode' => mb_convert_encoding($string, "ISO-8859-1", "UTF-8"),
            'encode' => mb_convert_encoding($string, "UTF-8", "ISO-8859-1"),
            default  => $string
        };
    }

    /**
     * Optimización de ordenamiento usando funciones nativas más rápidas que ShellSort manual
     */
    public function orderVector(array $vector): array {
        asort($vector);
        return $vector;
    }

    /**
     * Atajos de fecha mejorados
     */
    public function showDatePart(string $date, string $part): void {
        $timestamp = strtotime($date);
        echo match($part) {
            'anio' => date('Y', $timestamp),
            'mes'  => date('m', $timestamp),
            'dia'  => date('d', $timestamp),
            default => date('Y-m-d', $timestamp)
        };
    }
}