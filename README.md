## 📜 Licencia (MIT)
Copyright (c) 2019-2026 **Arturo Vásquez Soluciones de Sistemas / AVFDigital**

Se concede permiso, de forma gratuita, a cualquier persona que obtenga una copia de este software para utilizarlo, modificarlo y distribuirlo sin restricciones, siempre que se incluya el aviso de copyright original.

---

## 🤝 Soporte y Colaboración
Si esta herramienta te ayuda a facturar más rápido o a mantener tus proyectos limpios, considera apoyar su desarrollo:

[![Paypal Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/paypalme/avsolucionesweb)

---
<p align="center">
  <b>Desarrollado porAquí tienes el contenido completo del **README.md** en un solo bloque de código, optimizado para GitHub, con un diseño moderno y las nuevas secciones técnicas que solicitaste:
```markdown
# 🚀 xSQLART v3.0 | PHP Database Wrapper
### High-Performance MySQL Utility & Security Toolkit

![PHP Version](https://img.shields.io/badge/PHP-5.6%20|%207.x%20|%208.3-8892bf?style=for-the-badge&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)
![Maintained](https://img.shields.io/badge/Maintained%3F-Yes-007acc?style=for-the-badge)

**xSQLART** es una librería PHP diseñada para maximizar la productividad en el manejo de bases de datos MySQL. Bajo la filosofía de **"Haz más con menos"**, esta herramienta permite la reutilización de código mediante copiar y pegar, eliminando la necesidad de reescribir identificadores o variables complejas en cada proyecto.

---

## 🛠️ Registro de Mejoras (v3.0 - 2026)

Esta actualización representa un salto significativo en la estabilidad y modernización de la librería:

*   **Compatibilidad PHP 8.3:** Refactorización del núcleo para soportar las nuevas funcionalidades y restricciones de tipos de PHP 8.3.
*   **Depuración de Código:** Se eliminaron métodos obsoletos y se corrigieron fugas de memoria en consultas de gran volumen.
*   **Manejo de Excepciones:** Mejora en el sistema de errores para ofrecer logs más claros durante la etapa de desarrollo.
*   **Optimización de Conectividad:** Mejora en la persistencia de la conexión mediante el patrón Singleton, reduciendo el consumo de recursos del servidor.

---

## 📊 Diccionario de Funciones

La librería se divide en tres pilares fundamentales para el desarrollo backend:

| Función | Tipo | Descripción |
| :--- | :--- | :--- |
| `getInstance()` | **Core** | Implementa el patrón Singleton para una instancia única. |
| `run()` | **Consultas DB** | Inicializa la conexión segura con los parámetros definidos. |
| `select()` | **Consultas DB** | Abstracción para sentencias SELECT con soporte de arrays. |
| `update()` | **Consultas DB** | Actualización de registros mediante mapeo de datos. |
| `delete()` | **Consultas DB** | Eliminación simplificada de registros con cláusula WHERE. |
| `Execute()` | **Consultas DB** | Ejecución de queries SQL personalizadas. |
| `hashcad()` | **Cripto** | Generación de Hash SHA512 de alta seguridad. |
| `hashcadalgo()` | **Cripto** | Generación de hashes seleccionando el algoritmo (MD5, SHA256, etc). |
| `genHash()` | **Seguridad** | Creación de cadenas pseudoaleatorias para tokens o sales. |
| `ExportarSQL()` | **Seguridad** | Backup integral de la estructura y datos de la DB. |
| `ExportCSV()` | **Consultas DB** | Exportación rápida de resultados a formato plano. |
| `MailSend()` | **Seguridad** | Envío de correos electrónicos con sanitización HTML. |

---

## 🚀 Ejemplos de Implementación

### 1. Conexión y Configuración
```php
define("SERVIDOR_BD", "127.0.0.1");
define("NOMBRE_BD", "dbname");
define("USUARIO_BD", "user");
define("CLAVE_BD", "password");

include("xsqlart.class.php");

$db = xsqlart::getInstance();
$db->run();


// SELECT con WHERE
$db->select('usuarios', ['nombre', 'correo'], ['id' => 5]);

// UPDATE
$db->update('config', ['tema' => 'dark'], ['usuario' => 'arturo']);

// DELETE
$db->delete('logs', ['fecha' => '2023-01-01']);

// Generar un token de seguridad de 128 caracteres
$token = $db->genHash(128);

// Hashear una contraseña con SHA512
$passwordSafe = $db->hashcad("user_password_123");

```

## 📜 Licencia (MIT)
**Copyright (c) 2019-2026 Arturo Vásquez Soluciones de Sistemas / AVFDigital**

Se concede permiso, de forma gratuita, a cualquier persona que obtenga una copia de este software y de los archivos de documentación asociados (el "Software"), para utilizar el Software sin restricción, incluyendo, sin limitación, los derechos de uso, copia, modificación, fusión, publicación, distribución, sublicencia y/o venta de copias del Software, sujeto a las siguientes condiciones:

El aviso de copyright anterior y este aviso de permiso se incluirán en todas las copias o partes sustanciales del Software.

---

## 🤝 Soporte y Colaboración
Si esta librería te ha sido de utilidad para agilizar tus desarrollos o mejorar tus proyectos, puedes apoyar su mantenimiento y la creación de nuevas herramientas mediante una donación:

[![Paypal Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/paypalme/avsolucionesweb)

```html
<p align="center">
  <b>Desarrollado por Arturo Vásquez</b><br>
  <i>Expert Web Development & System Solutions</i>
</p>
```