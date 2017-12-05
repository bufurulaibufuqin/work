<?php

namespace app\ai\controller;

use ai\AipOcr;
use think\Controller;

class Ocr extends Controller {
    private $ocr;
    private $method;
    public function __construct(){
        parent::__construct();
        $this->ocr = new AipOcr(AI_APP_ID,AI_API_KEY,AI_SECRET_KEY);
        $this->method = $this->request->method();
    }
    /**
     * 目录
     * @return \think\response\View
     * @author wangxuegang
     */
    public function index(){
        return view();
    }

    /**
     * 通用文字识别
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function basicGeneral(){
        if($this->method=='GET'){
            return view();
        }
        $tmp_name=file_get_contents($_FILES['ocr']['tmp_name']);
        $ocr=$this->ocr->basicGeneral($tmp_name);
        return json_encode($ocr);
    }
}