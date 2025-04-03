<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductosTable extends Migration
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
				'descripcion' => [
					'type' => 'TEXT',
					'null' => true,
				],
				'categoria' => [
					'type'       => 'VARCHAR',
					'constraint' => '50',
				],
				'precio' => [
					'type'       => 'DECIMAL',
					'constraint' => '10,2',
				],
				'stock' => [
					'type'       => 'INT',
					'constraint' => 11,
				],
				'fecha_creacion' => [
					'type' => 'DATETIME',
					'null' => true,
				],
				'fecha_actualizacion' => [
					'type' => 'DATETIME',
					'null' => true,
				],
				'imagen' => [
					'type' => 'VARCHAR', 
					'constraint' => 255, 
					'null' => true
				],
			]);

			$this->forge->addKey('id', true);
			$this->forge->createTable('productos');
		}


		public function down()
		{
			$this->forge->dropTable('productos');
		}		
}
