<?php
namespace app\admin\controller;

use app\admin\model\User;
use think\Cookie;
use think\Db;

class Index{
    private $user;

    public function __construct()
    {
        $this->user=new User();
    }

    public function index(){
        set_time_limit(300);
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $redis->auth('123456');
//        print_r($redis->rpoplpush('order_user_id','new_order_user_id'));die;

//        $order_user_id=$redis->brPop('order_user_id',5);
//        if($order_user_id[1]){
//            echo '出队id'.$order_user_id[1];
//        }else{
//            echo '出队完成';
//        }
//        die;
//        print_r($redis_user);die;
        //字符串cun
//        $redis->set('tutorial-string','tutorial-test');
//        echo $redis->get('tutorial-string');

//        echo $redis->getRange('tutorial-string',0,3);
//        echo '<br>';
//        echo $redis->getRange('tutorial-string',4,6);
//        echo '<br>';
//        echo $redis->getRange('tutorial-string',7,10);

//        echo $redis->getSet('tutorial-string','string-test');

//        print_r($redis->mget(array('tutorial-string','tutorial-string1')));

        
////      列表存
//        $redis->lpush("tutorial-list", "Redis",'Mongodb','Mysql');
////      获取存储的数据并输出
//        $arList = $redis->lrange("tutorial-list", 0 ,100);
//        print_r($arList);

//        $insert_user_sql='insert into user (g_id,`name`,password,login_second,login_ip,login_time) values ';
//        $insert_user_sql.="(1,'$name$i','$password',$i,'127.0.0.1',now()),";
//        $insert_user_sql=substr($insert_user_sql,0,-1);


//        $name='wangxuegang';
        for ($i=1;$i<=50;$i++){
//            $redis->lPush('order_user_id',$i);

//            $password=md5('12345'.$i);
//            $insert_user_data=array(
//                'g_id'=>1,
//                'name'=>$name.$i,
//                'password'=>$password,
//            );
//            $last_id=$this->user->insertGetId($insert_user_data);
//            $redis->set('user_table_'.$last_id.'_login_second',$i);
//            $redis->set('user_table_'.$last_id.'_login_ip','127.0.0.1');
//            $redis->set('user_table_'.$last_id.'_login_time',date('Y-m-d H:i:s'));

//            $redis_user_data=$redis->mget(array('user_table_'.$i.'_login_second','user_table_'.$i.'_login_ip','user_table_'.$i.'_login_time'));
//            $redis_user_data_array=array(
//                'login_second'=>$redis_user_data[0],
//                'login_ip'=>$redis_user_data[1],
//                'login_time'=>$redis_user_data[2],
//            );
//            $redis->hMset("user_table_u_id:$i",$redis_user_data_array);
        }
//        die;
        return view('index');
    }
    public function welcome(){
        $user=Cookie::get('user');
        $data=array(
            'hostname'=>gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'host'=>$_SERVER['HTTP_HOST'],
            'domainName'=>$_SERVER['SERVER_NAME'],
            'port'=>$_SERVER["SERVER_PORT"],
            'PHP_OS'=>PHP_OS,
        );
        return view('welcome',['user'=>$user,'data'=>$data]);
    }
}
