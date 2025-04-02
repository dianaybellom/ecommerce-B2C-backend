<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'usuario_id'       => ['type' => 'INT', 'unsigned' => true],
            'estado'           => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'pendiente'],
            'total'            => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'fecha_creacion'   => ['type' => 'DATETIME', 'null' => true],
            'fecha_actualizacion' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pedidos');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos');
    }
}
