<?php

namespace App\Controllers;

use App\Models\PedidoModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Pedido extends ResourceController
{
    use ResponseTrait;

    protected $modelName = PedidoModel::class;
    protected $format    = 'json';

    // GET /pedido (solo para ADMIN)
    public function index()
    {
        $rol = session()->get('rol');
        if ($rol !== 'ADMIN') {
            return $this->respond([
                'error' => 'Acceso no autorizado. Solo administradores pueden ver todos los pedidos.'
            ], 403);
        }

        $pedidos = $this->model->findAll();
        $detalleModel = new \App\Models\PedidosDetalle();

        foreach ($pedidos as &$pedido) {
            $pedido['productos'] = $detalleModel->where('pedido_id', $pedido['id'])->findAll();
        }

        return $this->respond($pedidos);
    }

    // GET /mis-pedidos (solo pedidos del usuario autenticado)
    public function misPedidos()
    {
        $usuarioId = session()->get('usuario_id');
        if (!$usuarioId) {
            return $this->respond([
                'error' => 'Debes iniciar sesión para ver tus pedidos.'
            ], 401);
        }

        $detalleModel = new \App\Models\PedidosDetalle();
        $pedidos = $this->model->where('usuario_id', $usuarioId)->findAll();

        foreach ($pedidos as &$pedido) {
            $pedido['productos'] = $detalleModel->where('pedido_id', $pedido['id'])->findAll();
        }

        return $this->respond($pedidos);
    }

    // GET /pedido/{id}
    public function show($id = null)
    {
        $pedido = $this->model->find($id);
        if (!$pedido) {
            return $this->failNotFound('Pedido no encontrado');
        }

        $detalleModel = new \App\Models\PedidosDetalle();
        $pedido['productos'] = $detalleModel->where('pedido_id', $id)->findAll();

        return $this->respond($pedido);
    }

    // POST /pedido
    public function create()
    {
        $pedidoModel = $this->model;
        $detalleModel = new \App\Models\PedidosDetalle();
        $productoModel = new \App\Models\Producto();

        $usuarioId = session()->get('usuario_id');
        if (!$usuarioId) {
            return $this->respond([
                'error' => 'Debes iniciar sesión para hacer un pedido.'
            ], 401);
        }

        $data = $this->request->getJSON(true);

        if (!isset($data['productos']) || !is_array($data['productos']) || empty($data['productos'])) {
            return $this->respond([
                'error' => 'Debes enviar al menos un producto en el pedido.'
            ], 422);
        }

        $totalPedido = 0;

        foreach ($data['productos'] as $item) {
            if (!isset($item['producto_id']) || !isset($item['cantidad'])) {
                return $this->respond([
                    'error' => 'Cada producto debe tener producto_id y cantidad.'
                ], 422);
            }

            $producto = $productoModel->find($item['producto_id']);
            if (!$producto) {
                return $this->respond([
                    'error' => "Producto ID {$item['producto_id']} no existe."
                ], 422);
            }

            $precioUnitario = $producto['precio'];
            $cantidad = $item['cantidad'];
            $subtotal = $precioUnitario * $cantidad;

            $totalPedido += $subtotal;
        }

        $pedidoData = [
            'usuario_id' => $usuarioId,
            'total'      => $totalPedido,
            'estado'     => 'pendiente'
        ];

        $pedidoModel->insert($pedidoData);
        $pedidoId = $pedidoModel->getInsertID();

        foreach ($data['productos'] as $item) {
            $producto = $productoModel->find($item['producto_id']);
            $precioUnitario = $producto['precio'];
            $cantidad = $item['cantidad'];
            $subtotal = $precioUnitario * $cantidad;

            $detalleModel->insert([
                'pedido_id'       => $pedidoId,
                'producto_id'     => $item['producto_id'],
                'cantidad'        => $cantidad,
                'precio_unitario' => $precioUnitario,
                'total'           => $subtotal
            ]);
        }

        return $this->respondCreated([
            'mensaje'   => 'Pedido creado exitosamente',
            'pedido_id' => $pedidoId,
            'total'     => $totalPedido
        ]);
    }

    // PUT /pedido/{id}
    public function update($id = null)
    {
        $usuarioId = session()->get('usuario_id');
        $rol = session()->get('rol'); // Asegúrate de guardar el rol en la sesión al hacer login
    
        if (!$usuarioId || $rol !== 'ADMIN') {
            return $this->respond([
                'error' => 'No autorizado. Solo los administradores pueden actualizar pedidos.'
            ], 403);
        }
    
        $data = $this->request->getJSON(true);
    
        if (empty($data)) {
            return $this->respond([
                'error' => 'No se proporcionaron datos para actualizar.'
            ], 400);
        }
    
        if (!$this->model->update($id, $data)) {
            return $this->respond([
                'error' => 'No se pudo actualizar el pedido'
            ], 500);
        }
    
        return $this->respond([
            'mensaje' => 'Pedido actualizado correctamente',
            'pedido_id' => $id,
            'datos' => $data
        ]);
    }
    

    // DELETE /pedido/{id}
    public function delete($id = null)
    {
        $usuarioId = session()->get('usuario_id');
        $rol = session()->get('rol');
    
        if (!$usuarioId || $rol !== 'ADMIN') {
            return $this->respond([
                'error' => 'No autorizado. Solo los administradores pueden eliminar pedidos.'
            ], 403);
        }
    
        if (!$this->model->find($id)) {
            return $this->failNotFound("Pedido con ID {$id} no existe.");
        }
    
        if (!$this->model->delete($id)) {
            return $this->respond([
                'error' => 'No se pudo eliminar el pedido'
            ], 500);
        }
    
        return $this->respondDeleted([
            'mensaje' => 'Pedido eliminado correctamente',
            'pedido_id' => $id
        ]);
    }
    
}
