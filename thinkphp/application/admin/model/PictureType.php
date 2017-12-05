<?php
namespace app\admin\model;

use think\Model;

class PictureType extends Model{
    /**
     * 查询列表
     * @param array $field
     * @param array $where
     * @return array
     */
    public function getList($field=array(),$where=array()){
        $data=array();
        $res=$this->where($where)->field($field)->select();
        if(!empty($res)){
            foreach ($res as $k => $v) {
                $data[] = $v->toArray();
            }
        }
        return $data;
    }
}
