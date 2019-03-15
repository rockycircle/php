<?php
namespace Rbac\C\DataConverter;
class DataConverterForStudent {

    public static $field = array(
        array("id" => "", "field" => "is_id", "name" => "学号"),
        array("id" => "", "field" => "is_name", "name" => "姓名"),
        array("id" => "", "field" => "is_sex", "name" => "性别"),
        array("id" => "", "field" => "ico_id", "name" => "学部"),
        array("id" => "", "field" => "id_id", "name" => "专业"),
        array("id" => "", "field" => "icl_id", "name" => "班级"),
        array("id" => "", "field" => "is_grade", "name" => "年级"),
    );
    
    /**
     * 返回Field
     * @return type
     */
    public static function getField() {
        return self::$field;
    }

    public static function getSqlField() {
        return self::$sqlField;
    }
    
    /**
     * 导入数据
     * @param type $data
     */
    public function importData($data){
        $studentModel = D("InfoStudent");
        $collegeModel = D("InfoCollege");
        $disciplineModel = D("InfoDiscipline");
        $classModel = D("InfoClass");
        
        foreach($data as $key=>$v){
           // print_r($v);
           $studentInfo = $v;
           //插入数据
           $college = $collegeModel->where(array("ico_name"=>$v["ico_id"]))->find();
           $studentInfo["ico_id"] = $college["ico_id"];
           $discipline = $disciplineModel->where(array("id_name"=>$v["id_id"],"id_time"=>$v["is_grade"]))->find();
           $studentInfo["id_id"] = $discipline["id_id"];
           //班级信息
           $number = intval($v["icl_id"]);
           $classData = array("icl_number"=>$number,
                               "id_id"=>$discipline["id_id"],
                               "ico_id"=>$college["ico_id"],
                               "icl_year"=>$v["is_grade"]);
           $classInfo = $classModel->where($classData)->find();
           if(empty($classInfo)){
              //插入数据
               $classId = $classModel->add($classData);
           }else{
               $classId = $classInfo["icl_id"];
           }
           $studentInfo["icl_id"] = $classId;
           //插入学生数据
           $studentInfo["is_study_date"] = $v["is_grade"]."-09-01";
           $studentModel->add($studentInfo);
           //echo $studentModel->getLastSql();exit;
        }
        return count($data);  //返回记录数据
    }
	
	/**
     * 重置鍵值
     * @param type $sqlField   需要置換key的數組
     * @param type $arr       原值
     * @param type $key       匹配Key值
     * @param type $tplField  平臺數據中的field
     * @param type $value      指定key的值
     */
    public static function _restetKey($sqlField, $arr, $key, $tplField, $value) {
        for ($j = 0; $j < count($sqlField); $j++) {
            if ($key == $sqlField[$j][$tplField]) {
                $arr[$sqlField[$j]["sqlField"]] = $value;
            }
        }
        return $arr;
    }

}





?>
