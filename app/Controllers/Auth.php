<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login(): string|RedirectResponse
    {
        if (session()->get('user_id') !== null) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/login', [
            'title'  => 'Staff Login | POS Database',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function attempt(): RedirectResponse
    {
        $rules = [
            'username' => [
                'label'  => 'Username',
                'rules'  => 'required|min_length[3]|max_length[50]',
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        $user     = $this->userModel->where('username', $username)->first();

        if (! $user || ! password_verify($password, (string) ($user['password'] ?? ''))) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }

        $session = session();
        $session->regenerate(true);
        $session->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
        ]);

        return redirect()->to(site_url('/'))->with('message', 'Welcome back, ' . $user['full_name'] . '.');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to(site_url('login'))->with('message', 'You have been logged out.');
    }
}
