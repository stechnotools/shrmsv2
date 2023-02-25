<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserGroup extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => TRUE
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => TRUE
            ],
            'deleted_at' => [
                'type' => 'datetime',
                'null' => TRUE
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users_group');
    }

    public function down()
    {
        $this->forge->dropTable('users_group');
    }
}
