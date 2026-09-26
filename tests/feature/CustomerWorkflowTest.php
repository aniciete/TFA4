<?php

namespace Tests\Feature;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\Tfa3Seeder;

/**
 * @internal
 */
final class CustomerWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed = Tfa3Seeder::class;

    public function testCustomersIndexDisplaysNewButtonAndEditLinks(): void
    {
        $result = $this->get('customers');

        $result->assertOK();
        $result->assertSee('New Customer');
        $result->assertSee('customers/new');
        $result->assertSee('customers/edit/1');
        $result->assertSee('Edit');
    }

    public function testNewCustomerFormRendersProperly(): void
    {
        $result = $this->get('customers/new');

        $result->assertOK();
        $result->assertSee('New Customer Account');
        $result->assertSee('Customer Profile Form');
        $result->assertSee('Full Name');
        $result->assertSee('Email Address');
        $result->assertSee('Phone Number');
        $result->assertSee('Save Customer');
    }

    public function testCreateCustomerFailsValidationWhenRequiredFieldsMissing(): void
    {
        $result = $this->post('customers/new', [
            'full_name' => '',
            'email'     => '',
            'phone'     => '',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
        $errors = session()->getFlashdata('errors') ?? [];
        $this->assertArrayHasKey('full_name', $errors);
        $this->assertArrayHasKey('email', $errors);
    }

    public function testCreateCustomerFailsValidationOnInvalidEmail(): void
    {
        $result = $this->post('customers/new', [
            'full_name' => 'Valid Name',
            'email'     => 'not-a-valid-email-format',
            'phone'     => '12345',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
        $errors = session()->getFlashdata('errors') ?? [];
        $this->assertArrayHasKey('email', $errors);
    }

    public function testCreateCustomerSuccessPersistsRecordAndSetsCreatedAt(): void
    {
        $customerModel = new CustomerModel();
        $initialCount  = $customerModel->countAllResults();

        $result = $this->post('customers/new', [
            'full_name' => 'Amelia Clarke',
            'email'     => 'amelia.clarke@example.com',
            'phone'     => '+1 (555) 999-0000',
        ]);

        $result->assertRedirectTo(site_url('customers'));

        $this->assertSame($initialCount + 1, $customerModel->countAllResults());

        $inserted = $customerModel->where('email', 'amelia.clarke@example.com')->first();
        $this->assertNotNull($inserted);
        $this->assertSame('Amelia Clarke', $inserted['full_name']);
        $this->assertSame('+1 (555) 999-0000', $inserted['phone']);
        $this->assertNotEmpty($inserted['created_at']);
    }

    public function testEditCustomerFormPrefillsExistingRecord(): void
    {
        $result = $this->get('customers/edit/1');

        $result->assertOK();
        $result->assertSee('Edit Customer Account');
        $result->assertSee('Elena Rostova');
        $result->assertSee('elena.rostova@example.com');
        $result->assertSee('+1 (555) 234-5678');
        $result->assertSee('Update Customer');
    }

    public function testEditCustomerThrows404ForNonExistentId(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->get('customers/edit/9999');
    }

    public function testUpdateCustomerThrows404ForNonExistentId(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->post('customers/edit/9999', [
            'full_name' => 'Ghost Customer',
            'email'     => 'ghost@example.com',
        ]);
    }

    public function testUpdateCustomerSuccessUpdatesRecordAndPreservesCreatedAt(): void
    {
        $customerModel = new CustomerModel();
        $original      = $customerModel->find(1);
        $this->assertNotNull($original);
        $originalCreatedAt = $original['created_at'];

        $result = $this->post('customers/edit/1', [
            'full_name' => 'Elena Rostova Updated',
            'email'     => 'elena.updated@example.com',
            'phone'     => '+1 (555) 111-2222',
        ]);

        $result->assertRedirectTo(site_url('customers'));

        $updated = $customerModel->find(1);
        $this->assertSame('Elena Rostova Updated', $updated['full_name']);
        $this->assertSame('elena.updated@example.com', $updated['email']);
        $this->assertSame('+1 (555) 111-2222', $updated['phone']);
        $this->assertSame($originalCreatedAt, $updated['created_at'], 'Original created_at must be preserved on update.');
    }

    public function testUpdateCustomerValidationFailsOnEmptyFields(): void
    {
        $result = $this->post('customers/edit/1', [
            'full_name' => '',
            'email'     => 'invalid-email',
            'phone'     => '',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');

        $customerModel = new CustomerModel();
        $unchanged     = $customerModel->find(1);
        $this->assertSame('Elena Rostova', $unchanged['full_name']);
    }
}
