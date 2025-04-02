<?php

namespace App\Controllers;

use App\Models\Producto;
use CodeIgniter\RESTful\ResourceController;

class ProductoController extends ResourceController
{
    protected $modelName = Producto::class;
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function show($id = null)
    {
        $producto = $this->model->find($id);
        return $producto
            ? $this->respond($producto)
            : $this->failNotFound('Producto no encontrado');
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        $this->model->insert($data);
        return $this->respondCreated($data);
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        if (empty($data)) {
            return $this->fail('No data received');
        }
    
        $this->model->update($id, $data);
        return $this->respond($data);
    }    

    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted(['id' => $id]);
    }
}
