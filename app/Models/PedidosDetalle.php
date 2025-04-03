<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidosDetalle extends Model
{
    protected $table            = 'pedidos_detalle';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'total'
    ];
}
