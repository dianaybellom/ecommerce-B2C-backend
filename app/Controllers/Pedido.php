<?php

namespace App\Controllers;

use App\Models\Pedido;
use CodeIgniter\RESTful\ResourceController;

class Pedido extends ResourceController
{
    protected $modelName = Pedido::class;
    protected $format    = 'json';

    // GET /pedido
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // GET /pedido/{id}
    public function show($id = null)
    {
        $pedido = $this->model->find($id);
        return $pedido
            ? $this->respond($pedido)
            : $this->failNotFound('Pedido no encontrado');
    }

    // POST /pedido
    public function create()
    {
        $data = $this->request->getJSON(true);

        // Obtener el ID de usuario desde la sesión
        $usuarioId = session()->get('usuario_id');
        if (!$usuarioId) {
            return $this->failUnauthorized('Debes iniciar sesión para crear un pedido.');
        }

        $data['usuario_id'] = $usuarioId;

        if (!$this->model->insert($data)) {
            return $this->failServerError('Error al crear el pedido');
        }

        return $this->respondCreated($data);
    }

    // PUT /pedido/{id}
    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar el pedido');
        }

        return $this->respond($data);
    }

    // DELETE /pedido/{id}
    public function delete($id = null)
    {
        if (!$this->model->delete($id)) {
            return $this->failServerError('No se pudo eliminar el pedido');
        }

        return $this->respondDeleted(['id' => $id]);
    }
}
