<?php

namespace Tests\Feature;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\Tfa3Seeder;

/**
 * @internal
 */
final class PosFoundationsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed = Tfa3Seeder::class;

    public function testHomePageReturnsOkAndDisplaysElements(): void
    {
        $result = $this->get('/');

        $result->assertOK();
        $result->assertSee('Point-of-Sale Database');
        $result->assertSee('Customer Accounts');
        $result->assertSee('User Accounts');
        $result->assertSee('About');
        $result->assertSee('aria-current="page"');

        // Extended assertions for Bright-Market layout and landmarks
        $result->assertSee('Skip to main content');
        $result->assertSee('href="#main-content"');
        $result->assertSee('role="banner"');
        $result->assertSee('role="main"');
        $result->assertSee('role="contentinfo"');
        $result->assertSee('01');
        $result->assertSee('Registered Customers');
        $result->assertSee('assets/css/style.css');
        $result->assertSee('favicon.svg');
    }

    public function testAboutPageReturnsOkAndDisplaysMvcFlow(): void
    {
        $result = $this->get('about');

        $result->assertOK();
        $result->assertSee('About POS Database');
        $result->assertSee('Counter Service &amp; Transaction Flow');
        $result->assertSee('Relational Database &amp; Models');
        $result->assertSee('DATABASE ARCHITECTURE');
        $result->assertSee('CustomerModel:');
        $result->assertSee('UserModel:');
        $result->assertSee('aria-current="page"');

        // Extended assertions for landmarks and connected 4-step flow
        $result->assertSee('Skip to main content');
        $result->assertSee('02');
        $result->assertSee('1. Customer Identification');
        $result->assertSee('2. Item &amp; Order Verification');
        $result->assertSee('3. Staff Processing');
        $result->assertSee('4. Sale Completion &amp; Receipt');
        $result->assertSee('MODEL PERSISTENCE PROTOCOL');
    }

    public function testCustomersPageReturnsOkAndDisplaysRecords(): void
    {
        $result = $this->get('customers');

        $result->assertOK();
        $result->assertSee('Customer Accounts');
        $result->assertSee('Total Records');
        $result->assertSee('Elena Rostova');
        $result->assertSee('elena.rostova@example.com');
        $result->assertSee('+1 (555) 234-5678');
        $result->assertSee('Marcus Vance');
        $result->assertSee('Aria Thorne');
        $result->assertSee('Julian Mercer');
        $result->assertSee('Sophia Lin');
        $result->assertSee('<table class="data-table">');
        $result->assertSee('scope="col"');

        // Extended assertions for responsive data-labels and record count
        $result->assertSee('Skip to main content');
        $result->assertSee('aria-current="page"');
        $result->assertSee('data-label="Full Name"');
        $result->assertSee('data-label="Email Address"');
        $result->assertSee('data-label="Phone Number"');
        $result->assertSee('data-label="#"');
        $result->assertSee('stat-giant');
    }

    public function testUsersPageReturnsOkAndDisplaysStaffRecords(): void
    {
        $result = $this->get('users');

        $result->assertOK();
        $result->assertSee('User Accounts');
        $result->assertSee('Total Users');
        $result->assertSee('admin.reyes');
        $result->assertSee('Carlos Reyes');
        $result->assertSee('2026-01-15 08:00:00');
        $result->assertSee('mgr.castro');
        $result->assertSee('Beatriz Castro');
        $result->assertSee('cashier.valdez');
        $result->assertSee('cashier.santos');
        $result->assertSee('inv.navarro');
        $result->assertSee('<table class="data-table">');
        $result->assertSee('scope="col"');

        // Assert role is NOT displayed
        $result->assertDontSee('System Role');
        $result->assertDontSee('Store Administrator');

        // Extended assertions for responsive data-labels and created_at
        $result->assertSee('Skip to main content');
        $result->assertSee('aria-current="page"');
        $result->assertSee('data-label="Username"');
        $result->assertSee('data-label="Full Name"');
        $result->assertSee('data-label="Created At"');
        $result->assertSee('stat-giant');
    }

    public function testLocalAssetsAndFontsExist(): void
    {
        $publicPath = rtrim(PUBLICPATH, '/\\') . '/';
        $this->assertFileExists($publicPath . 'assets/css/style.css');
        $this->assertFileExists($publicPath . 'favicon.svg');
        $this->assertFileExists($publicPath . 'assets/fonts/bricolage-grotesque-latin.woff2');
        $this->assertFileExists($publicPath . 'assets/fonts/ibm-plex-mono-400.woff2');
        $this->assertFileExists($publicPath . 'assets/fonts/ibm-plex-mono-500.woff2');
        $this->assertFileExists($publicPath . 'assets/fonts/ibm-plex-mono-600.woff2');
    }

    public function testUndefinedRouteThrowsPageNotFound(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->get('non-existent-pos-route-404');
    }
}
