<?php

class File extends \Phalcon\Mvc\User\Component {
    private $_maxNumberOfFiles = 1000;
    public $uploadDir;

    public function __construct($folderKey, $type) {
        $typeDir   = $this->config->upload->$folderKey->type->$type->dir;
        $keyDir    = $this->config->upload->$folderKey->key;
        $uploadDir = $this->config->upload->uploadDir;
        $uploadUrl = $this->config->upload->uploadUrl;

        $this->uploadDir = $uploadDir . '/' . $keyDir . '/' . $typeDir;
        $this->uploadUrl = $uploadUrl . '/' . $keyDir . '/' . $typeDir;
    }

    /**
    * get folder number by id
    * @param int $fileId
    * @return string $folder
    */
    public function getFolder($fileId){
        $folder = $fileId % $this->_maxNumberOfFiles;
        return $folder;
    }

    /**
     * get upload path of new record. 
     * Base on id of record, defined the file path will be stored
     * @param  int $fileId        record id
     * @param  string $extension  file extension
     * @param  string $subFolder  ex: image, termsound
     * @param  string $version    ex: original, thumb
     * @return string $path            
     */
    public function getUploadPath($fileId, $extension, $version = 'original') {
        $uploadDir  = $this->uploadDir;
        $uploadDir .= '/' . $this->getFolder($fileId) . '/' . $version;

        $this->createFolder($uploadDir);

        $fileName = $fileId;
        if ($extension) $fileName .= "." . strtolower($extension);

        return $uploadDir . '/' . $fileName;    
    }

    /**
     * Get upload url of file base on file id
     * 
     * @param  int $fileId        record id
     * @param  string $extension  file extension
     * @param  string $subFolder  ex: image, termsound
     * @param  string $version    ex: original, thumb
     * @return string $path 
     */
    static public function getUploadUrl($folderKey, $type, $fileId, $extension, $version = 'original') {
        $fileManager = new File($folderKey, $type);

        $host       = $fileManager->config->url->cdn;

        $uploadUrl  = $fileManager->uploadUrl;
        $uploadUrl .= '/' . $fileManager->getFolder($fileId) . '/' . $version;

        $fileName = $fileId;
        if ($extension) $fileName .= "." . strtolower($extension);

        return $host . $uploadUrl . '/' . $fileName;
    }

    public function deleteFile($fileId, $extension, $version = 'original') {
        $uploadDir  = $this->uploadDir;
        $uploadDir .= '/' . $this->getFolder($fileId) . '/' . $version;

        $fileName = $fileId;
        if ($extension) $fileName .= "." . strtolower($extension);

        $filePath = $uploadDir . '/' . $fileName;
        if (is_file($filePath)) return unlink($filePath);

        return false;
    }

    /**
     * Resize image
     * @param string $filePath
     * @param int $toWidth
     * @param int $toHeight
     * @param int $quatity
     * @return string
     */
    static public function resizeImage($filePath, $toWidth, $toHeight, $destinationPath, $quality = 90) {
        $cmd = "convert '{$filePath}' -resize {$toWidth}x{$toHeight} -quality {$quality} '{$destinationPath}'";

        shell_exec($cmd);
    }


    static public function createFolder($folderPath){
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    }

    /**
     * Generate thumbnail of image
     * @param  int $imageId   
     * @param  string $extension 
     * @return void
     */
    static public function generateThumbnail($imageId, $extension){        
        $file = new File();

        $filePath = File::getImagePath($imageId, $extension);
        $toWidth = $file->systemConfig['Flashcard_Image_Thumbnail_Width'];
        $toHeight = $file->systemConfig['Flashcard_Image_Thumbnail_Height'];
        $destinationPath = File::getImagePath($imageId, $extension, 'thumb');
        File::resizeImage($filePath, $toWidth, $toHeight, $destinationPath);
    }

    /**
     * Move uploaded image to server
     * @param  string $uploadedFilePath 
     * @param  int    $id
     * @param  string $extension
     * @return void
     */
    static public function moveImage($uploadedFilePath, $id, $extension){
        $file = new File();
        $imagePath = $file->getUploadPath($id, $extension, $file->imageDir);
        $imageContent = false;
        if ($extension == 'png'){
            $imageContent = File::compressPNG($uploadedFilePath);
        }
        if ($imageContent == false){
            $imageContent = file_get_contents($uploadedFilePath);
        }
        file_put_contents($imagePath, $imageContent);
        // copy($uploadedFilePath, $imagePath);
        unlink($uploadedFilePath);
        File::generateThumbnail($id, $extension);
    }

    /**
     * generate json file
     * @param  string $filePath 
     * @param  array $data     
     * @return boolean           
     */
    static public function generateJsonFile($filePath, $data){
        $result  = new \stdClass();
        $result  = $data;
        $content = json_encode($result);

        return file_put_contents($filePath, $content);
    }

    /**
     * Zip folder
     * 
     * @param  string  $folderPath     [description]
     * @param  boolean $isRemoveFolder [description]
     * @return string|boolean
     */
    static public function zipFolder($folderPath, $isRemoveFolder = false){
        $folderName = basename($folderPath);
        shell_exec("cd " . $folderPath . "&& cd .. && zip -r " . $folderName . ".zip " . $folderName);
        $fileName = $folderPath . '.zip';
        if (file_exists($fileName)){
            if ($isRemoveFolder){
                shell_exec("rm -r " . $folderPath);
            }
            return $fileName;
        }        
        return false;
    }

    /**
     * Copy folder
     * 
     * @param  string $source [description]
     * @param  string $desc   [description]
     * @return void
     */
    static public function copyFolder($source, $desc){
        shell_exec("cp -r " . $source . " " . $desc);
    }

    static public function convertImage($filePath, $ext, $quality = 90){        
        $oldExt          = File::getExtension($filePath);

        $destinationPath = str_replace($oldExt, $ext, $filePath);
        $cmd             = "convert '{$filePath}' -quality {$quality} '{$destinationPath}'";
        shell_exec($cmd);

        if (file_exists($destinationPath)){
            shell_exec("rm $filePath");
            return $destinationPath;
        }

        return false;
    }

    /**
     * Get extension of file
     * 
     * @param  string $filePath 
     * @return string $extension
     */
    static public function getExtension($filePath){
        return strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    }

    /**
     * Get extension of file by mine type
     * 
     * @param  string $filePath 
     * @return string $extension
     */
    static public function getExtensionByMineType($filePath) {
        $mimeContentType = mime_content_type($filePath);

        $fileExtensions = array(
            'image/gif'          => 'gif',
            'image/jpeg'         => 'jpg',
            'image/png'          => 'png',
            'application/x-shockwave-flash' => 'swf',
            'image/psd'          => 'psd',
            'image/bmp'          => 'bmp',
            'image/tiff'         => 'tiff',
            'image/tiff'         => 'tiff',
            'application/octet-stream'      => 'jpc',
            'image/jp2'                     => 'jp2',
            'application/octet-stream'      => 'jpf',
            'application/octet-stream'      => 'jb2',
            'application/x-shockwave-flash' => 'swc',
            'image/iff'          => 'aiff',
            'image/vnd.wap.wbmp' => 'wbmp',
            'image/xbm'          => 'xbm',
        );

        if ($fileExtensions[$mimeContentType]) {
            return $fileExtensions[$mimeContentType];
        } else {
            return "";
        }
    }
}