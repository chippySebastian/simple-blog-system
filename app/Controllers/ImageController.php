<?php

namespace App\Controllers;

use App\Services\ImageUploadService;

/**
 * ImageController
 * 
 * Serves images from secure storage outside web root
 */
class ImageController extends BaseController
{
    private $imageService;
    
    public function __construct()
    {
        $this->imageService = new ImageUploadService();
    }
    
    /**
     * Serve image file
     * 
     * @param string $size ('original', 'medium', 'thumbnail')
     * @param string $filename
     */
    public function serve($size, $filename)
    {
        // Validate size parameter
        if (!in_array($size, ['original', 'medium', 'thumbnail'])) {
            http_response_code(404);
            exit('Invalid size parameter');
        }
        
        // Sanitize filename to prevent directory traversal
        $filename = basename($filename);
        
        // Get image path
        $imagePath = $this->imageService->getImagePath($filename, $size);
        
        if (!$imagePath || !file_exists($imagePath)) {
            http_response_code(404);
            exit('Image not found');
        }
        
        // Get mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $imagePath);
        finfo_close($finfo);
        
        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($imagePath));
        header('Cache-Control: public, max-age=31536000'); // Cache for 1 year
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        // Output image
        readfile($imagePath);
        exit;
    }
}
