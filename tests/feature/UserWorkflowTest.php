<?php

namespace Tests\Feature;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\Tfa3Seeder;

/**
 * @internal
 */
final class UserWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed = Tfa3Seeder::class;

    protected function tearDown(): void
    {
        parent::tearDown();
        // Reset superglobals files array
        service('superglobals')->setFilesArray([]);
        $_FILES = [];
    }

    public function testUsersIndexDisplaysNewButtonAvatarPlaceholderAndEditLinks(): void
    {
        $result = $this->get('users');

        $result->assertOK();
        $result->assertSee('New User');
        $result->assertSee('users/new');
        $result->assertSee('avatar-placeholder.svg');
        $result->assertSee('users/edit/1');
        $result->assertSee('Edit');
    }

    public function testNewUserFormRendersProperly(): void
    {
        $result = $this->get('users/new');

        $result->assertOK();
        $result->assertSee('New User Account');
        $result->assertSee('User Account Setup');
        $result->assertSee('Username');
        $result->assertSee('Full Name');
        $result->assertSee('Profile Avatar');
        $result->assertSee('Save User');
    }

    public function testCreateUserFailsValidationWhenRequiredFieldsMissing(): void
    {
        $result = $this->post('users/new', [
            'username'  => '',
            'full_name' => '',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
        $errors = session()->getFlashdata('errors') ?? [];
        $this->assertArrayHasKey('username', $errors);
        $this->assertArrayHasKey('full_name', $errors);
    }

    public function testCreateUserFailsValidationOnDuplicateUsername(): void
    {
        $result = $this->post('users/new', [
            'username'  => 'admin.reyes', // Already exists in seed
            'full_name' => 'Another Admin',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
        $errors = session()->getFlashdata('errors') ?? [];
        $this->assertArrayHasKey('username', $errors);
    }

    public function testCreateUserSuccessWithoutAvatar(): void
    {
        $userModel    = new UserModel();
        $initialCount = $userModel->countAllResults();

        $result = $this->post('users/new', [
            'username'  => 'sarah.connor',
            'full_name' => 'Sarah Connor',
        ]);

        $result->assertRedirectTo(site_url('users'));
        $this->assertSame($initialCount + 1, $userModel->countAllResults());

        $user = $userModel->where('username', 'sarah.connor')->first();
        $this->assertNotNull($user);
        $this->assertSame('Sarah Connor', $user['full_name']);
        $this->assertNull($user['avatar']);
        $this->assertNotEmpty($user['created_at']);
    }

    public function testEditUserFormPrefillsExistingRecordAndDisplaysPlaceholderWhenNoAvatar(): void
    {
        $result = $this->get('users/edit/1');

        $result->assertOK();
        $result->assertSee('Edit User Account');
        $result->assertSee('Carlos Reyes');
        $result->assertSee('admin.reyes');
        $result->assertSee('avatar-placeholder.svg');
        $result->assertSee('Update User');
    }

    public function testEditUserThrows404ForNonExistentId(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->get('users/edit/9999');
    }

    public function testUpdateUserThrows404ForNonExistentId(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->post('users/edit/9999', [
            'username'  => 'ghost.user',
            'full_name' => 'Ghost User',
        ]);
    }

    public function testUpdateUserSuccessPreservesExistingUsernameAndCreatedAt(): void
    {
        $userModel = new UserModel();
        $original  = $userModel->find(1);
        $this->assertNotNull($original);
        $originalCreatedAt = $original['created_at'];

        // Submitting same username 'admin.reyes' should pass unique rule with ignore ID
        $result = $this->post('users/edit/1', [
            'username'  => 'admin.reyes',
            'full_name' => 'Carlos Reyes Updated',
        ]);

        $result->assertRedirectTo(site_url('users'));

        $updated = $userModel->find(1);
        $this->assertSame('Carlos Reyes Updated', $updated['full_name']);
        $this->assertSame('admin.reyes', $updated['username']);
        $this->assertSame($originalCreatedAt, $updated['created_at'], 'Original created_at must be preserved.');
    }

    public function testCreateUserWithValidAvatarUploadResizesAndSavesImage(): void
    {
        // Generate a 400x300 valid PNG test image
        $tmpImage = tempnam(sys_get_temp_dir(), 'avatar_test') . '.png';
        $gd       = imagecreatetruecolor(400, 300);
        $bg       = imagecolorallocate($gd, 40, 120, 80);
        imagefilledrectangle($gd, 0, 0, 400, 300, $bg);
        imagepng($gd, $tmpImage);
        imagedestroy($gd);

        $fileSize = filesize($tmpImage);

        $files = [
            'avatar' => [
                'name'     => 'test_avatar.png',
                'type'     => 'image/png',
                'size'     => $fileSize,
                'tmp_name' => $tmpImage,
                'error'    => UPLOAD_ERR_OK,
            ],
        ];
        service('superglobals')->setFilesArray($files);

        $result = $this->post('users/new', [
            'username'  => 'avatar.operator',
            'full_name' => 'Avatar Operator',
        ]);

        $result->assertRedirectTo(site_url('users'));

        $userModel = new UserModel();
        $user      = $userModel->where('username', 'avatar.operator')->first();
        $this->assertNotNull($user);
        $this->assertNotEmpty($user['avatar']);

        // Verify generated avatar filename is not the original client name
        $this->assertNotSame('test_avatar.png', $user['avatar']);

        // Verify file exists on disk in public/uploads/avatars/
        $savedFile = FCPATH . 'uploads/avatars/' . $user['avatar'];
        $this->assertFileExists($savedFile);

        // Verify image dimensions were resized to max 256x256
        $sizeInfo = getimagesize($savedFile);
        $this->assertNotFalse($sizeInfo);
        $this->assertLessThanOrEqual(256, $sizeInfo[0], 'Width must be <= 256px');
        $this->assertLessThanOrEqual(256, $sizeInfo[1], 'Height must be <= 256px');

        // Verify listing page renders the uploaded avatar
        $listing = $this->get('users');
        $listing->assertSee('uploads/avatars/' . $user['avatar']);

        // Clean up
        @unlink($tmpImage);
        @unlink($savedFile);
    }

    public function testCreateUserRejectsNonImageFileWithoutDatabaseChanges(): void
    {
        $tmpText = tempnam(sys_get_temp_dir(), 'text_test') . '.txt';
        file_put_contents($tmpText, 'Plain text content that is not an image file.');

        $files = [
            'avatar' => [
                'name'     => 'document.txt',
                'type'     => 'text/plain',
                'size'     => filesize($tmpText),
                'tmp_name' => $tmpText,
                'error'    => UPLOAD_ERR_OK,
            ],
        ];
        service('superglobals')->setFilesArray($files);

        $result = $this->post('users/new', [
            'username'  => 'text.user',
            'full_name' => 'Text File User',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
        $errors = session()->getFlashdata('errors') ?? [];
        $this->assertArrayHasKey('avatar', $errors);

        $userModel = new UserModel();
        $this->assertNull($userModel->where('username', 'text.user')->first());

        @unlink($tmpText);
    }

    public function testCreateUserRejectsFileOver2MBWithoutDatabaseChanges(): void
    {
        $tmpLarge = tempnam(sys_get_temp_dir(), 'large_test') . '.png';
        file_put_contents($tmpLarge, 'fake image data');

        $files = [
            'avatar' => [
                'name'     => 'huge_image.png',
                'type'     => 'image/png',
                'size'     => 2097153 + 1000, // Greater than 2 MB (2048 KB = 2097152 bytes)
                'tmp_name' => $tmpLarge,
                'error'    => UPLOAD_ERR_OK,
            ],
        ];
        service('superglobals')->setFilesArray($files);

        $result = $this->post('users/new', [
            'username'  => 'huge.user',
            'full_name' => 'Huge File User',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
        $errors = session()->getFlashdata('errors') ?? [];
        $this->assertArrayHasKey('avatar', $errors);

        $userModel = new UserModel();
        $this->assertNull($userModel->where('username', 'huge.user')->first());

        @unlink($tmpLarge);
    }

    public function testUpdateUserPreservesExistingAvatarWhenNoReplacementSubmitted(): void
    {
        $userModel = new UserModel();
        $userModel->update(1, ['avatar' => 'preserved_avatar_123.jpg']);

        // Explicitly set no file uploaded
        $files = [
            'avatar' => [
                'name'     => '',
                'type'     => '',
                'size'     => 0,
                'tmp_name' => '',
                'error'    => UPLOAD_ERR_NO_FILE,
            ],
        ];
        service('superglobals')->setFilesArray($files);

        $result = $this->post('users/edit/1', [
            'username'  => 'admin.reyes',
            'full_name' => 'Carlos Reyes Renamed',
        ]);

        $result->assertRedirectTo(site_url('users'));

        $updated = $userModel->find(1);
        $this->assertSame('preserved_avatar_123.jpg', $updated['avatar'], 'Existing avatar must be preserved when no replacement submitted.');

        // Verify edit page displays the preserved avatar preview
        $editPage = $this->get('users/edit/1');
        $editPage->assertSee('preserved_avatar_123.jpg');
    }
}
