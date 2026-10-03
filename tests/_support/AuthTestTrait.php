<?php

namespace Tests\Support;

trait AuthTestTrait
{
    protected function loginAsAdmin(): void
    {
        $this->withSession([
            'user_id'   => 1,
            'username'  => 'admin.reyes',
            'full_name' => 'Carlos Reyes',
        ]);
    }
}
