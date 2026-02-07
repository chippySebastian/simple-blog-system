<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * ImageUploadService
 * 
 * Handles image uploads with validation, thumbnail generation, and secure storage
 */
class ImageUploadService
{
    private $manager;
    // Maximum file size in bytes (5MB)
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;
    
    // Allowed image types
    private const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    
    // Allowed extensions
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // Thumbnail dimensions
    private const THUMBNAIL_WIDTH = 300;
    private const THUMBNAIL_HEIGHT = 200;
    
    // Medium size dimensions
    private const MEDIUM_WIDTH = 800;
    private const MEDIUM_HEIGHT = 600;
    
    private $uploadPath;
    private $originalPath;
    private $thumbnailPath;
    private $mediumPath;
    
    public function __construct()
    {
        // Initialize Intervention Image v3 with GD driver
        $this->manager = new ImageManager(new Driver());
        
        // Use storage directory outside of public web root
        $this->uploadPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images';
        $this->originalPath = $this->uploadPath . DIRECTORY_SEPARATOR . 'original';
        $this->thumbnailPath = $this->uploadPath . DIRECTORY_SEPARATOR . 'thumbnails';
        $this->mediumPath = $this->uploadPath . DIRECTORY_SEPARATOR . 'medium';
        
        $this->ensureDirectoriesExist();
    }
    
    /**
     * Ensure upload directories exist
     */
    private function ensureDirectoriesExist()
    {
        $directories = [
            $this->uploadPath,
            $this->originalPath,
            $this->thumbnailPath,
            $this->mediumPath
        ];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
        
        // Create .htaccess to prevent direct access (if on Apache)
        $htaccessPath = $this->uploadPath . DIRECTORY_SEPARATOR . '.htaccess';
        if (!file_exists($htaccessPath)) {
            file_put_contents($htaccessPath, "Deny from all\n");
        }
    }
    
    /**
     * Validate uploaded file
     * 
     * @param array $file The $_FILES array element
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public function validate($file)
    {
        // Check if file was uploaded
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['valid' => false, 'error' => 'Invalid file upload'];
        }
        
        // Check for upload errors
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return ['valid' => false, 'error' => 'No file was uploaded'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['valid' => false, 'error' => 'File size exceeds limit'];
            default:
                return ['valid' => false, 'error' => 'Unknown upload error'];
        }
        
        // Check file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return ['valid' => false, 'error' => 'File size exceeds ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB limit'];
        }
        
        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        // finfo_close() is deprecated in PHP 8.5+ - objects are freed automatically
        
        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            return ['valid' => false, 'error' => 'Invalid file type. Allowed types: ' . implode(', ', self::ALLOWED_EXTENSIONS)];
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            return ['valid' => false, 'error' => 'Invalid file extension'];
        }
        
        return ['valid' => true, 'error' => null];
    }
    
    /**
     * Upload and process image
     * 
     * @param array $file The $_FILES array element
     * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
     */
    public function upload($file)
    {
        // Validate file
        $validation = $this->validate($file);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'filename' => null,
                'error' => $validation['error']
            ];
        }
        
        try {
            // Generate unique filename
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = $this->generateUniqueFilename($extension);
            
            // Save original image
            $originalFilePath = $this->originalPath . DIRECTORY_SEPARATOR . $filename;
            if (!move_uploaded_file($file['tmp_name'], $originalFilePath)) {
                return [
                    'success' => false,
                    'filename' => null,
                    'error' => 'Failed to save uploaded file'
                ];
            }
            
            // Generate thumbnail
            $this->generateThumbnail($originalFilePath, $filename);
            
            // Generate medium size image
            $this->generateMediumImage($originalFilePath, $filename);
            
            return [
                'success' => true,
                'filename' => $filename,
                'error' => null
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Error processing image: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate unique filename
     * 
     * @param string $extension
     * @return string
     */
    private function generateUniqueFilename($extension)
    {
        $timestamp = time();
        $random = bin2hex(random_bytes(8));
        return "{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * Generate thumbnail
     * 
     * @param string $sourcePath
     * @param string $filename
     */
    private function generateThumbnail($sourcePath, $filename)
    {
        $thumbnailPath = $this->thumbnailPath . DIRECTORY_SEPARATOR . $filename;
        
        $img = $this->manager->read($sourcePath);
        $img->cover(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);
        $img->save($thumbnailPath, quality: 80);
    }
    
    /**
     * Generate medium size image
     * 
     * @param string $sourcePath
     * @param string $filename
     */
    private function generateMediumImage($sourcePath, $filename)
    {
        $mediumPath = $this->mediumPath . DIRECTORY_SEPARATOR . $filename;
        
        $img = $this->manager->read($sourcePath);
        $img->scaleDown(width: self::MEDIUM_WIDTH, height: self::MEDIUM_HEIGHT);
        $img->save($mediumPath, quality: 85);
    }
    
    /**
     * Delete image and its variants
     * 
     * @param string $filename
     * @return bool
     */
    public function delete($filename)
    {
        if (empty($filename)) {
            return false;
        }
        
        $deleted = false;
        
        // Delete original
        $originalFile = $this->originalPath . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($originalFile)) {
            unlink($originalFile);
            $deleted = true;
        }
        
        // Delete thumbnail
        $thumbnailFile = $this->thumbnailPath . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($thumbnailFile)) {
            unlink($thumbnailFile);
        }
        
        // Delete medium
        $mediumFile = $this->mediumPath . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($mediumFile)) {
            unlink($mediumFile);
        }
        
        return $deleted;
    }
    
    /**
     * Get image URL for display
     * 
     * @param string $filename
     * @param string $size ('original', 'medium', 'thumbnail')
     * @return string|null
     */
    public function getImageUrl($filename, $size = 'medium')
    {
        if (empty($filename)) {
            return null;
        }
        
        // Return route that serves the image
        return "/images/{$size}/{$filename}";
    }
    
    /**
     * Get image path
     * 
     * @param string $filename
     * @param string $size ('original', 'medium', 'thumbnail')
     * @return string|null
     */
    public function getImagePath($filename, $size = 'medium')
    {
        if (empty($filename)) {
            return null;
        }
        
        $path = null;
        switch ($size) {
            case 'original':
                $path = $this->originalPath . DIRECTORY_SEPARATOR . $filename;
                break;
            case 'thumbnail':
                $path = $this->thumbnailPath . DIRECTORY_SEPARATOR . $filename;
                break;
            case 'medium':
            default:
                $path = $this->mediumPath . DIRECTORY_SEPARATOR . $filename;
                break;
        }
        
        return file_exists($path) ? $path : null;
    }
}
