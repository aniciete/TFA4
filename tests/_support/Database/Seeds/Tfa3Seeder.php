<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Tfa3Seeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('customers')->truncate();
        $this->db->table('users')->truncate();

        $customers = [
            [
                'id'         => 1,
                'full_name'  => 'Elena Rostova',
                'email'      => 'elena.rostova@example.com',
                'phone'      => '+1 (555) 234-5678',
                'created_at' => '2026-03-01 08:30:00',
            ],
            [
                'id'         => 2,
                'full_name'  => 'Marcus Vance',
                'email'      => 'marcus.vance@example.com',
                'phone'      => '+1 (555) 345-6789',
                'created_at' => '2026-03-02 09:15:00',
            ],
            [
                'id'         => 3,
                'full_name'  => 'Aria Thorne',
                'email'      => 'aria.thorne@example.com',
                'phone'      => '+1 (555) 456-7890',
                'created_at' => '2026-03-03 10:45:00',
            ],
            [
                'id'         => 4,
                'full_name'  => 'Julian Mercer',
                'email'      => 'julian.mercer@example.com',
                'phone'      => '+1 (555) 567-8901',
                'created_at' => '2026-03-04 14:20:00',
            ],
            [
                'id'         => 5,
                'full_name'  => 'Sophia Lin',
                'email'      => 'sophia.lin@example.com',
                'phone'      => '+1 (555) 678-9012',
                'created_at' => '2026-03-05 16:05:00',
            ],
        ];

        $users = [
            [
                'id'         => 1,
                'username'   => 'admin.reyes',
                'full_name'  => 'Carlos Reyes',
                'created_at' => '2026-01-15 08:00:00',
            ],
            [
                'id'         => 2,
                'username'   => 'mgr.castro',
                'full_name'  => 'Beatriz Castro',
                'created_at' => '2026-01-20 08:30:00',
            ],
            [
                'id'         => 3,
                'username'   => 'cashier.valdez',
                'full_name'  => 'Daniel Valdez',
                'created_at' => '2026-02-01 09:00:00',
            ],
            [
                'id'         => 4,
                'username'   => 'cashier.santos',
                'full_name'  => 'Camille Santos',
                'created_at' => '2026-02-01 09:15:00',
            ],
            [
                'id'         => 5,
                'username'   => 'inv.navarro',
                'full_name'  => 'Leo Navarro',
                'created_at' => '2026-02-10 10:00:00',
            ],
        ];

        $this->db->table('customers')->insertBatch($customers);
        $this->db->table('users')->insertBatch($users);
    }
}
