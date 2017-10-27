<?php
namespace app\admin\service;

use app\admin\model\User;

class AdminService {
    private $User;
    public function __construct() {
        $this->User=new User();
    }
    //登录
    public function login($name,$password){
        $data=$this->User->login($name,$password);
        if (!empty($data)){
            $user=$data->toArray();
        }else{
            $user=array();
        }
        return $user;
    }
}