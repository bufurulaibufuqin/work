<?php

namespace app\ai\model;

use think\Model;

class User extends Model{

    protected $table = 'admin';
    //自定义初始化
    public function initialize(){
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }
    /**
     * 查询列表信息
     * @param array $field
     * @param $where
     * @return array
     * @author wangxuegang
     */
    public function getInfo($field=array(),$where=array(),$order=array()){
        $res = $this->where($where)->field($field)->order($order)->select();
        if (empty($res)) {
            return array();
        } else {
            $data = [];
            foreach ($res as $k => $v) {
                $data[] = $v->toArray();
            }
            return $data;
        }
    }
}