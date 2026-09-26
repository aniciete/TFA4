<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    public function index(): string
    {
        $customerModel = new CustomerModel();
        $customers     = $customerModel->orderBy('id', 'ASC')->findAll();

        return view('customers/index', [
            'title'      => 'Customer Accounts | POS Database',
            'activePage' => 'customers',
            'customers'  => $customers,
        ]);
    }
}
