<?php
namespace Rbac\C\Xpublic;
/**
 * 
 *
 * @author Administrator
 */
//ImportData
class ImportData {

    public $matchFieldParam;

    public function setMatchFieldParam($matchFieldParam) {
        $this->matchFieldParam = $matchFieldParam;
    }

    public function getMatchFieldParam() {
        return $this->matchFieldParam;
    }

    /**
     * 上传数据
     */
    public function step1($records, $platform_filed, $separator) {
        $baseUploadProcess = new BaseUploadProcess();
        $dataSourece = $baseUploadProcess->getDataByUploadFile($records, $separator);
        $dataSourece_field = array();
        for ($i = 0; $i < count($dataSourece["data"]); $i++) {
            $row = array();
            $row["id"] = $i;
            $row["excelField"] = $dataSourece["data"][$i];
            array_push($dataSourece_field, $row);
        }
        $tempFilePath = $dataSourece["tempFilePath"];
      
        $data = $this->matchField($dataSourece_field, $platform_filed);
       
        return array("data" => $data, "tempFilePath" => $tempFilePath);
    }

    /**
     * 数据确认
     */
    public function step2($tempFilePath, $field) {

        $data = $this->getDataByJsonFile($tempFilePath, $field);
        return $data;
    }

    /**
     * @param type $excelField  Excel或者csv文件中第一行字段
     * @param type $platform_filed  平台模板_字段
     * @return array  重新匹配过的字段   array("excelField"=> ,"id"=> ,"field" => )
     */
    public function matchField($excelField, $platform_filed) {
        $field = array();
        for ($i = 0; $i < count($excelField); $i++) {
            $row = array();
            $row["excelField"] = $excelField[$i]["excelField"];
            $row["id"] = trim($excelField[$i]["id"]);
            foreach($platform_filed as $filed)
            {
              $res = $this->str_equal($excelField[$i]["excelField"],$filed[$this->getMatchFieldParam()]);
               if ($res!==false) {
                    $row["field"] = trim($filed["field"]);
                    break;
               }
              else
              {
                  $row["field"] = ""; 
              }
            }
            $field[$i] = $row;
        }
        return $field;
    }

    /*     * 经过用户字段匹配确认之后的新全部数据
     * @param type $data  excel或者csv中读取到的数据
     * @param type $field  匹配完整的数据段，数据格式 ：json数据： [{field:},{id:}] 
     * @return 返回新的数据
     */

    public function getNewData($data, $field) {
      
        //$data = session("upload_erp_pre_orders");
        $field = json_decode($field);
        //print_r($field);
        $newfiled = array();
        for ($i = 0; $i < count($field); $i++) {
            $row = array();
            if ($field[$i]->field != '') {
                $row["field"] = $field[$i]->field;
                $row["id"] = $field[$i]->id;
                $newfiled[$i] = $row;
            }
        }
        $newdata = array();
        for ($i = 1; $i < count($data); $i++) {
            $row = array();
            for ($j = 0; $j < count($data[$i]); $j++) {
                if ($newfiled[$j]["id"] == $j) {
                    $row[$newfiled[$j]["field"]] = $data[$i][$j];
                }
            }
            $newdata[$i - 1] = $row;
        }
     //   print_r($newdata);
        return $newdata;
    }

    /**
     * 字符串比较函数
     * @param type $str1 字符1
     * @param type $str2  字符2
     * @return boolean
     */
    public function str_equal($str1, $str2) {
        //首先对字符串进行空格过滤,并对字符串转义大写
        $str1 = strtoupper(trim($str1));
        $str2 = strtoupper(trim($str2));
        if ($str1 === $str2) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据文件路径获取json文件中的数据
     * @param type $file
     * @return type
     */
    public function getDataByJsonFile($file, $field) {
        $json = file_get_contents($file);
        $json = json_decode($json,true);
        $data = $this->getNewData($json, $field);
        return $data;
    }

}

?>
