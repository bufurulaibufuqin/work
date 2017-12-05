<?php
namespace app\admin\controller;

use app\admin\model\PictureType;
use think\Controller;
use think\Request;

class Picture extends Controller {
    private $pictureType;
    public function __construct(){
        parent::__construct();
        $this->pictureType = new PictureType();
    }
    /*
     * 图片管理
     */
    //图片列表
    public function pictureList(){
        return view('picture-list');
    }
    //图片添加
    public function pictureAdd(){
        $method=$this->request->method();
        if($method=='GET'){
            $field=array();
            $where=array();
            $pictureType=$this->pictureType->getList($field,$where);
            return view('picture-add',['pictureType'=>$pictureType]);
        }elseif ($method=='POST'){
            $data=$this->request->param();
            echo json_encode($data);
        }
    }
}