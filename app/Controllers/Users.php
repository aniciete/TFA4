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
        $users         = $this->userModel->orderBy('id', 'ASC')->findAll();
        $avatarManager = service('avatarManager');

        foreach ($users as &$user) {
            $user = $avatarManager->prepareUserAvatar($user);
        }
        unset($user);

        return view('users/index', [
            'title'      => 'User Accounts | POS Database',
            'activePage' => 'users',
            'users'      => $users,
        ]);
    }

    public function new(): string
    {
        $user = service('avatarManager')->prepareUserAvatar([
            'username'  => '',
            'full_name' => '',
            'avatar'    => null,
        ]);

        return view('users/form', [
            'title'      => 'New User Account | POS Database',
            'activePage' => 'users',
            'mode'       => 'create',
            'action'     => site_url('users/new'),
            'user'       => $user,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create(): RedirectResponse
    {
        $rules = [
            'full_name' => $this->userModel->getValidationRules()['full_name'],
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
        ];

        $avatarFile = $this->request->getFile('avatar');
        $rules      = $this->attachAvatarRules($rules, $avatarFile);
        $messages   = $this->userModel->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatarName = null;
        if ($avatarFile && $avatarFile->getError() === UPLOAD_ERR_OK && ! $avatarFile->hasMoved()) {
            $avatarName = service('avatarManager')->processUpload($avatarFile);
        }

        $inserted = $this->userModel->insert([
            'username'   => trim((string) $this->request->getPost('username')),
            'full_name'  => trim((string) $this->request->getPost('full_name')),
            'avatar'     => $avatarName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (! $inserted) {
            if ($avatarName) {
                service('avatarManager')->deleteAvatar($avatarName);
            }

            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return redirect()->to(site_url('users'))->with('message', 'User account created successfully.');
    }

    public function edit(int|string $id): string
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound("User #{$id} not found.");
        }

        $user = service('avatarManager')->prepareUserAvatar($user);

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
            'full_name' => $this->userModel->getValidationRules()['full_name'],
            'username'  => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
        ];

        $avatarFile = $this->request->getFile('avatar');
        $rules      = $this->attachAvatarRules($rules, $avatarFile);
        $messages   = $this->userModel->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatarManager = service('avatarManager');
        $newAvatar     = null;
        if ($avatarFile && $avatarFile->getError() === UPLOAD_ERR_OK && ! $avatarFile->hasMoved()) {
            $newAvatar    = $avatarManager->processUpload($avatarFile);
            $avatarToSave = $newAvatar;
        } else {
            // Preserve existing avatar when editing without a replacement
            $avatarToSave = $user['avatar'];
        }

        $updated = $this->userModel->update($id, [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'avatar'    => $avatarToSave,
        ]);

        if (! $updated) {
            if ($newAvatar) {
                $avatarManager->deleteAvatar($newAvatar);
            }

            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        // Unlink old avatar only after database update succeeds to avoid premature deletion
        if ($newAvatar !== null && ! empty($user['avatar'])) {
            $avatarManager->deleteAvatar($user['avatar']);
        }

        return redirect()->to(site_url('users'))->with('message', 'User account updated successfully.');
    }

    /**
     * Conditionally attach avatar upload validation rules if a file is present.
     *
     * @param array<string, mixed> $rules
     * @return array<string, mixed>
     */
    protected function attachAvatarRules(array $rules, ?UploadedFile $avatarFile): array
    {
        if ($avatarFile && $avatarFile->getError() !== UPLOAD_ERR_NO_FILE) {
            return array_merge($rules, $this->getAvatarValidationRules());
        }

        return $rules;
    }

    /**
     * Shared validation rules and messages for avatar file uploads.
     */
    protected function getAvatarValidationRules(): array
    {
        return [
            'avatar' => [
                'label'  => 'Avatar Image',
                'rules'  => [
                    'uploaded[avatar]',
                    'is_image[avatar]',
                    'mime_in[avatar,image/jpg,image/jpeg,image/png]',
                    'max_size[avatar,2048]',
                ],
                'errors' => [
                    'uploaded' => 'Please select a valid image file to upload.',
                    'is_image' => 'The avatar must be a valid image file.',
                    'mime_in'  => 'The avatar must be a JPG, JPEG, or PNG image.',
                    'max_size' => 'The avatar file size must not exceed 2 MB.',
                ],
            ],
        ];
    }
}
