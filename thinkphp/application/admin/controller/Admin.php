<?php
namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Cookie;
use think\Request;
use app\admin\model\AdminTable;

class Admin extends Controller {
    private $admin;
    public function __construct(){
        parent::__construct();
        $this->admin = new AdminTable();
    }
    /*
     * 后台首页&我的桌面
     */
    //后台首页
    public function index(){
        return view('index-2');
    }
    //我的桌面
    public function welcome(){
        return view('welcome');
    }
    /*
     * 管理员管理
     */
    //管理员列表
    public function adminList(){
        $param=Request::instance()->param();
        $start_time=!empty($param['start_time'])?$param['start_time']:'';
        $end_time=!empty($param['end_time'])?$param['end_time']:'';
        $admin_name=!empty($param['admin_name'])?$param['admin_name']:'';

        if($start_time != '' && $end_time != ''){
            $admin_time_ids=$this->admin->whereTime('admin_join_time','between',[$start_time,$end_time])->column('admin_id');
            if(!$admin_time_ids) $admin_time_ids=array();
        }
        if($admin_name != ''){
            $admin_name_ids=$this->admin->where('admin_login_name','like',"%$admin_name%")->column('admin_id');
            if(!$admin_name_ids) $admin_name_ids=array();
        }
        $admin_ids = $this->admin->where('admin_is_del','EQ',0)->column('admin_id');

        if(isset($admin_time_ids)) $admin_ids = array_intersect($admin_time_ids,$admin_ids);
        if(isset($admin_name_ids)) $admin_ids = array_intersect($admin_name_ids,$admin_ids);

        $ids = join(',',$admin_ids) ;
        $admin_data=$this->admin->where('admin_id','in',$ids)->select();
        return view('admin-list',['admin_data'=>$admin_data,'start_time'=>$start_time,'end_time'=>$end_time,'admin_name'=>$admin_name]);
    }
    //添加管理员
    public function adminAdd(){
        $method=Request::instance()->method();
        if($method=='GET'){
            if(Cookie::has('admin_a_data')){
                $data_str=Cookie::get('admin_a_data');
                $admin_a_data=json_decode($data_str,true);
                $admin_a_data['admin_update']=1;
                return view('admin-add',['admin_a_data'=>$admin_a_data]);
            }else{
                $admin_a_data=array(
                    'admin_update'=>'',
                    'admin_login_name'=>'',
                    'admin_show_name'=>'',
                    'admin_phone'=>'',
                    'admin_email'=>'',
                    'admin_desc'=>'',
                );
                return view('admin-add',['admin_a_data'=>$admin_a_data]);
            }
        }elseif($method=='POST'){
            $param=Request::instance()->param();
            $login_name=$param['login_name'];
            $show_name=$param['show_name'];
            $password=$param['password'];
            $sex=$param['sex'];
            $phone=$param['phone'];
            $email=$param['email'];
            $desc=$param['desc'];
            $insert_data=array(
                'admin_login_name'=>$login_name,
                'admin_show_name'=>$show_name,
                'admin_password'=>md5($password),
                'admin_sex'=>$sex,
                'admin_phone'=>$phone,
                'admin_email'=>$email,
                'admin_desc'=>$desc,
                'admin_join_time'=>date('Y-m-d H:i:s')
            );
            if($this->admin->insert($insert_data)){
                $data['code']=1;
                $data['msg']='添加成功';
            }else{
                $data['code']=0;
                $data['msg']='添加失败';
            }
            echo json_encode($data);
        }
    }
    //管理员操作
    public function adminOperation(){
        $param=Request::instance()->param();
        $kind=$param['kind'];
        $id=$param['id'];
        if($kind=='admin_stop'){//管理员停用
            $update_array=array(
                'admin_is_enable'=>1
            );
            if($this->admin->where('admin_id',$id)->update($update_array)){
                $data['code']=1;
                $data['msg']='停用成功';
            }else{
                $data['code']=0;
                $data['msg']='停用失败';
            }
            echo json_encode($data);
        }elseif ($kind=='admin_start'){//管理员启用
            $update_array=array(
                'admin_is_enable'=>0
            );
            if($this->admin->where('admin_id',$id)->update($update_array)){
                $data['code']=1;
                $data['msg']='启用成功';
            }else{
                $data['code']=0;
                $data['msg']='启用失败';
            }
            echo json_encode($data);
        }elseif ($kind=='admin_update'){//编辑管理员
            $show_name=$param['show_name'];
            $phone=$param['phone'];
            $email=$param['email'];
            $desc=$param['desc'];
            $update_array=array(
                'admin_show_name'=>$show_name,
                'admin_phone'=>$phone,
                'admin_email'=>$email,
                'admin_desc'=>$desc,
            );
            $this->admin->where('admin_id',$id)->update($update_array);
        }elseif ($kind=='admin_del'){//管理员删除
            $update_array=array(
                'admin_is_del'=>1
            );
            if($this->admin->where('admin_id',$id)->update($update_array)){
                $data['code']=1;
                $data['msg']='删除成功';
            }else{
                $data['code']=0;
                $data['msg']='删除失败';
            }
            echo json_encode($data);
        }else if($kind=='admin_cookie'){//设置管理员cookie
            if($id!=0){
                $admin_a_data=$this->admin->where('admin_id',$id)->find();
                Cookie::set('admin_a_data',$admin_a_data);
            }else{
                Cookie::delete('admin_a_data');
            }
            $data=array(
                'code'=>1,
                'msg'=>'设置成功'
            );
            echo json_encode($data);
        }
    }
    public function test(){
        $file='123456.jpg';
        $postfix=substr(strrchr($file, '.'), 1);
        $upload=Cookie::get('upload');
        echo $upload;
    }
}
