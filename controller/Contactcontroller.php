<?php

include_once("model/Contact.php");

Class Contactcontroller {

    public $model;

    public function __construct() {
        $this->model = new Contact();
    }

    public function fieldArray() {
        $field_array = array(
            array('name' => 'item_type', 'type' => 'text', 'class' => 'form-data validaterequiredfield'),
            array('name' => 'title','validation' => 'blank','class' => 'validaterequiredfield'),
            array('name' => 'email_address','validation' => 'blank,email','class' => 'validaterequiredfield'),
            array('name' => 'contact_number','validation' => 'blank,number','class' => 'validaterequiredfield'),
            array('name' => 'tmp_upload_document', 'type' => 'file'),
            array('name' => 'tmp_upload_image', 'type' => 'file'),
            array('name' => 'upload_document', 'type' => 'hidden'),
            array('name' => 'upload_image', 'type' => 'hidden'),
            array('name' => 'slug'),
            //array('name' => 'radio_options', 'type' => 'radio','options' => array('1'=>'Radio Option 1','2'=>'Option 2','3'=>'Option 3')),
            array('name' => 'display_order'),
            array('name' => 'comments', 'type' => 'textarea'),
            array('name' => 'select_options', 'type' => 'select', 'options' => array('1' => 'Option 1', '2' => 'Option 2', '3' => 'Option 3')),
            array('name' => 'information_type', 'type' => 'select', 'multiple' => 'multiple', 'options' => array('1' => 'type 1', '2' => 'type 2', '3' => 'type 3')),
        );
        return $field_array;
    }

    public function index() {
        $field_array = $this->fieldArray();
        $data = $this->model->getContactdata();
        require_once 'view/Contact.php';
    }

    public function newfilename($filename) {
        $extension = end(explode(".", $filename));
        return (time() . "." . $extension);
    }

    function save() {
        $response = array();
        $validarray = $this->fieldArray();
        if(isset($validarray) && $validarray != false){
            foreach($validarray as $validation){
                if(isset($validation['validation']) && $validation['validation'] != ""){
                    $arrValidation = explode(",",$validation['validation']);
                    if(isset($arrValidation[0]) && $arrValidation[0] == 'blank'){
                        if(isset($_REQUEST[$validation['name']]) && $_REQUEST[$validation['name']] == ""){
                            $response[$validation['name']] = $validation['name'].' cannot be blank';
                        }
                    }
                    if(isset($arrValidation[1]) && $arrValidation[1] == 'number'){
                        if(!is_numeric($_REQUEST[$validation['name']])){
                            $response[$validation['name']] = "Please enter numbers only for ".$validation['name'];
                        }
                    }
                    if(isset($arrValidation[1]) && $arrValidation[1] == 'email'){    
                            if (!filter_var($_REQUEST[$validation['name']], FILTER_VALIDATE_EMAIL)) {
                            $response[$validation['name']] = "Please enter valid email address.";
                        }
                    }
                }
            }
            
            //echo "<pre>";print_r($response);exit;
        }
        echo json_encode($response);
        exit;
        echo "save method called.........";
        echo "<pre>";
        print_r($_REQUEST);
        exit;
    }

    public function savefile() {
        global $root_path;
        $success = 0;
        $tmp_filename = $_FILES['file']['tmp_name'];
        $newfilename = date('Ymdhis') . "_" . $_FILES['file']['name'];
        //$newfilename = newfilename($_FILES["file"]["name"]);
        $width = "100";
        $height = "100";
        $source = TEMP_UPLOAD . $newfilename;
        $destination = TEMP_UPLOAD . "/thumb/";
        $thumb_filename = "TH_" . $newfilename;
        if (move_uploaded_file($tmp_filename, $source)) {
            $this->createThumb($source, $thumb_filename, $destination, $width, $height);
            $success = 1;
        }

        $response = array(
            "success" => $success,
            "filepath" => $source,
            "filename" => $newfilename,
            "fileurl" => TEMP_UPLOAD_URL . $newfilename,
        );
        echo json_encode($response);
        exit;
    }

    public function createThumb($source, $filename, $destination, $width, $height) {
        $info = pathinfo($source);
        if (strtolower($info['extension']) == 'jpg') {
            $img = imagecreatefromjpeg("{$source}");
        } elseif (strtolower($info['extension']) == 'jpeg') {
            $img = imagecreatefromjpeg("{$source}");
        } elseif (strtolower($info['extension']) == 'gif') {
            $img = imagecreatefromgif("{$source}");
        } elseif (strtolower($info['extension']) == 'png') {
            $img = imagecreatefrompng("{$source}");
        }

        @$newwidth = $originalwidth = imagesx($img);
        @$newheight = $originalheight = imagesy($img);

        $color = "#CCCCCC";
        $strTrueColor = $this->html2rgb($color);
        $intFirstColor = $strTrueColor[0];
        $intSecondColor = $strTrueColor[1];
        $intThirdColor = $strTrueColor[2];

        if ($originalwidth > $width) {
            @$newwidth = $width;
            @$newheight = (($originalheight * $width) / $originalwidth);
        }
        if ($newheight > $height) {
            @$newheight = $height;
            @$newwidth = (($originalwidth * $height) / $originalheight);
        }

        @$tmp_img = imagecreatetruecolor($width, $height);

        $strWhiteBackground = imagecolorallocate($tmp_img, $intFirstColor, $intSecondColor, $intThirdColor);
        imagefill($tmp_img, 0, 0, $strWhiteBackground);

        @$floatWhiteWidth = (($width - $newwidth) / 2);
        @$floatWhiteHeight = (($height - $newheight) / 2);

        @imagecopyresized($tmp_img, $img, $floatWhiteWidth, $floatWhiteHeight, 0, 0, $newwidth, $newheight, $originalwidth, $originalheight);
        $strDestinationFilename = basename($destination);
        $destination = str_replace($strDestinationFilename, "", $destination);

        if (strtolower($info['extension']) == 'jpg') {
            imagejpeg($tmp_img, "{$destination}{$filename}");
        } elseif (strtolower($info['extension']) == 'jpeg') {
            imagegif($tmp_img, "{$destination}{$filename}");
        } elseif (strtolower($info['extension']) == 'gif') {
            imagegif($tmp_img, "{$destination}{$filename}");
        } elseif (strtolower($info['extension']) == 'png') {
            imagepng($tmp_img, "{$destination}{$filename}");
        }
    }

    function html2rgb($color = '') {
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }
        if (strlen($color) == 6) {
            list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array($r, $g, $b);
    }

}
