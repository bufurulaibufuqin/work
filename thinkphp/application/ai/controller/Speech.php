<?php

namespace app\ai\controller;

use ai\AipSpeech;
use think\Controller;

class Speech extends Controller {
    private $speech;
    private $method;
    public function __construct(){
        parent::__construct();
        $this->speech = new AipSpeech(AI_APP_ID,AI_API_KEY,AI_SECRET_KEY);
        $this->method = $this->request->method();
    }
    /**
     * 语音识别
     * @return string|\think\response\View
     * @author wangxuegang
     */
    public function index(){
        if($this->method=='GET'){
            return view();
        }
        $speech=$this->speech->asr(null,'pcm',16000,array(
            'url' => 'http://images.xn--rhqy14ac7tcmj.top/8k.wav',
        ));
        return json_encode($speech);
    }
}