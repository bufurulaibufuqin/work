<?php
namespace app\admin\model;

use Think\Db;
use think\Model;

class Join extends Model{
    public function join(){
        return Db::table('user')->join('group','user.g_id = group.g_id')->select();
    }
}
