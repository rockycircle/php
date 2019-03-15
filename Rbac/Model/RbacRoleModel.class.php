<?php
namespace Rbac\Model;

/**
  +----------------------------------------------------------
 * 角色模型
  +----------------------------------------------------------
 * Description of RoleModel
  +----------------------------------------------------------
 * @author Jonas_yang 2015-11-26
  +----------------------------------------------------------
 */
class RbacRoleModel extends ExtendModel{
     public $_validate = array(
         array("name","require","角色名必须"),
     );
     
     public $_auto = array(
         array("create_time","time",self::MODEL_INSERT,"function"),
         array("update_time","time",self::MODEL_UPDATE,"function")
     );
     
     function getGroupAppList($groupId){
        $rs = $this->db->query('select b.id,b.title,b.name from rbac_access as a ,rbac_node as b where a.node_id=b.id and  b.pid=0 and a.role_id='.$groupId.' '); 
        return $rs;
     }
     
    function getGroupModuleList($groupId,$appId) {
        $table ='rbac_access';
        $rs = $this->db->query('select b.id,b.title,b.name from '.$table.' as a ,rbac_node as b where a.node_id=b.id and  b.pid='.$appId.' and a.role_id='.$groupId.' ');
        return $rs;
    }
    
    function getGroupActionList($groupId,$moduleId) {
        $table = 'rbac_access';
        $rs = $this->db->query('select b.id,b.title,b.name from '.$table.' as a ,rbac_node as b where a.node_id=b.id and  b.pid='.$moduleId.' and  a.role_id='.$groupId.' ');
        return $rs;
    }
    
    function getGroupUserList($groupId) {
        $table = 'rbac_role_user';
        $rs = $this->db->query('select b.id,b.nickname,b.email from '.$table.' as a ,rbac_user as b where a.user_id=b.id and  a.role_id='.$groupId.' ');
        return $rs;
    }

}

?>
