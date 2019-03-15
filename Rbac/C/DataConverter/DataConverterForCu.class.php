<?php
namespace Rbac\C\DataConverter;
class DataConverterForCu {

    public static $field = array(
        array("id" => "", "field" => "cu_name", "name" => "课程名称"),
        array("id" => "", "field" => "cu_point", "name" => "学分"),
        array("id" => "", "field" => "cu_time", "name" => "课时"),
        //array("id" => "", "field" => "teacher", "name" => "任课老师"),
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
       
        $curriculumModel = M("info_curriculum");
        foreach($data as $v){
           $cuInfo = $v;
           $cuInfo["cu_name"] = trim($cuInfo["cu_name"]);
           $cuInfo["ico_id"] = 10000;
           $cu = $curriculumModel->where(array("cu_name"=>$cuInfo["cu_name"]))->find();
           if(empty($cu)){
               $curriculumModel->add($cuInfo);
           }
        }
      /**  $pinyin = new \Rbac\C\Xpublic\PinYin();
        $userModel = M("rbac_user");
        $roleUserModel = M("rbac_role_user");
        //导入教师数据
        foreach($data as $v){
            if(!empty($v["teacher"])){
                //插入教师数据
                $teacherList = explode(",", $v["teacher"]);
                if(!empty($teacherList)){
                    foreach($teacherList as $teacher){
                        $account =  $pinyin->pinyin($teacher, 'UTF8');
                        $user = $userModel->where(array("account"=>$account))->find();
                        if(empty($user)){
                            //插入数据
                            $userInfo["account"] = $account;
                            $userInfo["nickname"] = $teacher;
                            $userInfo["password"] = md5("123456");
                            $userInfo["create_time"] = time();
                            $id = $userModel->add($userInfo);
                            $roleUserModel->add(array("user_id"=>$id,"role_id"=>3));
                        }
                    }
                }
            }
        }**/
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
