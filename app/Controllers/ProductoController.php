<?php

namespace App\Controllers;

use App\Models\Producto;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProductoController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = Producto::class;
    protected $format    = 'json';

    // GET /producto
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // GET /producto/{id}
    public function show($id = null)
    {
        $producto = $this->model->find($id);
        return $producto
            ? $this->respond($producto)
            : $this->failNotFound('Producto no encontrado');
    }

    // POST /producto
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationError('No se proporcionaron datos.');
        }

        if (isset($data[0])) {
            // Inserción múltiple
            if (!$this->model->insertBatch($data)) {
                return $this->failServerError('No se pudieron insertar los productos.');
            }
            return $this->respondCreated(['mensaje' => 'Productos creados exitosamente']);
        } else {
            // Inserción única
            if (!$this->model->insert($data)) {
                return $this->failServerError('No se pudo insertar el producto.');
            }
            return $this->respondCreated(['mensaje' => 'Producto creado exitosamente']);
        }
    }

    // PUT /producto/{id}
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        if (empty($data)) {
            return $this->failValidationError('No se recibieron datos para actualizar.');
        }

        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar el producto.');
        }

        return $this->respond(['mensaje' => 'Producto actualizado correctamente']);
    }

    // DELETE /producto/{id}
    public function delete($id = null)
    {
        if (!$this->model->delete($id)) {
            return $this->failServerError('No se pudo eliminar el producto.');
        }

        return $this->respondDeleted(['mensaje' => 'Producto eliminado']);
    }
}