<?php
class ImageResizer {

    protected $image;
    protected $image_type;

    public function load ($filename) {
        $image_info = getimagesize ($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF){
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG){
            $this->image = imagecreatefrompng($filename);
        }
    }

public function save ($filename, $image_type =IMAGETYPE_JPEG, $compression =100){
    if ($this->image_type == IMAGETYPE_JPEG) {
        imagejpeg($this->image, $filename, $compression);
    } elseif ($this->image_type == IMAGETYPE_GIF){
        imagejpeg($this->image, $filename);
    } elseif ($this->image_type == IMAGETYPE_PNG){
        imagejpeg($this->image, $filename);
    }
}

protected function getWidth (){
    return imagesx($this->image);
}

protected function getHeight (){
    return imagesy ($this->image);
}


public function resizeToHeight ($height) {
    $ratio = $height / $this-> getHeight();
    $width =$this->getWidth() * $ratio;
    $this->resize($width, $height);
}

public function resizeToWidth ($width){
    $ratio = $width / $this-> getWidth();
    $height =$this->getHeight() * $ratio;
    $this->resize($width, $height);
}

public function scale($scale){
    $width = $this-> getWidth() * $scale / 100;
    $height = $this->getHeight() * $scale /100;
    $this-> resize($width, $height);
}

public function resize ($width, $height) {
    $new_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($new_image, $this-> image, 0,0,0,0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->image =$new_image;
}
}

/*$size = new imageResizer();
$size->load("imagefox.png");
$size-> scale(250);
$size->save("img/imagefox.png");*/



