<?php

namespace Tests\Feature;

use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\Tfa4Seeder;

/**
 * @internal
 */
final class AuthWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed = Tfa4Seeder::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withSession([]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testLoginPageRenders(): void
    {
        $result = $this->get('login');

        $result->assertOK();
        $result->assertSee('Staff Login');
        $result->assertSee('name="username"');
        $result->assertSee('name="password"');
    }

    public function testProtectedCustomerRouteRedirectsLoggedOutUser(): void
    {
        $result = $this->get('customers');

        $result->assertRedirectTo(site_url('login'));
        $this->assertSame('Please log in to manage accounts.', session()->getFlashdata('error'));
    }

    public function testInvalidCredentialsAreRejected(): void
    {
        $result = $this->post('login', [
            'username' => 'admin.reyes',
            'password' => 'incorrect-password',
        ]);

        $result->assertRedirect();
        $this->assertNull(session()->get('user_id'));
        $this->assertSame('Invalid username or password.', session()->getFlashdata('error'));
    }

    public function testValidCredentialsCreateSessionAndAllowProtectedAccess(): void
    {
        $result = $this->post('login', [
            'username' => 'admin.reyes',
            'password' => 'TFA4Demo!2026',
        ]);

        $result->assertRedirectTo(site_url('/'));
        $this->assertSame(1, session()->get('user_id'));
        $this->assertSame('admin.reyes', session()->get('username'));

        $protected = $this->withSession($_SESSION)->get('customers');
        $protected->assertOK();
        $protected->assertSee('Customer Accounts');
    }

    public function testSeededPasswordsAreStoredAsVerifiableHashes(): void
    {
        $user = (new UserModel())->where('username', 'admin.reyes')->first();

        $this->assertNotNull($user);
        $this->assertNotSame('TFA4Demo!2026', $user['password']);
        $this->assertTrue(password_verify('TFA4Demo!2026', $user['password']));
    }

    public function testLogoutDestroysSessionAndRedirectsToLogin(): void
    {
        $result = $this->withSession([
            'user_id'   => 1,
            'username'  => 'admin.reyes',
            'full_name' => 'Carlos Reyes',
        ])->post('logout');

        $result->assertRedirectTo(site_url('login'));
        $this->assertSame('You have been logged out.', session()->getFlashdata('message'));

        $protected = $this->withSession([])->get('customers');
        $protected->assertRedirectTo(site_url('login'));
    }
}
