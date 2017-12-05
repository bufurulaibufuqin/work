<?php
namespace app\admin\controller;

use PHPMailer\PHPMailer\PHPMailer;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Request;
use app\admin\model\AdminTable;

class Index extends Controller {
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
//        $toemail = '1392197379@qq.com';//定义收件人的邮箱
//
//        $mail = new PHPMailer();
//
//        $mail->isSMTP();// 使用SMTP服务
//        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
//        $mail->Host = "smtp.dycd.com";// 发送方的SMTP服务器地址
//        $mail->SMTPAuth = true;// 是否使用身份验证
//        $mail->Username = "wangxuegang@dycd.com";// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱
//        $mail->Password = "admin2017";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！
//        $mail->SMTPSecure = "ssl";// 使用ssl协议方式
//        $mail->Port = 465;// 163邮箱的ssl协议方式端口号是465/994
//
//        $mail->setFrom("wangxuegang@dycd.com",'王雪岗');// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
//        $mail->addAddress($toemail);// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
//        $mail->addReplyTo("wangxuegang@dycd.com",'王雪岗');// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
//        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
//        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
//        $mail->addAttachment("shuju.xlsx");// 添加附件
//
//        $mail->Subject = "这是一个测试邮件";// 邮件标题
//        $mail->Body = "邮件内容是：您的验证码是：123456，哈哈哈！";// 邮件正文
//        //$mail->AltBody = "This is the plain text纯文本";// 这个是+设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用
//
//        if(!$mail->send()){// 发送邮件
//            echo "Message could not be sent.";
//            echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
//        }else{
//            echo '发送成功';
//        }
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
