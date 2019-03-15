<?php
namespace Rbac\C\Xpublic;
// 登录模块
class DownExcel{
    
    private $objExcel;
    private $objWriter;
    private $objActSheet;
    private $rowNum = 1;
    private $rowSursor = 0;
    
    public function __construct(){
        
    }
    // 创建文件格式写入对象实例
    public function instantiationPHPExcel(){
        Vendor('PHPExcel.PHPExcel');
        Vendor('PHPExcel/PHPExcel/Writer/Excel5.php');
        Vendor('PHPExcel/PHPExcel/Writer/Excel2007.php');
        Vendor('PHPExcel/PHPExcel/Cell.php');
        
        $this->objExcel = new \PHPExcel();
        //$this->objWriter = new \PHPExcel_Writer_Excel5($this->objExcel);   // 用于其他版本格式
        $this->objWriter = new \PHPExcel_Writer_Excel2007($this->objExcel);  // 用于 2007 格式
    }
    
    /**
     * 生成当前的sheet
     */
    public function sheet(){
        $this->objExcel->setActiveSheetIndex(0);
        $this->objActSheet = $this->objExcel->getActiveSheet();
        
        //设置当前活动sheet的名称
        $this->objActSheet->setTitle('Sheet');
    }
    
    /**
     * excel的标题
     * @param array $head
     */
    public function setTitle($head){
        // 设置头
        if(! empty($head)){
            $this->rowSursor += 1;
            for ($i = 0; $i < count($head); $i++){
                $c = chr(65+$i);
                $this->objActSheet->setCellValue($c.'1', $head[$i]);
                
                $this->objActSheet->getColumnDimension($c)->setAutoSize(true);  //设置宽度
            }
        }
    }
    /**
     * excel的数据
     * @param array $data
     */
    public function addData($data){
        // 设置内容
        /* if(! empty($data)){
            for ($i = 0; $i < count($data); $i++){
                $this->rowNum += $i;
                
                for($j = 0; $j < count($data[$i]); $j++){
                    
                    $c = chr(65+$j);
                    $this->objActSheet->setCellValue($c.($i+$this->rowNum), $data[$i][$j]);
                }
            }
        } */
        $this->rowNum = $this->rowSursor;
        if(! empty($data)){
            foreach ($data as $value){
                $this->rowSursor += 1;
                
                $j = 0;
                foreach ($value as $v){
                    
                    $c = chr(65+$j);
                    $this->objActSheet->setCellValue($c.($i+$this->rowSursor), $v);
                    
                    $j++;
                }
            }
        }
        
        
    }
    
    /**
     * 直接下载
     */
    public function down($title = ''){
        $outputFileName = $title . ".xls";
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        ///header('Content-Disposition:attachment;filename="' . $outputFileName . '"');  //到文件
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');  //到浏览器
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $this->objWriter->save('php://output');
    }
    
    /**
     * 在服务器 重定向下载
     * @param unknown $title
     * @param unknown $zipFalg
     */
    public function outPut($title,$zipFalg){
        $title = $title . ".xls";
        $filePath = APP_PATH.'/Runtime/Temp/'.$title;
        $this->objWriter = \PHPExcel_IOFactory::createWriter($this->objExcel, 'Excel5');
        $this->objWriter->save($filePath); 
        if($zipFalg){
            $zip = $filePath.'.zip';
            exec("zip -j " . $zip . " " . $filePath); //调用linux系统命令
            sleep(1);
            header("location:".$zip);
        }else{
            header("location:".$filePath);
        }
    }
    
    /**
     * 在服务器 重定向下载
     * @param unknown $title
     * @param unknown $zipFalg
     */
    public function outPutFile($title){
        $title = $title . ".xls";
        $filePath = APP_PATH.'/Runtime/Temp/'.$title;
        $this->objWriter = \PHPExcel_IOFactory::createWriter($this->objExcel, 'Excel5');
        $this->objWriter->save($filePath);
    }
    
    /**
     * 直接下载文件
     * @param unknown $name
     * $file_dir : 路径
     * $name : 文件名
     */
    public static function downloads($file_dir, $name){
        if (!file_exists($file_dir.$name)){
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit;
        } else {
            $file = fopen($file_dir.$name,"r");
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".filesize($file_dir . $name));
            Header("Content-Disposition: attachment; filename=".$name);
            echo fread($file, filesize($file_dir.$name));
            fclose($file);
        }
    }
}