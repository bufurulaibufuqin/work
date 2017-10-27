<?php
namespace app\excel\controller;

use think\Db;

class Index
{
    public function index(){
//        self::excels();
        return view();
    }
    /*
     * 导出excel数据表格
     */
    public function excels(){
        $userData=Db::table('user')->select();
        if(!empty($userData)){
            $objPHPExcel=new \PHPExcel();
            //设置居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置excel列名
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','用户ID');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','用户组ID');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','用户名');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','密码');

            //背景填充颜色
            $objPHPExcel->getActiveSheet()->getStyle( 'A1:D1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle( 'A1:D1')->getFill()->getStartColor()->setARGB('FF808080');

            //把数据循环写入excel中
            foreach($userData as $key => $value){
                $key+= 2;   //从第二行开始填充
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value['u_id']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['g_id']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['name']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['password']);
            }
            //设置默认字体
            $objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);

            //设置列宽
            $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            //导出代码
            $objPHPExcel->getActiveSheet() -> setTitle('订单数据');
            $objPHPExcel-> setActiveSheetIndex(0);

            $objWriter=\PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            $filename = "用户数据.xlsx";
            ob_end_clean();//清除缓存以免乱码出现

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter -> save('php://output');
        }
    }
    public function test(){

        if (!empty($_FILES)) {

            $file_name = $_FILES['photo']['name'];

            $tmp_name = $_FILES["photo"]["tmp_name"];

            move_uploaded_file($tmp_name,$file_name);

            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));//判断导入表格后缀格式

            if ($extension == 'xlsx') {

                $objReader =\PHPExcel_IOFactory::createReader('Excel2007');

                $objPHPExcel =$objReader->load($file_name);

            } else if ($extension == 'xls'){

                $objReader =\PHPExcel_IOFactory::createReader('Excel5');

                $objPHPExcel =$objReader->load($file_name);
            }

            $sheet =$objPHPExcel->getSheet(0);

            $highestRow = $sheet->getHighestRow();//取得总行数

            $highestColumn =$sheet->getHighestColumn(); //取得总列数

            for ($i = 2; $i <= $highestRow; $i++) {

//                $data['u_id'] =$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();//主键忽略

                $data['g_id'] =$objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue();

                $data['name'] =$objPHPExcel->getActiveSheet()->getCell("C" .$i)->getValue();

                $data['password'] = $objPHPExcel->getActiveSheet()->getCell("D". $i)->getValue();

                db::table('user')->insert($data);

            }

            echo ('导入成功!');

        } else {

            echo ("请选择上传的文件");

        }
    }
}