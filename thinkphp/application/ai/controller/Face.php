<?php

namespace app\ai\controller;

use app\ai\model\User;
use ai\AipFace;
use think\Controller;

class Face extends Controller{
    private $face;
    private $method;
    private $user;
    public function __construct(){
        parent::__construct();
        $this->user = new User();
        $this->face = new AipFace(FACE_APP_ID,FACE_API_KEY,FACE_SECRET_KEY);
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
     * 人脸检测
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function faceDetection(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=file_get_contents($_FILES['face']['tmp_name']);
        $face=$this->face->detect($tmp_name,array(
            'face_fields' => 'age,beauty,expression,faceshape,gender,glasses,landmark,race,qualities',
        ));
        return json_encode($face);
    }

    /**
     * 人脸两两比对
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function faceTwoToTwo(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=$_FILES['face']['tmp_name'];
        $tmp_name_str='';
        foreach ($tmp_name as $key=>$val){
            $tmp_name_str.=file_get_contents($val).'；';
        }

        $array=explode("；", $tmp_name_str);
        unset($array[count($array) - 1]);

        $face=$this->face->match($array);
        return json_encode($face);
    }

    /**
     * 人脸注册
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function faceRegistered(){
        if($this->method=='GET'){
            $field=array('admin_id,admin_show_name');
            $where=array(
                'admin_is_del'=>0
            );
            $user=$this->user->getInfo($field,$where);
            return view('faceRegistered',['user'=>$user]);
        }
        $admin_id=$this->request->param('admin_id');
        $admin_show_name=$this->request->param('admin_show_name');
        $tmp_name=file_get_contents($_FILES['face']['tmp_name']);

        $face=$this->face->addUser(
            $admin_id,$admin_show_name,'user_group1',$tmp_name
        );
        return json_encode($face);
    }

    /**
     * 人脸识别
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function faceRecognition(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=file_get_contents($_FILES['face']['tmp_name']);
        $face=$this->face->identifyUser('user_group1',$tmp_name);
        return json_encode($face);
    }

    /**
     * 人脸认证
     * @return string
     * @author wangxuegang
     */
    public function faceAuthentication(){
        $admin_id=$this->request->param('admin_id');
        $tmp_name=file_get_contents($_FILES['face']['tmp_name']);
        $face=$this->face->verifyUser($admin_id,'user_group1',$tmp_name);
        return json_encode($face);
    }
    /**
     * 人脸更新
     * @return string
     * @author wangxuegang
     */
    public function faceToUpdate(){
        $admin_id=$this->request->param('admin_id');
        $admin_show_name=$this->request->param('admin_show_name');
        $tmp_name=file_get_contents($_FILES['face']['tmp_name']);
        $face=$this->face->updateUser($admin_id,$admin_show_name,'user_group1',$tmp_name);
        return json_encode($face);
    }

    /**
     * 人脸删除
     * @return string
     * @author wangxuegang
     */
    public function faceToDelete(){
        $admin_id=$this->request->param('admin_id');
        $face=$this->face->deleteUser($admin_id);
        return json_encode($face);
    }

    /**
     * 用户信息查询
     * @return string
     * @author wangxuegang
     */
    public function faceUserInfo(){
        $admin_id=$this->request->param('admin_id');
        $face=$this->face->getUser($admin_id);
        return json_encode($face);
    }

    /**
     *
     * 组列表查询
     * @return string
     * @author wangxuegang
     */
    public function faceGroupList(){
        $face=$this->face->getGroupList();
        return json_encode($face);
    }

    /**
     *
     * 组内用户列表查询
     * @return string
     * @author wangxuegang
     */
    public function faceGroupUsers(){
        $face=$this->face->getGroupUsers('user_group1');
        return json_encode($face);
    }

    /**
     *
     * 组内用户列表查询
     * @return string
     * @author wangxuegang
     */
    public function faceGroupUser(){
        $face=$this->face->addGroupUser('user_group2','user_group1',3);
        return json_encode($face);
    }

    /**
     * 组内删除用户
     * @return string
     * @author wangxuegang
     */
    public function faceDelGroupUser(){
        $admin_id=$this->request->param('admin_id');
        $face=$this->face->deleteGroupUser('user_group1',$admin_id);
        return json_encode($face);
    }
}