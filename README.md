# <p align="center">🚀 xSQLART v3.0</p>
<p align="center">
  <b>High-Performance MySQL Utility & Security Toolkit for PHP</b>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3%20%2B-8892bf?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/Version-3.0.0-007acc?style=for-the-badge&logo=github" alt="Software Version">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Author-Arturo%20Vásquez-orange?style=flat-square&logo=visual-studio-code" alt="Author">
  <img src="https://img.shields.io/badge/Status-Maintained-success?style=flat-square" alt="Maintenance">
  <img src="https://img.shields.io/badge/Coverage-Secure-red?style=flat-square" alt="Security">
</p>

---

### 📖 Descripción
**xSQLART** es una librería premium diseñada para maximizar la productividad. Bajo la filosofía **"Haz más con menos"**, permite una integración inmediata mediante *copy-paste*, eliminando la fricción de configurar variables complejas en cada nuevo proyecto.

---

## 🛠️ Registro de Mejoras ✨ `v3.0 - 2026`

| Mejora | Descripción |
| :--- | :--- |
| 💎 **Core Modernizado** | Refactorización completa nativa para **PHP 8.3**. |
| 🛡️ **Zero Leaks** | Depuración de memoria en consultas masivas y eliminación de métodos *deprecated*. |
| 🚦 **Smart Logs** | Nuevo sistema de manejo de excepciones para debugging acelerado. |
| ⚡ **Singleton Pro** | Conectividad persistente optimizada para reducir la carga en el servidor. |

---

## 📊 Diccionario de Funciones
> *Clasificación por tipo de operación*

### 🧱 Core & Conectividad
*   `getInstance()` ➜ **[Core]** Único punto de acceso (Singleton).
*   `run()` ➜ **[DB]** Inicialización de conexión segura.

### 🔍 Consultas DB (Fluent CRUD)
*   🔵 `select(tabla, campos, where)` ➜ Lectura con mapeo automático.
*   🟡 `update(tabla, data, where)` ➜ Actualización limpia.
*   🔴 `delete(tabla, where)` ➜ Eliminación segura.
*   ⚫ `Execute(query)` ➜ Consultas personalizadas a bajo nivel.
*   📄 `ExportCSV(file, query)` ➜ Reportes inmediatos.

### 🔐 Cripto & Seguridad
*   🔑 `hashcad(string)` ➜ **SHA512** por defecto.
*   🎲 `genHash(int)` ➜ Generador de tokens pseudoaleatorios.
*   🔒 `hashcadalgo(str, algo)` ➜ Soporte multialgoritmo (MD5, SHA256).
*   📦 `ExportarSQL(dbname)` ➜ Backup total de base de datos.
*   📧 `MailSend(...)` ➜ Mailing con sanitización HTML.

---

## 🚀 Ejemplos de Implementación

### 🔌 1. Setup Maestro
```php
define("SERVIDOR_BD", "127.0.0.1");
define("NOMBRE_BD", "db_system");
define("USUARIO_BD", "admin");
define("CLAVE_BD", "secret_key");

include("xsqlart.class.php");

$db = xsqlart::getInstance();
$db->run();

// SELECT: Obtener nombre de usuario con ID 5
$db->select('usuarios', ['nombre'], ['id' => 5]);

// UPDATE: Cambiar tema a un usuario
$db->update('config', ['tema' => 'dark'], ['usuario' => 'arturo']);

// SECURITY: Generar un token de sesión
$sessionToken = $db->genHash(128);

```

---

## 📜 Licencia (MIT)
**Copyright (c) 2019-2026 Arturo Vásquez Soluciones de Sistemas / AVFDigital**

Se concede permiso, de forma gratuita, a cualquier persona que obtenga una copia de este software y de los archivos de documentación asociados (el "Software"), para utilizar el Software sin restricción, incluyendo, sin limitación, los derechos de uso, copia, modificación, fusión, publicación, distribución, sublicencia y/o venta de copias del Software, sujeto a las siguientes condiciones:

El aviso de copyright anterior y este aviso de permiso se incluirán en todas las copias o partes sustanciales del Software.

EL SOFTWARE SE PROPORCIONA "TAL CUAL", SIN GARANTÍA DE NINGÚN TIPO, EXPRESA O IMPLÍCITA, INCLUYENDO PERO NO LIMITADO A GARANTÍAS DE COMERCIALIZACIÓN, IDONEIDAD PARA UN PROPÓSITO PARTICULAR Y NO INFRACCIÓN. EN NINGÚN CASO LOS AUTORES O TITULARES DEL COPYRIGHT SERÁN RESPONSABLES DE NINGUNA RECLAMACIÓN, DAÑO U OTRA RESPONSABILIDAD, YA SEA EN UNA ACCIÓN DE CONTRATO, AGRAVIO O DE OTRO MODO, QUE SURJA DE, FUERA DE O EN CONEXIÓN CON EL SOFTWARE O EL USO U OTROS TRATOS EN EL SOFTWARE.

---

## 🤝 Soporte y Colaboración

Si esta herramienta te ha sido de utilidad para agilizar tus desarrollos, optimizar tus proyectos o simplemente te ha ahorrado valiosas horas de codificación, considera apoyar su mantenimiento y la creación de nuevas soluciones Open Source.

Tu colaboración permite que este proyecto siga actualizado y compatible con las últimas versiones de PHP.

<p align="left">
  <a href="https://www.paypal.com/paypalme/avsolucionesweb" target="_blank">
    <img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="Donar con PayPal" />
  </a>
</p>

---

<p align="center">
  <b>Desarrollado por Arturo Vásquez</b><br>
  <i>Expert Web Development & System Solutions</i>
</p>
