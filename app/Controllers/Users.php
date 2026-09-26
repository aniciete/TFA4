<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        $users = $this->userModel->orderBy('id', 'ASC')->findAll();

        return view('users/index', [
            'title'      => 'User Accounts | POS Database',
            'activePage' => 'users',
            'users'      => $users,
        ]);
    }

    public function new(): string
    {
        return view('users/form', [
            'title'      => 'New User Account | POS Database',
            'activePage' => 'users',
            'mode'       => 'create',
            'action'     => site_url('users/new'),
            'user'       => [
                'username'  => '',
                'full_name' => '',
                'avatar'    => null,
            ],
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create(): RedirectResponse
    {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
        ];

        $avatarFile = $this->request->getFile('avatar');
        if ($avatarFile && $avatarFile->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['avatar'] = [
                'label' => 'Avatar Image',
                'rules' => [
                    'uploaded[avatar]',
                    'is_image[avatar]',
                    'mime_in[avatar,image/jpg,image/jpeg,image/png]',
                    'max_size[avatar,2048]',
                ],
                'errors' => [
                    'is_image' => 'The avatar must be a valid image file.',
                    'mime_in'  => 'The avatar must be a JPG, JPEG, or PNG image.',
                    'max_size' => 'The avatar file size must not exceed 2 MB.',
                ],
            ];
        }

        $messages = [
            'full_name' => [
                'required'   => 'Full Name is required.',
                'min_length' => 'Full Name must be at least 2 characters.',
                'max_length' => 'Full Name cannot exceed 100 characters.',
            ],
            'username' => [
                'required'   => 'Username is required.',
                'min_length' => 'Username must be at least 3 characters.',
                'max_length' => 'Username cannot exceed 50 characters.',
                'is_unique'  => 'This username is already taken. Please choose another.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatarName = null;
        if ($avatarFile && $avatarFile->isValid() && ! $avatarFile->hasMoved()) {
            $avatarName = $this->processAvatarUpload($avatarFile);
        }

        $this->userModel->insert([
            'username'   => trim((string) $this->request->getPost('username')),
            'full_name'  => trim((string) $this->request->getPost('full_name')),
            'avatar'     => $avatarName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('users'))->with('message', 'User account created successfully.');
    }

    public function edit(int|string $id): string
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound("User #{$id} not found.");
        }

        return view('users/form', [
            'title'      => 'Edit User Account | POS Database',
            'activePage' => 'users',
            'mode'       => 'edit',
            'action'     => site_url("users/edit/{$id}"),
            'user'       => $user,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update(int|string $id): RedirectResponse
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound("User #{$id} not found.");
        }

        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'username'  => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
        ];

        $avatarFile = $this->request->getFile('avatar');
        if ($avatarFile && $avatarFile->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['avatar'] = [
                'label' => 'Avatar Image',
                'rules' => [
                    'uploaded[avatar]',
                    'is_image[avatar]',
                    'mime_in[avatar,image/jpg,image/jpeg,image/png]',
                    'max_size[avatar,2048]',
                ],
                'errors' => [
                    'is_image' => 'The avatar must be a valid image file.',
                    'mime_in'  => 'The avatar must be a JPG, JPEG, or PNG image.',
                    'max_size' => 'The avatar file size must not exceed 2 MB.',
                ],
            ];
        }

        $messages = [
            'full_name' => [
                'required'   => 'Full Name is required.',
                'min_length' => 'Full Name must be at least 2 characters.',
                'max_length' => 'Full Name cannot exceed 100 characters.',
            ],
            'username' => [
                'required'   => 'Username is required.',
                'min_length' => 'Username must be at least 3 characters.',
                'max_length' => 'Username cannot exceed 50 characters.',
                'is_unique'  => 'This username is already taken. Please choose another.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($avatarFile && $avatarFile->isValid() && ! $avatarFile->hasMoved()) {
            $avatarToSave = $this->processAvatarUpload($avatarFile);
        } else {
            // Preserve existing avatar when editing without a replacement
            $avatarToSave = $user['avatar'];
        }

        $this->userModel->update($id, [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'avatar'    => $avatarToSave,
        ]);

        return redirect()->to(site_url('users'))->with('message', 'User account updated successfully.');
    }

    /**
     * Store and resize uploaded avatar to display-ready 256x256 max dimensions.
     */
    protected function processAvatarUpload(UploadedFile $file): ?string
    {
        $targetDir = FCPATH . 'uploads/avatars';

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);

        $savedFilePath = $targetDir . DIRECTORY_SEPARATOR . $newName;

        // Resize image to display-ready 256x256 maximum maintaining aspect ratio
        service('image')
            ->withFile($savedFilePath)
            ->resize(256, 256, true, 'auto')
            ->save($savedFilePath);

        return $newName;
    }
}
