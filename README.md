# 🛒 Plataforma ecommerce-B2C BACKEND

Este es el backend de una plataforma de ecommerce desarrollado con [CodeIgniter 4](https://codeigniter.com/) y PHP. Implementa una API RESTful para gestionar productos con funcionalidad CRUD y conexión a base de datos MySQL.

## 📚 Tabla de Contenidos
- [Sobre el Frontend](#-sobre-el-frontend)
- [Tecnologías Usadas](#-tecnologías-usadas)
- [Arquitectura del Sistema](#-arquitectura-del-sistema)
- [Sobre la Base de Datos](#️-sobre-la-base-de-datos)
- [Capturas de Pantalla](#️-capturas-de-pantalla)
    - [Operaciones CRUD](#operaciones-crud)
    - [Base de Datos](#base-de-datos)
- [Instrucciones para Ejecutar](#-instrucciones-para-ejecutar)
- [Disclaimer](#-disclaimer)
- [Contacto](#-contacto)


## 🌐 Sobre el Frontend
El frontend de este proyecto se encuentra en el repositorio https://github.com/dianaybellom/ecommerce-B2C.

## 🚀 Tecnologías Usadas
- PHP 8.x
- CodeIgniter 4.6
- MySQL
- XAMPP
- Composer

## 📂 Arquitectura del Sistema

Este sistema sigue la estructura MVC (Modelo - Vista - Controlador) que propone CodeIgniter:

- **Modelos**: gestionan los datos (`app/Models/Producto.php`)
- **Controladores**: gestionan las rutas y lógica de negocio (`app/Controllers/ProductoController.php`)
- **Vistas**: para esta fase el backend solo responde con JSON.

## 🗃️ Sobre la Base de Datos
Se utilizó MySQL como sistema de base de datos relacional. La tabla productos contiene los siguientes campos:
- `id`
- `nombre`
- `descripcion`
- `categoria`
- `precio`
- `stock`
- `fecha_creacion`
- `fecha_actualizacion`

## 🖥️ Capturas de Pantalla
### Operaciones CRUD
Las siguientes capturas muestran la operaciones CRUD realizadas en Postman.

**POST**

**GET**

**PUT**

**DELETE**

### Base de datos
La siguiente captura muestra el contenido de la tabla productos en phpMyAdmin, evidenciando los datos insertados y los campos de fechas gestionados automáticamente por CodeIgniter.



## 📌 Instrucciones para Ejecutar
1. Clonar este repositorio:
```bash ```
``` git clone https://github.com/tu_usuario/ecommerce-B2C-backend.git ``` 


2. Instalar dependencias:
```composer install```

3. Copiar el archivo .env y configurar entorno:
```cp env .env```

Editar las siguientes líneas:
<pre><code>CI_ENVIRONMENT = development
database.default.hostname = localhost
database.default.database = ecommerce
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi </code></pre>

4. Crear la base de datos ecommerce desde phpMyAdmin o línea de comandos.

5. Ejecutar migraciones para crear la tabla:
```php spark migrate```

6. Iniciar el servidor de desarrollo:
```php spark serve```

## 📝 Disclaimer
Este proyecto ha sido desarrollado con el apoyo activo de ChatGPT, un modelo de lenguaje de inteligencia artificial creado por OpenAI.

Debido a mi limitado expertise en desarrollo de software, creé un GPT como asistente de desarrollo, para:
- Generar código base funcional
- Resolver errores y mensajes de compilación
- Explicar buenas prácticas

Este es el link del GPT creado: https://chatgpt.com/g/g-67cda8de6b7c8191a38f6722c69cbf4c-cse642-soft-development-expert
  
## 📫 Contacto
dianabellomejia_@hotmail.com