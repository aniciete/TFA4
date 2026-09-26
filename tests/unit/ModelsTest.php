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
            $this->assertArrayHasKey('avatar', $user);
            $this->assertArrayHasKey('created_at', $user);
            $this->assertArrayNotHasKey('role', $user);
        }
    }

    public function testCustomerModelValidationFailsOnInvalidEmailAndMissingName(): void
    {
        $customerModel = new CustomerModel();

        // Missing name and invalid email
        $result = $customerModel->validate([
            'full_name' => '',
            'email'     => 'not-an-email',
            'phone'     => '123',
        ]);

        $this->assertFalse($result);
        $errors = $customerModel->errors();
        $this->assertArrayHasKey('full_name', $errors);
        $this->assertArrayHasKey('email', $errors);
    }

    public function testUserModelValidationFailsOnDuplicateUsername(): void
    {
        $userModel = new UserModel();

        // Duplicate username 'admin.reyes' already seeded
        $result = $userModel->validate([
            'username'  => 'admin.reyes',
            'full_name' => 'Duplicate Admin',
        ]);

        $this->assertFalse($result);
        $errors = $userModel->errors();
        $this->assertArrayHasKey('username', $errors);
    }

    public function testUserModelUpdateAllowsSameUsernameForOwnRecord(): void
    {
        $userModel = new UserModel();

        // User #1 is admin.reyes. Updating user #1 with same username should succeed.
        $success = $userModel->update(1, [
            'username'  => 'admin.reyes',
            'full_name' => 'Carlos Reyes Modified',
        ]);

        $this->assertTrue($success);
        $updated = $userModel->find(1);
        $this->assertSame('Carlos Reyes Modified', $updated['full_name']);
        $this->assertSame('admin.reyes', $updated['username']);
    }

    public function testUserModelUpdateRejectsDuplicateUsernameFromOtherRecord(): void
    {
        $userModel = new UserModel();

        // User #2 is cashier.delacruz. Updating user #2 with user #1's username should fail.
        $success = $userModel->update(2, [
            'username'  => 'admin.reyes',
            'full_name' => 'Maria Dela Cruz',
        ]);

        $this->assertFalse($success);
    }
}


