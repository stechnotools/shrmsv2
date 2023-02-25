<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserGroupPermission extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_group_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => FALSE,
            ],
            'permission_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => FALSE,
            ]
        ]);
        //$this->forge->addForeignKey('user_group_id', 'users_group', 'id', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('permission_id', 'permission', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users_group_permission');
    }

    public function down()
    {
        $this->forge->dropTable('users_group_permission');
    }
}
