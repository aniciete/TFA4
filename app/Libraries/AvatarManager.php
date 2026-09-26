<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use Throwable;

class AvatarManager
{
    protected string $targetDir;

    public function __construct(?string $targetDir = null)
    {
        $this->targetDir = rtrim($targetDir ?? FCPATH . 'uploads/avatars', '/\\');
    }

    /**
     * Process, store, and resize an uploaded avatar image.
     */
    public function processUpload(UploadedFile $file): ?string
    {
        if ($file->getError() !== UPLOAD_ERR_OK || $file->hasMoved()) {
            return null;
        }

        if (! is_dir($this->targetDir)) {
            mkdir($this->targetDir, 0755, true);
        }

        $newName       = $file->getRandomName();
        $savedFilePath = $this->getFilePath($newName);

        if (is_uploaded_file($file->getTempName())) {
            $file->move($this->targetDir, $newName);
        } else {
            // Support non-SAPI contexts (e.g., CLI environments and programmatic tests)
            copy($file->getTempName(), $savedFilePath);
        }

        // Resize image to display-ready 256x256 maximum maintaining aspect ratio
        try {
            service('image')
                ->withFile($savedFilePath)
                ->resize(256, 256, true, 'auto')
                ->save($savedFilePath);
        } catch (Throwable $e) {
            // Clean up partial/unresized file on image processing failure
            @unlink($savedFilePath);

            return null;
        }

        return $newName;
    }

    /**
     * Delete an existing avatar file from disk.
     */
    public function deleteAvatar(?string $filename): bool
    {
        if (empty($filename)) {
            return false;
        }

        $filePath = $this->getFilePath($filename);

        if (is_file($filePath)) {
            return @unlink($filePath);
        }

        return false;
    }

    /**
     * Check if a given avatar file exists on disk.
     */
    public function avatarExists(?string $filename): bool
    {
        if (empty($filename)) {
            return false;
        }

        return is_file($this->getFilePath($filename));
    }

    /**
     * Resolve the public URL for an avatar, falling back to placeholder if missing.
     */
    public function getAvatarUrl(?string $filename): string
    {
        if ($this->avatarExists($filename)) {
            return base_url('uploads/avatars/' . $filename);
        }

        return base_url('assets/images/avatar-placeholder.svg');
    }

    /**
     * Hydrate a user record with avatar presentation data.
     *
     * @param array<string, mixed> $user
     * @return array<string, mixed>
     */
    public function prepareUserAvatar(array $user): array
    {
        $user['has_avatar'] = $this->avatarExists($user['avatar'] ?? null);
        $user['avatar_url'] = $this->getAvatarUrl($user['avatar'] ?? null);

        return $user;
    }

    /**
     * Build absolute filesystem path to an avatar image.
     */
    protected function getFilePath(string $filename): string
    {
        return $this->targetDir . DIRECTORY_SEPARATOR . basename($filename);
    }
}
