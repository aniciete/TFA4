<?php

namespace Tests\Unit;

use App\Models\CustomerModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Database\Seeds\Tfa3Seeder;

/**
 * @internal
 */
final class ModelsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = Tfa3Seeder::class;

    public function testCustomerModelFindAllReturnsFiveRecordsWithSchemaFields(): void
    {
        $customerModel = new CustomerModel();
        $customers     = $customerModel->orderBy('id', 'ASC')->findAll();

        $this->assertCount(5, $customers);
        $this->assertSame('Elena Rostova', $customers[0]['full_name']);
        $this->assertSame('elena.rostova@example.com', $customers[0]['email']);
        $this->assertSame('+1 (555) 234-5678', $customers[0]['phone']);
        $this->assertSame('2026-03-01 08:30:00', $customers[0]['created_at']);

        // Verify keys across all records
        foreach ($customers as $customer) {
            $this->assertArrayHasKey('id', $customer);
            $this->assertArrayHasKey('full_name', $customer);
            $this->assertArrayHasKey('email', $customer);
            $this->assertArrayHasKey('phone', $customer);
            $this->assertArrayHasKey('created_at', $customer);
        }
    }

    public function testUserModelFindAllReturnsFiveRecordsWithSchemaFieldsAndNoRole(): void
    {
        $userModel = new UserModel();
        $users     = $userModel->orderBy('id', 'ASC')->findAll();

        $this->assertCount(5, $users);
        $this->assertSame('admin.reyes', $users[0]['username']);
        $this->assertSame('Carlos Reyes', $users[0]['full_name']);
        $this->assertSame('2026-01-15 08:00:00', $users[0]['created_at']);

        // Verify keys across all records and confirm role is absent
        foreach ($users as $user) {
            $this->assertArrayHasKey('id', $user);
            $this->assertArrayHasKey('username', $user);
            $this->assertArrayHasKey('full_name', $user);
            $this->assertArrayHasKey('created_at', $user);
            $this->assertArrayNotHasKey('role', $user);
        }
    }
}
