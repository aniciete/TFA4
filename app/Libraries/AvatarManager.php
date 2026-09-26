<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

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
        $savedFilePath = $this->targetDir . DIRECTORY_SEPARATOR . $newName;

        if (is_uploaded_file($file->getTempName())) {
            $file->move($this->targetDir, $newName);
        } else {
            // Support non-SAPI contexts (e.g., CLI environments and programmatic tests)
            copy($file->getTempName(), $savedFilePath);
        }

        // Resize image to display-ready 256x256 maximum maintaining aspect ratio
        service('image')
            ->withFile($savedFilePath)
            ->resize(256, 256, true, 'auto')
            ->save($savedFilePath);

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

        $filePath = $this->targetDir . DIRECTORY_SEPARATOR . basename($filename);

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

        $filePath = $this->targetDir . DIRECTORY_SEPARATOR . basename($filename);

        return is_file($filePath);
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
}
