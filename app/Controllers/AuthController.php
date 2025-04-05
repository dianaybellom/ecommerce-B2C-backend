<?php

namespace App\Controllers;

use App\Models\Usuario;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $modelName = Usuario::class;
    protected $format    = 'json';

    // Registro de usuario
    public function register()
    {
        $data = $this->request->getJSON(true);

        // Validaciones básicas
        if (!$data || !isset($data['correo'], $data['contrasena'])) {
            return $this->failValidationError('Correo y contraseña son obligatorios.');
        }

        // Verificar si ya existe un usuario con ese correo
        $usuarioExistente = $this->model->where('correo', $data['correo'])->first();
        if ($usuarioExistente) {
            return $this->failResourceExists('El correo ya está registrado.');
        }

        // Hashear la contraseña
        $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        // Asignación de rol automática
        $correoAdmin = 'dianabellomejia_@hotmail.com';
        $data['rol'] = ($data['correo'] === $correoAdmin) ? 'ADMIN' : 'SHOPPER';

        // Guardar en la base de datos
        if (!$this->model->insert($data)) {
            return $this->failServerError('No se pudo registrar el usuario.');
        }

        return $this->respondCreated(['mensaje' => 'Usuario registrado exitosamente']);
    }



    // Inicio de sesión
    public function login()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['correo'], $data['contrasena'])) {
            return $this->failValidationError('Correo y contraseña requeridos.');
        }

        $usuario = $this->model->where('correo', $data['correo'])->first();

        if (!$usuario || !password_verify($data['contrasena'], $usuario['contrasena'])) {
            return $this->failUnauthorized('Credenciales inválidas.');
        }

        // Iniciar sesión (guardar ID en sesión)
        session()->set('usuario_id', $usuario['id']);
        session()->set('rol', $usuario['rol']);

        return $this->respond([
            'mensaje' => 'Inicio de sesión exitoso',
            'usuario' => [
                'id'       => $usuario['id'],
                'nombre'   => $usuario['nombre'],
                'apellido' => $usuario['apellido'],
                'correo'   => $usuario['correo'],
                'rol'      => $usuario['rol']
            ]
        ]);
    }

    // Cierre de sesión
    public function logout()
    {
        session()->destroy();
        return $this->respond(['mensaje' => 'Sesión cerrada']);
    }

    // Obtener usuario actual desde la sesión
    public function usuarioActual()
    {
        $usuarioId = session()->get('usuario_id');
        $rol = session()->get('rol');

        if (!$usuarioId) {
            return $this->respond(['autenticado' => false]);
        }

        return $this->respond([
            'autenticado' => true,
            'usuario_id' => $usuarioId,
            'rol' => $rol
        ]);
    }


    public function optionsHandler()
    {
        return $this->response->setStatusCode(200);
    }

}
