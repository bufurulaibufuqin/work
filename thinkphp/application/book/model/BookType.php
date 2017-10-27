<?php
namespace app\book\model;

use think\Model;

class BookType extends Model {

    protected $table = 'book_type';

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
    /**
     * @param array $field
     * @param $where
     * @param $order
     * @return array
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

    /**
     * 查询一条数据
     * @param array $field
     * @param $where
     * @return array
     * @author wangxuegang
     */
    public function getOneData($field=array(),$where){
        $res = $this->where($where)->field($field)->limit(1)->find();
        if(empty($res)){
            return array();
        }else{
            return $res->toArray();
        }
    }

    /**
     * 查询符合条件的单字段数组
     * @param $key
     * @param $where
     * @return array
     * @author wangxuegang
     */
    public function getWhereIds($key,$where){
        $res = $this->where($where)->column($key);
        if(!$res) $res = array();
        return $res;
    }
}