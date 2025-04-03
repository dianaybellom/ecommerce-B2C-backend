<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidosDetalleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'pedido_id'       => ['type' => 'INT', 'unsigned' => true],
            'producto_id'     => ['type' => 'INT', 'unsigned' => true],
            'cantidad'        => ['type' => 'INT', 'unsigned' => true],
            'precio_unitario' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'total'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],

            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('producto_id', 'productos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pedidos_detalle');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos_detalle');
    }
}