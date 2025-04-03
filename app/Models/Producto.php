<?php

namespace App\Models;

use CodeIgniter\Model;

class Producto extends Model
{
    protected $table            = 'productos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'categoria', 
        'precio', 
        'stock',
        'imagen'
    ];

    // ✅ Manejo automático de fechas personalizadas
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';
}
