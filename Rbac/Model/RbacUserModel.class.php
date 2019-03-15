<?php
namespace Rbac\Model;
/**
 * Description of UserModel
 * @author Jonas_yang
 */
class RbacUserModel extends ExtendModel{
    //参数格式  验证字段，验证规则，验证提示，[验证条件,附加规则,验证时间]
    //self::EXISTS_VALIDATE  存在字段就验证
    //新增字段的时候验证
    public $_validate = array(
       array('account','/^[a-z]\w{3,}$/i','帐号格式错误'),
       array('password','require','密码必须'),
       array('nickname','require','昵称必须'),
       array('newpassword','require','确认密码必须'),
       array('newpassword','password','确认密码不一致',self::EXISTS_VALIDATE,'confirm'),
       array('account','','账号已经存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
    );
    
    /**
     *自动填充
     * function 表示规则是一个函数，例如:time()
     * MODEL_BOTH 表示所有情况都处理
     */
    public $_auto = array(
        array("status","1"),
        array("password","md5",self::MODEL_BOTH,'function'),
        array("create_time","time",self::MODEL_INSERT,'function'),
        array("uptdate_time","time",self::MODEL_UPDATE,'function'),
    );
    
    /**
     * 获取用户列表
     */
    public function getUserList(){
        return $this->field("id,nickname")->select();
    }
    
    public function getNameById($id){
       $user =  $this->field("nickname")->find($id);
       if(empty($user)){
           return "";
       }else{
           return $user["nickname"];
       }
    }
}

?>
