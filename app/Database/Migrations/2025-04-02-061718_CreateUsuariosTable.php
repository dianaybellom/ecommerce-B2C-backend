<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'fecha_nacimiento' => [
                'type' => 'DATE',
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true,
            ],
            'contrasena' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'rol' => [
            'type'       => 'ENUM',
            'constraint' => ['SHOPPER', 'ADMIN'],
            'default'    => 'SHOPPER'
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('usuarios'); 
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
