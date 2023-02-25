<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
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
                'unsigned' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'unique' => TRUE,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'unique' => TRUE,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'address2' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'city_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'country_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'state_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'zip' => [
                'type' => 'INT',
                'constraint' => 10,
                'null' => TRUE,
            ],
            'enabled' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 1,
            ],
            'activated' => [
                'type' => 'INT',
                'constraint' => 5,
                'default'=> 1
            ],
            'activation_code' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'last_login' => [
                'type' => 'datetime',
                'null' => TRUE
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
        //$this->forge->addForeignKey('user_group_id', 'user_group', 'id', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('branch_id', 'branch', 'id', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('country_id', 'country', 'id', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('state_id', 'state', 'id', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('city_id', 'city', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
