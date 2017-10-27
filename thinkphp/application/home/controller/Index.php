<?php
namespace app\home\controller;

class Index
{
    public function index()
    {
        return view('index-color');
    }
    public function test(){
        return view('test');
    }
}
