# 🛒 Plataforma ecommerce-B2C BACKEND

Este es el backend de una plataforma de ecommerce desarrollado con CodeIgniter 4 y PHP. La base de datos utilizada es MySQL y la arquitectura sigue el patrón MVC.

Este backend expone una API RESTful que permite gestionar usuarios, productos, pedidos y comunicación con un asistente conversacional (chatbot). Todos los endpoints responden en formato JSON y pueden ser consumidos por cualquier frontend compatible.

Entre sus características principales destacan:

- Autenticación de usuarios.
- Gestión completa del catálogo de productos: Consulta, creación, edición y actualización.
- Registro, consulta y procesamiento de pedidos.
- Control de accesos a funciones administrativas y de comprador según roles.
- Integración con la API de OpenAI para responder preguntas relacionadas a la tienda mediante un chatbot.
- Interfaz de despliegue automatizado mediante GitHub Actions hacia una instancia EC2 de AWS.


## 📚 Tabla de Contenidos
- [Sobre el Frontend](#-sobre-el-frontend)
- [Tecnologías Usadas](#-tecnologías-usadas)
- [Arquitectura del Sistema](#-arquitectura-del-sistema)
- [Autenticación y Seguridad](#-autenticación-y-seguridad)
- [Sobre la Base de Datos](#️-sobre-la-base-de-datos)
- [Endpoints](#️-endpoints)
- [Capturas de Pantalla](#️-capturas-de-pantalla)
    - [Operaciones CRUD](#operaciones-crud)
        - [register](#register)
        - [login](#login)
        - [usuarios](#usuarios)
        - [producto](#producto)
        - [pedido](#pedido)
        - [mis-pedidos](#mis-pedidos)
        - [chatbot](#chatbot)
- [Instrucciones para Ejecutar y Configurar](#-instrucciones-para-ejecutar-y-configurarr)
    - [Ejecución Local](#ejecución-local)
    - [Configuración de la API del Chatbot](#configuración-de-la-api-del-chatbot)
    - [Ejecución AWS con CI/CD Github Actions](#ejecución-aws-con-cicd-github-actions)
- [Desafios presentados](#-desafios-presentados)
- [Contacto](#-contacto)


## 🌐 Sobre el Frontend
El frontend de este proyecto se encuentra en el repositorio https://github.com/dianaybellom/ecommerce-B2C.

## 🚀 Tecnologías Usadas
- PHP 8.2.12
- CodeIgniter 4.6
- MySQL
- XAMPP
- Composer

### Despliegue en AWS
- Ubuntu 22.04 (t3.medium)
- Apache Server

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
    - `Chatbot`: recibe mensajes desde el frontend y utiliza la API de OpenAI para generar respuestas, funcionando como asistente automatizado.

- **Vistas**: Las vistas están centralizadas en el frontend (otro repositorio). Este backend actúa como API RESTful y expone endpoints consumidos vía JSON.

## 🔐 Autenticación y Seguridad
- Se usa sesión de PHP para autenticar usuarios.
- Hay endpoints protegidos que requieren autenticación: Se valida la autenticación y el rol (por ejemplo, ADMIN) usando `session()->get('usuario_id')` y `session()->get('rol')` desde los controladores. Se protegen las operaciones:  crear pedidos, visualizar pedidos personales, modificar roles de usuario y gestionar productos. Los endpoints públicos como GET /producto no requieren autenticación.
- Los roles determinan el acceso: solo `ADMIN` puede eliminar o actualizar pedidos de otros usuarios.
- Las respuestas de error están normalizadas y se evita exponer información sensible.
- Se encripta la contraseña de los usuarios al almacenar en la base de datos.

## 🗃️ Sobre la Base de Datos
Se utilizó MySQL como sistema de base de datos relacional. 

### Estructura
Se cuentan con las siguientes tablas y campos:

- **productos**:
    - `id`: identificador único del producto.
    - `nombre`: nombre del producto.
    - `descripcion`: descripción detallada del producto.
    - `categoria`: categoría a la que pertenece el producto.
    - `precio`: precio unitario del producto.
    - `stock`: cantidad disponible en inventario.
    - `fecha_creacion`: fecha en que se creó el registro.
    - `fecha_actualizacion`: fecha en que se actualizó por última vez el registro.
    - `imagen`: nombre del archivo de imagen asociado al producto (ruta relativa en `public/uploads/productos/`)

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
![ecommerce-db-diagram](https://github.com/user-attachments/assets/c589e2d5-6fda-4072-bc78-a7a89b1c22c6)


## ⚙️ Endpoints
Los endpoints disponibles son:

| Método | Ruta | Descripción |Ejemplo |
|--------|------|-------------|---------|
| POST | `/register` | Registro de nuevos usuarios | <pre><code>curl -X POST http://localhost:8080/register \ -H "Content-Type: application/json" \ -d '{"nombre":"Diana","apellido":"Bello 2","fecha_nacimiento":"1990-04-02","correo":"diana2@example.com","contrasena":"supersecreto","telefono":"123456789"}'</code></pre> |
| POST | `/login` | Inicio de sesión | <pre><code>curl -X POST http://localhost:8080/login \ -H "Content-Type: application/json" \ -d '{"correo":"diana@example.com","contrasena":"supersecreto"}'</code></pre> |
| GET | `/logout` | Cierre de sesión | <pre><code>curl http://localhost:8080/logout</code></pre> |
| GET | `/admin/usuarios` | Listar todos los usuarios (solo admin) | <pre><code>curl http://localhost:8080/admin/usuarios</code></pre> |
| PUT | `/admin/usuarios/{id}/rol` | Cambiar rol del usuario (solo admin) | <pre><code>curl -X PUT http://localhost:8080/admin/usuarios/2/rol \ -H "Content-Type: application/json" \ -d '{"rol":"ADMIN"}'</code></pre> |
| GET | `/usuario-actual` | Verifica si el usuario está autenticado y devuelve sus datos | <pre><code>curl http://localhost:8080/usuario-actual</code></pre> |
| GET | `/producto` | Listar todos los productos | <pre><code>curl http://localhost:8080/producto</code></pre> |
| GET | `/producto/{id}` | Ver un producto por ID | <pre><code>curl http://localhost:8080/producto/1</code></pre> |
| POST | `/producto` | Crear nuevo producto (solo admin) | <pre><code>curl -X POST http://localhost:8080/producto \ -H "Content-Type: application/json" \ -d '{"nombre":"Blusa Fleur Futur","categoria":"Blusas","precio":370,"stock":5,"descripcion":"Florece como los cerezos al alba, una pieza que une suavidad, brillo y un estilo vanguardista.","imagen_base64":"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA..."}'</code></pre> |
| PUT | `/producto/{id}` | Editar producto (solo admin) | <pre><code>curl -X PUT http://localhost:8080/producto/4 \ -H "Content-Type: application/json" \ -d '{"nombre":"Robe Sakura Dreams MODIFIED"}'</code></pre> |
| DELETE | `/producto/{id}` | Eliminar producto (solo admin) | <pre><code>curl -X DELETE http://localhost:8080/producto/3</code></pre> |
| GET | `/pedido` | Listar todos los pedidos (solo admin) | <pre><code>curl http://localhost:8080/pedido</code></pre> |
| GET | `/pedido/{id}` | Ver un pedido con sus productos | <pre><code>curl http://localhost:8080/pedido/1</code></pre> |
| POST | `/pedido` | Crear un nuevo pedido | <pre><code>curl -X POST http://localhost:8080/pedido \ -H "Content-Type: application/json" \ -d '{"productos":[{"producto_id":1,"cantidad":2},{"producto_id":2,"cantidad":1}]}'</code></pre> |
| PUT | `/pedido/{id}` | Actualizar un pedido (solo admin) | <pre><code>curl -X PUT http://localhost:8080/pedido/2 \ -H "Content-Type: application/json" \ -d '{"estado":"confirmado"}'</code></pre> |
| DELETE | `/pedido/{id}` | Eliminar un pedido (solo admin) | <pre><code>curl -X DELETE http://localhost:8080/pedido/2</code></pre> |
| GET | `/mis-pedidos` | Listar pedidos del usuario autenticado | <pre><code>curl http://localhost:8080/mis-pedidos</code></pre> |
| POST | `/chatbot` | Envía una pregunta al chatbot (requiere token válido) | <pre><code>curl -X POST http://localhost:8080/chatbot \ -H "Authorization: Bearer {token}" \ -H "Content-Type: application/json" \ -d '{"mensaje":"¿Cuáles son los vestidos disponibles?"}'</code></pre> |

## 🖥️ Capturas de Pantalla
### Operaciones CRUD
Las siguientes capturas muestran la operaciones CRUD realizadas en Postman para los distintos endpoints disponibles.

#### register
- **POST**

![POST-register](https://github.com/user-attachments/assets/db36672c-9644-482d-9767-e1142ebe333a)

#### login
- **POST**

![POST-login](https://github.com/user-attachments/assets/b9cbe4f5-ae2f-42d2-8412-1b6bb868175a)

#### usuarios
- **GET /admin/usuarios**

*Con usuario SHOPPER*
![GET-admin-usuarios-SHOPPER](https://github.com/user-attachments/assets/0e6ae9f2-81f5-42bf-a227-85149eb7670a)

*Con usuario ADMIN*
![GET-admin-usuarios-ADMIN](https://github.com/user-attachments/assets/c1722329-b29f-4743-89a7-323f57ae5b81)

- **PUT /admin/usuarios/{id}/rol**

*Con usuario SHOPPER*
![PUT-admin-usuarios-SHOPPER](https://github.com/user-attachments/assets/1b870c53-913a-4755-b8d5-afdd59371e5d)

*Con usuario ADMIN*
![PUT-admin-usuarios-ADMIN](https://github.com/user-attachments/assets/f2413ac0-98a8-4f6e-8e0c-4aef9cb2ef50)

#### producto
- **POST**
 
*Con usuario SHOPPER*
![POST-producto-SHOPPER](https://github.com/user-attachments/assets/3d589c69-13a2-4ba6-9728-790fcef3f070)

*Con usuario ADMIN*
![POST-producto-ADMIN](https://github.com/user-attachments/assets/ab8b7f8c-316c-4844-b96b-d5cd6a3a9b5a)


- **GET**

![GET](https://github.com/user-attachments/assets/2380934f-cc4c-4479-b4f4-17d755e95946)

- **PUT**

*Con usuario ADMIN*
![PUT](https://github.com/user-attachments/assets/1b066c50-fd18-496b-934f-91e1ec334a6e)

- **DELETE**

*Con usuario ADMIN*
![DELETE](https://github.com/user-attachments/assets/1648eeee-97d9-453c-8a56-326316bd1508)

#### pedido
- **POST**

![POST-pedido](https://github.com/user-attachments/assets/c347a5e6-fde3-4932-bb6b-665d39ca3c73)


- **GET**

*Con usuario ADMIN*
![GET-pedido](https://github.com/user-attachments/assets/d5782761-b0af-4283-9379-3d467959d346)

- **PUT**

*Con usuario ADMIN*
![PUT-pedido](https://github.com/user-attachments/assets/fdc0c858-ec52-4e2e-ba9c-1bbf5edd5c2f)

- **DELETE**

*Con usuario ADMIN*
![DELETE-pedido](https://github.com/user-attachments/assets/36ab8671-301b-4096-8d7a-b9ee7f7df1d9)


#### mis-pedidos
- **GET**

![GET-mis-pedidos](https://github.com/user-attachments/assets/c2c0d6a7-7922-4c61-8799-c3161398a828)

#### chatbot

El chatbot está implementado en el controlador Chatbot.php dentro de app/Controllers. Este controlador expone el endpoint:

**POST** /chatbot
![POST-chatbot](https://github.com/user-attachments/assets/79f171af-af7b-47d9-a6c0-a23d563d8ad4)


### Almacenamiento de contraseña usando hash
![password](https://github.com/user-attachments/assets/873e63c6-8e91-4cb3-8a51-e14f05f364b2)


## 📌 Instrucciones para Ejecutar y Configurar

### Ejecución local 

1. Clonar este repositorio:
```bash
git clone https://github.com/tu_usuario/ecommerce-B2C-backend.git
``` 

2. Instalar dependencias CodeIgniter4:
```bash
composer install
```

4. Copiar el archivo .env y configurar entorno:
```bash
cp env .env
```

Editar las siguientes líneas:
```ini
CI_ENVIRONMENT = development
database.default.hostname = localhost
database.default.database = ecommerce
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

4. Crear la base de datos ecommerce desde phpMyAdmin o línea de comandos.

5. Ejecutar migraciones para crear la tabla:
```bash
php spark migrate
```

7. Iniciar el servidor de desarrollo:
```bash
php spark serve
```

La API estará disponible en: http://localhost:8080/ .

### Configuración de la API del Chatbot

1. Ingresar al sitio oficial de OpenAI: https://platform.openai.com y acceder a su cuenta.
2. Acceder al panel de API Keys.
3. Crear una nueva API key (sk-...) y copiarla.
4. Instalar la biblioteca de cliente HTTP (ya incluida en CodeIgniter 4).
5. Definir la key que obtuvo en el paso 3 en el proyecto:
    - Si está local o un despliegue en AWS manual: Abrir el archivo .env en la raíz del backend y agregar o modificar la siguiente línea con el API Key:
        ```bash
        OPENAI_API_KEY=sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx 
        ```

    - Si está en AWS con Github Actions: En Github, dentro del repositorio en la ruta 'Settings > Secrets and variables > Actions' agregar un nuevo secret  con el nombre `OPENAI_API_KEY` y el API Key como Secret.

### Ejecución AWS con CI/CD Github Actions
Para desplegar automáticamente este backend en un servidor EC2 de AWS al hacer push en la rama main, se puede utilizar GitHub Actions con los siguientes pasos:

1. Crear instancia EC2 y preparar el entorno.
    - Tipo: t3.medium con Ubuntu 22.04
    - Instalar Apache2, PHP (8.2+), Composer y extensiones requeridas
    - Configurar MySQL y crear la base de datos ecommerce y usuario ecom_user.
    - Habilitar puertos 80 (HTTP) y 22 (SSH), 3306 (MySQL) en el grupo de seguridad.
    - Guardar key .pem.
2. Acceder a la terminal de la instancia con la .pem (en mi caso, usé MobaXterm) y correr los siguientes comandos:
    ```bash
    sudo apt update && sudo apt upgrade -y
    sudo apt install software-properties-common -y
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt update
    sudo apt install apache2 php8.2 php8.2-cli php8.2-mysql php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip php8.2-intl unzip -y
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm composer-setup.php
    ```
3. Instalar MySQL y crear base de datos:
    ```bash
    sudo apt install mysql-server -y
    sudo mysql_secure_installation
    sudo mysql -u root -p
        # Dentro del pront de Mysql:
    CREATE DATABASE ecommerce_db;
    CREATE USER 'ecom_user'@'localhost' IDENTIFIED BY 'Password123!';
    GRANT ALL PRIVILEGES ON ecommerce_db.* TO 'ecom_user'@'localhost';
    FLUSH PRIVILEGES;
    EXIT;
    ```
4. Configurar Apache:
    - Crear el archivo de configuración para que el backend se sirva bajo el alias /api:
    ```bash
    sudo nano /etc/apache2/sites-available/000-default.conf
    ```
    Contenido:
    ```bash
    <VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html

        Alias /api /var/www/html/backend/public
        <Directory /var/www/html/backend/public>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
    ```
    - Luego ejecutar:
    ```bash
    sudo a2enmod rewrite
    sudo systemctl restart apache2
    ```
5. Crear secrets necesarios en Github, dentro del repositorio en la ruta 'Settings > Secrets and variables > Actions'  agregar:
    - `EC2_HOST`: Dirección IP pública de tu instancia EC2 (ej. 3.144.119.116)
    - `EC2_KEY`: Cpntenido de archivo .pem de clave privada SSH para conectarse como ubuntu.
    - `OPENAI_API_KEY`: (Opcional) API Key de OpenAI utilizada por el chatbot.
6. Configurar repositorio con archivo de workflow CI/CD `.github/workflows/deploy-backend.yml` (Ver el creado en este repo).
7. Hacer push a la rama main para ejecutar el deploy automático.
8. Verificar instalación ejecutando `php spark migrate` en el servidor.
9. Probar la API accediendo a http://EC2_HOST/api/.

La API productiva de este repositorio está disponible en: http://3.144.119.116/api/ 

_Nota: Al momento de revisión la URL a la API productiva podría estar no accesible debido a la detención de la instancia en AWS por temas de costos._

**Posibles errores y soluciones**
- _Error "message": "Cache unable to write to \"/var/www/html/backend/writable/cache/\"._
En ocaciones, con el despliegue se dudtituye y pierde el archivo writable/cache, es por ello que hay que crearlo manualmente. Ejecute los siguientes comandos para solventar:
```bash
sudo mkdir -p /var/www/html/backend/writable/cache
sudo chown -R www-data:ubuntu /var/www/html/backend/writable
sudo chmod -R 775 /var/www/html/backend/writable
```

- _Error "Unable to connect to the database." al ejecutar `php spark migrate`_
Si con cada despliegue no se  evita que se sustituya el .env, los datos de user o password de la base de datos se pueden perder. Para corregirlo vaya a y ejecute `sudo nano /var/www/html/backend/.env` y asegurese de tener en las siguientes propiedades todos los valores que definió en la configuración.
```bash
database.default.hostname = localhost
database.default.database = ecommerce
database.default.username = ecom_user
database.default.password = *******
database.default.DBDriver = MySQLi
```

## 🔥 Desafios presentados

### Despliegue en AWS y CI/CD con Github Actions
- Persistencia del archivo .env en despliegues CI/CD: Se ajustó el workflow de GitHub Actions para excluir el archivo .env del proceso de limpieza.
- Permisos del directorio writable: Se incorporaron comandos en el pipeline de despliegue para crear el subdirectorio writable/cache (si no existe), y aplicar permisos adecuados (775) y propietario www-data. Cabe resaltar que aún en ocasiones aún me falla y tengo que intervenir manualmente.
- Alias /api y rutas REST: Se utilizó correctamente el Alias en Apache (Alias /api /var/www/html/backend/public) sin necesidad de modificar las rutas internas de CodeIgniter.

## 📝 Disclaimer
Este proyecto ha sido desarrollado con el apoyo de ChatGPT, un modelo de lenguaje de inteligencia artificial creado por OpenAI.

Debido a mi nivel de expertise en desarrollo de software, creé un GPT como asistente de desarrollo, para:
- Generar código base funcional
- Resolver errores y mensajes de compilación
- Explicar buenas prácticas

Este es el link del GPT creado: https://chatgpt.com/g/g-67cda8de6b7c8191a38f6722c69cbf4c-cse642-soft-development-expert
  
## 📫 Contacto
dianabellomejia_@hotmail.com
