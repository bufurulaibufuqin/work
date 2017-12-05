<?php

namespace app\ai\controller;

use ai\AipImageCensor;
use think\Controller;

class Image extends Controller{
    private $imageCensor;
    private $method;
    public function __construct(){
        parent::__construct();
        $this->imageCensor = new AipImageCensor(AI_APP_ID,AI_API_KEY,AI_SECRET_KEY);
        $this->method = $this->request->method();
    }

    /**
     * 目录
     * @return \think\response\View
     * @author wangxuegang
     */
    public function index(){
        return view();
    }

    /**
     * 色情识别
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function antiPorn(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=file_get_contents($_FILES['image']['tmp_name']);
        $imageCensor=$this->imageCensor->antiPorn($tmp_name);
        return json_encode($imageCensor);
    }

    /**
     * GIF色情图像识别
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function antiPornGif(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=file_get_contents($_FILES['image']['tmp_name']);
        $imageCensor=$this->imageCensor->antiPornGif($tmp_name);
        return json_encode($imageCensor);
    }

    /**
     * GIF色情图像识别
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function antiTerror(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=file_get_contents($_FILES['image']['tmp_name']);
        $imageCensor=$this->imageCensor->antiTerror($tmp_name);
        return json_encode($imageCensor);
    }

    /**
     * 头像审核
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function faceAudit(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=$_FILES['image']['tmp_name'];
        $tmp_name_str='';
        foreach ($tmp_name as $key=>$val){
            $tmp_name_str.=file_get_contents($val).'；';
        }

        $array=explode("；", $tmp_name_str);
        unset($array[count($array) - 1]);

        $imageCensor=$this->imageCensor->faceAudit($array);
        return json_encode($imageCensor);
    }

    /**
     * 组合审核
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function imageCensorComb(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=file_get_contents($_FILES['image']['tmp_name']);
        $options=array('antiporn','webimage');
        $imageCensor=$this->imageCensor->imageCensorComb($tmp_name,$options);
        return json_encode($imageCensor);
    }
}