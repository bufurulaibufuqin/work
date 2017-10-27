<?php

namespace app\ai\controller;

use face\AipFace;
use think\Controller;

class Face extends Controller{
    private $face;
    public function __construct(){
        parent::__construct();
        $this->face = new AipFace(FACE_APP_ID,FACE_API_KEY,FACE_SECRET_KEY);
    }
    public function index(){
        return view();
    }
    public function faceDetection(){
        $method=$this->request->method();
        if($method=='GET'){
            return view();
        }
        $face=$this->face->detect(file_get_contents($_FILES['face']['tmp_name']),array(
            'face_fields' => 'age,beauty,expression,faceshape,gender,glasses,landmark,race,qualities',
        ));
        return json_encode($face);
    }
}