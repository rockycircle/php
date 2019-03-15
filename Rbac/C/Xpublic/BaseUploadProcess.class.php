<?php

namespace Rbac\C\Xpublic;

/**
 * 公共上传文件解析类
 * @author Administrator
 */
class BaseUploadProcess {

    /**
     * 文件上传处理 
     * @param type $file  文件地址
     * @return boolean
     */
    public function uploadFile($file) {
        $upfile = $file;
        //获取数组里面的值 
        $name = $upfile["name"]; //上传文件的文件名 
        $type = $upfile["type"]; //上传文件的类型 
        $size = $upfile["size"]; //上传文件的大小 
        $tmp_name = $upfile["tmp_name"]; //上传文件的临时存放路径 
        $sExtension = substr($name, (strpos($name, '.') + 1)); //找到扩展名
        $sExtension = strtolower($sExtension);
        $sFileName = date("Y-m-d-H-i-s") . "." . $sExtension; //这样就是我们的新文件名了，全数字的不会有乱码了哦。        
        //判断是否为文本和excel文件 
        switch ($type) {
            case 'text/plain':$okType = true;
                break;
            case 'application/vnd.ms-excel':$okType = true;
                break;
        }
        if ($okType) {
            $error = $upfile["error"]; //上传后系统返回的值 
            //把上传的临时文件移动到./Runtime/Temp/目录下面 
            move_uploaded_file($tmp_name, './Runtime/Temp/' . $sFileName);
            $filePath = './Runtime/Temp/' . $sFileName;
            if ($error == 0) {
                return array("filename" => $filePath, "sExtension" => $sExtension);
                //echo "文件上传成功啦！";
            } elseif ($error == 1) {
                //echo "超过了文件大小，在php.ini文件中设置";
                return false;
            } elseif ($error == 2) {
                //echo "超过了文件的大小MAX_FILE_SIZE选项指定的值";
                return false;
            } elseif ($error == 3) {
                //echo "文件只有部分被上传";
                return false;
            } elseif ($error == 4) {
                //echo "没有文件被上传";
                return false;
            } else {
                return false;
                // echo "上传文件大小为0";
            }
            return array("filename" => $filePath, "sExtension" => $sExtension);
        } else {
            //$this->ajaxRespond(array("success" =>false));
            return false;
        }
    }

    /**
     * 解析CSV文件，返回数组
     * @param type $filename
     * @return array
     */
    public function processCsvFile($filename, $separator) {

        $erp_pre_orders = array();
        $file = fopen($filename, 'r');
        while ($data = fgetcsv($file, 0, $separator)) { //每次读取CSV里面的一行内容
            $data = $this->str_to_utf8($data);
            array_push($erp_pre_orders, $data);  //向csvArray数组中尾部添加值
        }
        fclose($file);
        return $erp_pre_orders;
    }

    /**
     * 导入excel内容转换成数组 
     * @param type $filePath
     * @return type
     */
    public function processExcelile($filePath) {

        Vendor('PHPExcel.PHPExcel');
        Vendor('PHPExcel/PHPExcel/Writer/Excel5.php');
        Vendor('PHPExcel/PHPExcel/Writer/Excel2007.php');
        Vendor('PHPExcel/PHPExcel/Cell.php');
        $PHPExcel = new \PHPExcel();
        /*         * 默认用excel2007读取excel，若格式不对，则用之前的版本进行读取 */
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                //读取失败
                return false;
            }
        }
        $PHPExcel = $PHPReader->load($filePath);
        $currentSheet = $PHPExcel->getSheet(0);  //读取excel文件中的第一个工作表
        $allColumn = $currentSheet->getHighestColumn(); //取得最大的列号
        $allColumn=\PHPExcel_Cell::columnIndexFromString( $allColumn);
        
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        $erp_pre_orders = array();  //声明数组
        /*         * 从第二行开始输出，因为excel表中第一行为列名 */
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            /*  * 从第A列开始输出 */
            $row = array();
            for ($currentColumn =0; $currentColumn <= $allColumn; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue(); 
                //var_dump($val);
                if(is_object($val))  {
                    $val =$val->__toString();    
                }
                //$val = $this->str_to_utf8($val);
                array_push($row, $val);
            }
            array_push($erp_pre_orders, $row);
          
        }
        return $erp_pre_orders;
    }

    /**
     * 从上传的文件中获取到数据,并将数据写到临时文件当中
     * @param 上传的文件表单 FILES
     * @param type $records
     * @param type $separator  csv文件分割符
     * @return array(data=> ,tempFilePath=> ) data:读取的标题段    将数据保存在临时目录下的路径
     */
    public function getDataByUploadFile($records, $separator) {
        foreach ($records as $key => $value) {
            if ($this->uploadFile($value)) {
                $file = $this->uploadFile($value);
                if ($file["sExtension"] == "csv" || $file["sExtension"] == "txt") {
                    $erp_pre_orders = $this->processCsvFile($file["filename"], $separator);
                } else {
                    $erp_pre_orders = $this->processExcelile($file["filename"]);
                }
                //将数据转换成json格式存入
                $tempFilePath = $this->writeFileToTxt(json_encode($erp_pre_orders));
                return array("data" => $erp_pre_orders[0], "tempFilePath" => $tempFilePath);
            } else {
                return null;
            }
        }
    }

    /**
     *  向文本格式的文件中写入数据
     * @param type $str 内容
     * @return string
     */
    public function writeFileToTxt($str) {
        $tempFilePath = "./Runtime/Temp/temp" . date("Y-m-d-H-i-s") . txt;
        $open = fopen($tempFilePath, "a");
        fwrite($open, $str);
        fclose($open);
        return $tempFilePath;
    }

    /**
     * 读取文件编码，并以utf8保存
     * @param type $str
     * @return type
     */
    function str_to_utf8($str) {

        if (mb_detect_encoding($str, 'UTF-8', true) === false) {
            $str = utf8_encode($str);
        }
        return $str;
    }

    
    /**
     * 匹配编码
     * @staticvar array $enclist
     * @param type $string
     * @param type $enc
     * @param type $ret
     * @return boolean
     */
    function mb_detect_encoding($string, $enc = null, $ret = null) {

        static $enclist = array(
    'UTF-8', 'ASCII',
    'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
    'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
    'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
    'Windows-1251', 'Windows-1252', 'Windows-1254',
        );
        $result = false;
        foreach ($enclist as $item) {
            $sample = iconv($item, $item, $string);
            if (md5($sample) == md5($string)) {
                if ($ret === NULL) {
                    $result = $item;
                } else {
                    $result = true;
                }
                break;
            }
        }

        return $result;
    }

   

}

?>
