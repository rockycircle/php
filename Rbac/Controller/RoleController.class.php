<?php
namespace Rbac\Controller;

/**
  +----------------------------------------------------------
 * 角色管理控制器
  +----------------------------------------------------------
 * Description of RoleController
  +----------------------------------------------------------
 * @author Jonas_yang 2015-11-26
  +----------------------------------------------------------
 */
class RoleController extends ExtendController{
    /**
     * 进入权限页面
     */
    public function role(){
        $this->display();
    }
    
    /**
     * 根据用户组ID获取到用户信息
     * @return type
     */
    public function user(){
         //读取系统的用户列表
        $user    =   D("RbacUser");
        $userlist=$user->field('id,account,nickname')->select();
        $group  = D("RbacRole");
        //获取当前用户组信息
        $groupId =  isset($_GET['id'])?$_GET['id']:'';
        if(!empty($groupId)) {
            //获取当前组的用户列表
            $selectUserList	=	$group->getGroupUserList($groupId);
            foreach($userlist as $key => $user ){
                 $user["check"] = 2;
                  foreach($selectUserList as $u){
                      if($u["id"]==$user["id"]){
                          $user["check"] = 1;  //表示选中
                          break;
                      }
                  }
                $userlist[$key] = $user;
            }
        }
         $this->ajaxReturn($userlist);
    }
    
    /**
     * 更新用户
     */
    public function saveUser(){
        //获取用户组信息
        $groupId = $_POST["groupId"];
        $userIds = $_POST["userIds"];
        $userIds = json_decode($userIds,true);
        //先删除权限表中的所有数据
        $roleUserModel = M("RbacRoleUser");
        $roleUserModel->where(array("role_id"=>$groupId))->delete();  //删除数据
        //添加数据
        foreach($userIds as $uid){
            $data["role_id"] = $groupId;
            $data["user_id"] = $uid;
            $roleUserModel->add($data);
        }
        $resultData["success"] = 1;
        $resultData["msg"] = "保存成功";
        $this->ajaxReturn($resultData);
        
    }
    
    
    /**
     * 更新授权
     */
    public function saveRole(){
        //获取用户组信息
        $groupId = $_POST["groupId"];
        $nodeIds = $_POST["nodeIds"];
        $nodeIds = json_decode($nodeIds,true);
        //先删除权限表中的所有数据
        $accessModel = M("RbacAccess");
        $nodeModel = M("RbacNode");
        $accessModel->where(array("role_id"=>$groupId))->delete();  //删除数据
        //添加数据
        foreach($nodeIds as $nid){
            $data["node_id"] = $nid;
            $node = $nodeModel->field("level,pid")->where(array("id"=>$nid))->find();
            $data = array_merge($data,$node);
            $data["role_id"] = $groupId;
            $accessModel->add($data);
        }
        $resultData["success"] = 1;
        $resultData["msg"] = "保存成功";
        $this->ajaxReturn($resultData);
    }
    
    /**
     * 删除组
     */
    public function delete(){
        $roleId = $_POST["ids"];
        D("RbacRole")->where(array("id"=>array("in",$roleId)))->delete();
        D("RbacAccess")->where(array("role_id"=>array("in",$roleId)))->delete();
        D("RbacRoleUser")->where(array("role_id"=>array("in",$roleId)))->delete();
        $resultData["success"] = 1;
        $resultData["msg"] = "删除成功";
        $this->ajaxReturn($resultData);
    }

    
}

?>
