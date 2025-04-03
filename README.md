# 🛒 Plataforma ecommerce-B2C BACKEND

Este es el backend de una plataforma de ecommerce desarrollado con [CodeIgniter 4](https://codeigniter.com/) y PHP. Implementa una API RESTful para gestionar productos con funcionalidad CRUD y conexión a base de datos MySQL.

## 📚 Tabla de Contenidos
- [Sobre el Frontend](#-sobre-el-frontend)
- [Tecnologías Usadas](#-tecnologías-usadas)
- [Arquitectura del Sistema](#-arquitectura-del-sistema)
- [Autenticación y Seguridad](#-autenticación-y-seguridad)
- [Sobre la Base de Datos](#️-sobre-la-base-de-datos)
- [Endpoints](#endpoints)
- [Capturas de Pantalla](#️-capturas-de-pantalla)
    - [Operaciones CRUD](#operaciones-crud)
        - [register](#register)
        - [login](#login)
        - [usuarios](#usuarios)
        - [producto](#producto)
        - [pedido](#pedido)
        - [mis-pedidos](#mis-pedidos)
- [Instrucciones para Ejecutar](#-instrucciones-para-ejecutar)
- [Contacto](#-contacto)


## 🌐 Sobre el Frontend
El frontend de este proyecto se encuentra en el repositorio https://github.com/dianaybellom/ecommerce-B2C.

## 🚀 Tecnologías Usadas
- PHP 8.2.12
- CodeIgniter 4.6
- MySQL
- XAMPP
- Composer

## 📂 Arquitectura del Sistema

Este sistema sigue la estructura MVC (Modelo - Vista - Controlador) que propone CodeIgniter:

- **Modelos**: Los modelos se implementaron utilizando `CodeIgniter\Model` para interactuar con la base de datos:

    - `Usuario`: gestión de usuarios y roles (`SHOPPER`, `ADMIN`)
    - `Producto`: catálogo de productos
    - `PedidoModel`: cabecera del pedido (total, estado, usuario)
    - `PedidosDetalle`: productos asociados a cada pedido

- **Controladores**: gestionan las rutas y lógica de negocio:
    - `Admin`: funcionalidades administrativas para usuarios con rol `ADMIN`.
    - `AuthController`: autenticación y gestión de sesiones de usuario.
    - `Pedido`: creación, actualización y consulta de pedidos y detalles asociados.
    - `ProductoController`: operaciones del catálogo de productos (CRUD).

- **Vistas**: Las vistas están centralizadas en el frontend (otro repositorio). Este backend actúa como API RESTful y expone endpoints consumidos vía JSON.

## 🔐 Autenticación y Seguridad
- Se usa sesión de PHP para autenticar usuarios.
- Hay endpoints protegidos que requieren autenticación: Se valida la autenticación y el rol (por ejemplo, ADMIN) usando `session()->get('usuario_id')` y `session()->get('rol')` desde los controladores. Se protegen las operaciones:  crear pedidos, visualizar pedidos personales, modificar roles de usuario y gestionar productos. Los endpoints públicos como GET /producto no requieren autenticación.
- Los roles determinan el acceso: solo `ADMIN` puede eliminar o actualizar pedidos de otros usuarios.
- Las respuestas de error están normalizadas y se evita exponer información sensible.

## 🗃️ Sobre la Base de Datos
Se utilizó MySQL como sistema de base de datos relacional. 

### Estructura
Se cuentan con las siguientes tablas y campos:

- **productos**:
    - `id`
    - `nombre`
    - `descripcion`
    - `categoria`
    - `precio`
    - `stock`
    - `fecha_creacion`
    - `fecha_actualizacion`

- **usuarios**
    - `id`: identificador único del usuario.
    - `nombre`: nombre del usuario.
    - `apellido`: apellido del usuario.
    - `fecha_nacimiento`: fecha de nacimiento del usuario.
    - `correo`: dirección de correo electrónico del usuario.
    - `contrasena`: contraseña cifrada del usuario.
    - `telefono`: número telefónico del usuario.
    - `created_at`: fecha de creación del registro del usuario.
    - `updated_at`: fecha de última actualización del registro del usuario.
    - `rol`: tipo de usuario, que puede ser `'SHOPPER'` o `'ADMIN'`.

- **pedidos**:
    - `id`: identificador único del pedido.
    - `usuario_id`: identificador del usuario que realizó el pedido (relación con tabla usuarios).
    - `estado`: estado actual del pedido (ej. pendiente, completado, cancelado).
    - `total`: monto total del pedido.
    - `fecha_creacion`: fecha en que fue creado el pedido.
    - `fecha_actualizacion`: fecha de última actualización del pedido.

- **pedidos_detalle**:
    - `id`: identificador único del detalle del pedido.
    - `pedido_id`: identificador del pedido al que pertenece (relación con tabla pedidos).
    - `producto_id`: identificador del producto solicitado (relación con tabla productos).
    - `cantidad`: cantidad solicitada del producto.
    - `precio_unitario`: precio unitario del producto en el momento del pedido.
    - `total`: total por línea (cantidad multiplicada por precio unitario).
    - `created_at`: fecha de creación del registro.
    - `updated_at`: fecha de última actualización del registro.


### Diagrama de relación entre tablas



## ⚙️ Endpoints
Los endpoints disponibles son:

| Método | Ruta                            | Descripción                                      |
|--------|----------------------------------|--------------------------------------------------|
| POST   | `/register`                     | Registro de nuevos usuarios                      |
| POST   | `/login`                        | Inicio de sesión                                 |
| GET    | `/logout`                       | Cierre de sesión                                 |
| GET    | `/admin/usuarios`              | Listar todos los usuarios (solo usuarios con rol admin)           |
| PUT    | `/admin/usuarios/{id}/rol`     | Cambiar rol del usuario (solo usuarios con rol admin)             |
| GET    | `/producto`                    | Listar todos los productos                       |
| GET    | `/producto/{id}`               | Ver un producto por ID                           |
| POST   | `/producto`                    | Crear nuevo producto                             |
| PUT    | `/producto/{id}`               | Editar producto                                  |
| DELETE | `/producto/{id}`               | Eliminar producto                                |
| GET    | `/pedido`                      | Listar todos los pedidos                         |
| GET    | `/pedido/{id}`                 | Ver un pedido con sus productos                  |
| POST   | `/pedido`                      | Crear un nuevo pedido                            |
| PUT    | `/pedido/{id}`                 | Actualizar un pedido (solo usuarios con rol admin)                |
| DELETE | `/pedido/{id}`                 | Eliminar un pedido (solo usuarios con rol admin)                  |
| GET    | `/mis-pedidos`                | Listar pedidos del usuario autenticado           |



## 🖥️ Capturas de Pantalla
### Operaciones CRUD
Las siguientes capturas muestran la operaciones CRUD realizadas en Postman para los productos.

#### register
- **POST**

#### login
- **POST**

#### usuarios
- **GET**

- **PUT**

#### producto
- **POST**
![POST](https://github.com/user-attachments/assets/91fb7661-240f-4ce3-9a9f-de05aea44f90)

- **GET**
![GET](https://github.com/user-attachments/assets/2380934f-cc4c-4479-b4f4-17d755e95946)

- **PUT**
![PUT](https://github.com/user-attachments/assets/1b066c50-fd18-496b-934f-91e1ec334a6e)

- **DELETE**
![DELETE](https://github.com/user-attachments/assets/1648eeee-97d9-453c-8a56-326316bd1508)

#### pedido
- **POST**

- **GET**

- **PUT**

- **DELETE**

#### mis-pedidos
- **GET**


## 📌 Instrucciones para Ejecutar
1. Clonar este repositorio:
```bash ```
``` git clone https://github.com/tu_usuario/ecommerce-B2C-backend.git ``` 


2. Instalar dependencias CodeIgniter4:
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

La API estará disponible en: http://localhost:8080/

## 📝 Disclaimer
Este proyecto ha sido desarrollado con el apoyo activo de ChatGPT, un modelo de lenguaje de inteligencia artificial creado por OpenAI.

Debido a mi limitado expertise en desarrollo de software, creé un GPT como asistente de desarrollo, para:
- Generar código base funcional
- Resolver errores y mensajes de compilación
- Explicar buenas prácticas

Este es el link del GPT creado: https://chatgpt.com/g/g-67cda8de6b7c8191a38f6722c69cbf4c-cse642-soft-development-expert
  
## 📫 Contacto
dianabellomejia_@hotmail.com
