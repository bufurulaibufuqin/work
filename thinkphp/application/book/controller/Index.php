<?php
namespace app\book\controller;

use app\book\model\BookType;
use ai\AipFace;
use think\Controller;
use think\Request;

class Index extends Controller
{
    private $book_type;
    private $title;
    private $face;

    /**
     * 初始化Controller构造函数
     * Index constructor.
     * @param Request|null $request
     * @author wangxuegang
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->book_type = new BookType();
        $this->face = new AipFace(FACE_APP_ID,FACE_API_KEY,FACE_SECRET_KEY);

        //点击导航的文字
        $nav = $this->request->request('nav','');

        if(empty($nav)){
            $this->title = '无为书城_无为集团旗下网站';
        }else{
            $this->title = $nav.'频道_无为书城';
        }
    }

    /**
     * 书城首页
     * @return \think\response\View
     * @author wangxuegang
     */
    public function index()
    {
        $fiend = ['name'];
        $where = [
            'is_del'=>0,
        ];
        $order = [
            'sort'=>'desc'
        ];
        $book_type=$this->book_type->getInfo($fiend,$where,$order);
        $data=[
            'book_type'=>$book_type,
            'title'=>$this->title
        ];
        return view('index',['data'=>$data]);
    }
    public function face(){
        $method=$this->request->method();
        if($method=='GET'){
            $data=[
                'title'=>$this->title
            ];
            return view('face',['data'=>$data]);
        }
        $face=$this->face->detect(file_get_contents($_FILES['face']['tmp_name']),array(
            'face_fields' => 'age,beauty,expression,faceshape,gender,glasses,landmark,race,qualities',
        ));
        $data['age']=round($face['result'][0]['age']);//年龄
        $data['beauty']=round($face['result'][0]['beauty'],2).'%';//魅力指数
        print_r($data);
    }
}


