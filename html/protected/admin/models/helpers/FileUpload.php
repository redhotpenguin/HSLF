<?php

/**
 * This is a helper class to upload files.
 *
 *
 * 
 *  
 */
class FileUpload {

    private $allowed_mime_types;
    private $field_attribute; // model field

    public function __construct($field_attribute, array $allowed_mime_types = null) {
        $this->field_attribute = $field_attribute;
        $this->allowed_mime_types = $allowed_mime_types;
    }

    public function save($file, $upload_directory) {

        if (!$this->verifyMimeType($file)) {
            return false;
        }

       
        Yii::import('application.extensions.upload.Upload');

        // create a new Upload object
        $upload = new Upload($_FILES[$this->field_attribute]);

        if ($upload->uploaded) {

            // actually process the file
            // Yii::app()->getBasePath() . Yii::app()->params['upload_dir'] .
            
            $fq_upload_directory = Yii::app()->getBasePath() . Yii::app()->params['upload_dir'].'/'. $upload_directory;
            
            $upload->process($fq_upload_directory);

            // get the file name after its saved ( needed because file names are incremented to avoid file overriding)
            $final_name = $upload->file_dst_name;
            
            //  return the fully qualified image name (ex: http://www.site.com/image1.jpg)
            return Yii::app()->params['upload_path'].$upload_directory.'/'.$final_name;
        } else {
            error_log('upload fail');
            return false;
        }
    }

    public function setAllowedMimeTypes(array $allowed_mime_types) {
        $this->allowed_mime_types = $allowed_mime_types;
    }

    public function getAllowedMimeTypes() {
        return $this->allowed_mime_types;
    }

    private function verifyMimeType($file) {
        $file_info = getimagesize($file['tmp_name']);
        return in_array($file_info['mime'], $this->allowed_mime_types);
    }

}

?>
