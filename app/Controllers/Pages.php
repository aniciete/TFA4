<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\UserModel;

class Pages extends BaseController
{
    public function home(): string
    {
        $customerCount = (new CustomerModel())->countAllResults();
        $userCount     = (new UserModel())->countAllResults();

        return view('pages/home', [
            'title'         => 'Home | POS Database',
            'activePage'    => 'home',
            'customerCount' => $customerCount,
            'userCount'     => $userCount,
        ]);
    }

    public function about(): string
    {
        return view('pages/about', [
            'title'      => 'About | POS Database',
            'activePage' => 'about',
        ]);
    }
}
