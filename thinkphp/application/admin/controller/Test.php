<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Test extends Controller {
    //走动的时钟
    public function index(){
        return view('index');
    }
    //飞舞的蝴蝶
    public function butterfly(){
        return view('butterfly');
    }
}