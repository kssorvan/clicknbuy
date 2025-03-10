<?php

namespace Core\Services;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class ImageUploadService
{
    private $cloudinary;
    private $uploadApi;

    public function __construct($config)
    {
        try {
            $cloudName = $config['cloud_name']  ?? '';
            $apiKey = $config['api_key']  ?? '';
            $apiSecret = $config['api_secret']  ?? '';

            $configuration = new Configuration([
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            $this->cloudinary = new Cloudinary($configuration);
            $this->uploadApi = new UploadApi($configuration);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to initialize Cloudinary: ' . $e->getMessage());
        }
    }

    public function validateImageFile($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \InvalidArgumentException('No file uploaded or upload error occurred');
        }

        $maxFileSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxFileSize) {
            throw new \InvalidArgumentException("File too large. Maximum {$maxFileSize} bytes allowed.");
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPEG, PNG, and WebP are allowed.');
        }
    }

    public function uploadImage($filePath, $options = [])
    {
        $defaultOptions = [
            'folder' => 'products',
            'overwrite' => true,
            'resource_type' => 'image',
            'transformation' => [
                ['width' => 800, 'height' => 600, 'crop' => 'limit']
            ]
        ];

        $uploadOptions = array_merge($defaultOptions, $options);

        try {
            $uploadResult = $this->uploadApi->upload($filePath, $uploadOptions);
            return $uploadResult;
        } catch (\Exception $e) {
            throw new \RuntimeException(
                'Image upload to Cloudinary failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}