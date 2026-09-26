<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Customers extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index(): string
    {
        $customers = $this->customerModel->orderBy('id', 'ASC')->findAll();

        return view('customers/index', [
            'title'      => 'Customer Accounts | POS Database',
            'activePage' => 'customers',
            'customers'  => $customers,
        ]);
    }

    public function new(): string
    {
        return view('customers/form', [
            'title'      => 'New Customer Account | POS Database',
            'activePage' => 'customers',
            'mode'       => 'create',
            'action'     => site_url('customers/new'),
            'customer'   => [
                'full_name' => '',
                'email'     => '',
                'phone'     => '',
            ],
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create(): RedirectResponse
    {
        $rules    = $this->customerModel->getValidationRules();
        $messages = $this->customerModel->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->insert([
            'full_name'  => trim((string) $this->request->getPost('full_name')),
            'email'      => trim((string) $this->request->getPost('email')),
            'phone'      => trim((string) $this->request->getPost('phone')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('customers'))->with('message', 'Customer account created successfully.');
    }

    public function edit(int|string $id): string
    {
        $customer = $this->customerModel->find($id);

        if (! $customer) {
            throw PageNotFoundException::forPageNotFound("Customer #{$id} not found.");
        }

        return view('customers/form', [
            'title'      => 'Edit Customer Account | POS Database',
            'activePage' => 'customers',
            'mode'       => 'edit',
            'action'     => site_url("customers/edit/{$id}"),
            'customer'   => $customer,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update(int|string $id): RedirectResponse
    {
        $customer = $this->customerModel->find($id);

        if (! $customer) {
            throw PageNotFoundException::forPageNotFound("Customer #{$id} not found.");
        }

        $rules    = $this->customerModel->getValidationRules();
        $messages = $this->customerModel->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->update($id, [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
            'phone'     => trim((string) $this->request->getPost('phone')),
        ]);

        return redirect()->to(site_url('customers'))->with('message', 'Customer account updated successfully.');
    }
}
