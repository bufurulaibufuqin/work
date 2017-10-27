<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Picture extends Controller {
    /*
     * 图片管理
     */
    //图片列表
    public function pictureList(){
        return view('picture-list');
    }
    //图片添加
    public function pictureAdd(){
        $method=Request::instance()->method();
        if($method=='GET'){
            return view('picture-add');
        }elseif ($method=='POST'){
            $data=Request::instance()->param();
            echo json_encode($data);
        }
    }
}