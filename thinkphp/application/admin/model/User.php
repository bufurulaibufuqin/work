<?php
namespace app\admin\model;

use think\Model;

class User extends Model{
    protected $pk = 'u_id';
    //登录查询
    public function login($name,$password){
        $where=array(
            'name'=>$name,
            'password'=>md5($password),
        );
        $user = User::get($where);
        return $user;
    }
}
