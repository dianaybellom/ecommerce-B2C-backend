<?php

namespace App\Controllers;

use App\Models\Usuario;
use CodeIgniter\RESTful\ResourceController;

class Admin extends ResourceController
{
    protected $modelName = Usuario::class;
    protected $format    = 'json';

    public function setRole($id = null)
    {
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

    public function listUsers()
    {
        return $this->respond($this->model->findAll());
    }
}
