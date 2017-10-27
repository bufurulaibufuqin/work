<?php
namespace app\admin\controller;

use app\admin\service\AdminService;
use think\Cookie;
use think\Request;

class Login{
    private $AdminService;

    public function __construct() {
        $this->AdminService=new AdminService();
    }

    public function login(){
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $redis->auth('123456');

        if (Request::instance()->isGet()){
            return view('login');
        }elseif (Request::instance()->isPost()){
            $name = Request::instance()->param('name');
            $password = Request::instance()->param('password');
            $user=$this->AdminService->login($name,$password);
            if(!empty($user)){
                $redis_user=$redis->hGetAll('user_table_u_id:'.$user['u_id']);

                $ip=$_SERVER["REMOTE_ADDR"];

                $redis->hSet('user_table_u_id:'.$user['u_id'],'login_second',$redis_user['login_second']+1);
                $redis->hSet('user_table_u_id:'.$user['u_id'],'login_ip',$ip);
                $redis->hSet('user_table_u_id:'.$user['u_id'],'login_time',date('Y-m-d H:i:s'));

                unset($user['password']);
                $user=array(
                    'login_second'=>$redis_user['login_second'],
                    'login_ip'=>$redis_user['login_ip'],
                    'login_time'=>$redis_user['login_time']
                );
                Cookie::set('user',$user,3600);
                echo '200';
            }else{
                echo '404';
            }
        }
    }
}
