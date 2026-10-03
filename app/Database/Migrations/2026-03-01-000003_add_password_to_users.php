<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'username',
            ],
        ]);

        $demoHash = password_hash('TFA4Demo!2026', PASSWORD_DEFAULT);
        $this->db->table('users')->where('password', null)->update(['password' => $demoHash]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'password');
    }
}
