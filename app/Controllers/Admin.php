<?php

namespace App\Controllers;

use App\Models\Usuario;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Admin extends ResourceController
{
    use ResponseTrait;

    protected $modelName = Usuario::class;
    protected $format    = 'json';

    // PUT /admin/usuarios/{id}/rol
    public function setRole($id = null)
    {
        $rol = session()->get('rol');
        if ($rol !== 'ADMIN') {
            return $this->respond([
                'error' => 'Acceso no autorizado. Solo administradores.'
            ], 403);
        }

        $data = $this->request->getJSON(true);

        if (!isset($data['rol']) || !in_array($data['rol'], ['SHOPPER', 'ADMIN'])) {
            return $this->failValidationError('Rol inválido.');
        }

        $usuario = $this->model->find($id);
        if (!$usuario) {
            return $this->failNotFound('Usuario no encontrado.');
        }

        $this->model->update($id, ['rol' => $data['rol']]);

        return $this->respond([
            'mensaje' => 'Rol actualizado correctamente',
            'usuario' => $this->model->find($id)
        ]);
    }

    // GET /admin/usuarios
    public function listUsers()
    {
        $rol = session()->get('rol');
        if ($rol !== 'ADMIN') {
            return $this->respond([
                'error' => 'Acceso no autorizado. Solo administradores.'
            ], 403);
        }

        return $this->respond($this->model->findAll());
    }

    public function optionsHandler($param = null)
    {
        return $this->response->setStatusCode(200);
    }

}
