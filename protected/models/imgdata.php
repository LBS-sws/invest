<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2018/3/20 0020
 * Time: 9:43
 */
class imgdata{

    public $imgsrc;
    public $imgdata;
    public $imgform;
    public function getdir($source){
        $this->imgsrc  = $source;
    }
    public function img2data(){
        $info=getimagesize($this->imgsrc);
        if($info){
            $img = $this->imgsrc;
            $imgExt = image_type_to_extension($info[2], false);  //获取文件后缀
            $fun = "imagecreatefrom{$imgExt}";
            $imgInfo = $fun($img);
            $mime = image_type_to_mime_type(exif_imagetype($img)); //获取图片的 MIME 类型
            header('Content-Type:'.$mime);
            $quality = 100;
            if($imgExt == 'png') $quality = 9;      //输出质量,JPEG格式(0-100),PNG格式(0-9)
            $getImgInfo = "image{$imgExt}";
            $getImgInfo($imgInfo, null, $quality);
            imagedestroy($imgInfo);
        }else{
            header("Content-type: image/png");
            $im = @imagecreate(200, 200)or die("创建图像资源失败");
            $bg = imagecolorallocate($im, 204, 204, 204);
            $red = imagecolorallocate($im, 255, 0, 0);
            imagestring($im, 12, 45, 90, "Image Error", $red);
            imagepng($im);
            imagedestroy($im);
        }
    }
    public function data2img(){
        //測試
    }
}