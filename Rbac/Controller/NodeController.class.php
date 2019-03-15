<?php
namespace Rbac\Controller;
/**
  +----------------------------------------------------------
 * 节点控制器
  +----------------------------------------------------------
 * Description of NodeController
  +----------------------------------------------------------
 * @author Jonas_yang 2015-11-27
  +----------------------------------------------------------
 */
class NodeController extends ExtendController {
   
    /**
     * 进入权限页面
     */
    public function node(){
        $this->display();
    }
    
    /**
     * 获取目录树
     */
    public function tree() {
        if(empty($_POST["id"])){
          $map["pid"] = 0;
        }else{
          $map["pid"] = $_POST["id"]; 
        }
        $gid= empty($_GET["gid"])?0:$_GET["gid"]; //获取用户组
        $accessList = M("RbacAccess")->field("role_id,node_id")->where(array("role_id"=>$gid))->select();
        $data = D("RbacNode")->field("id,title text,level,status,remark,name,pid")->where($map)->select();
        foreach( $data as $key=>$node){
            $node["state"] ="open";
            $node["checked"] = false;
            if($node["level"]==1||$node["level"]==2){
                $node["state"] ="closed";
            }
            //对比node
            foreach($accessList as $access){
                if($access["node_id"]==$node["id"]){
                  $node["checked"] = true;
                  break;
                }
            }
            $data[$key] = $node;
        }
        $this->ajaxReturn($data);
    }
    
    /**
     * 更新节点数据
     */
    public function save(){
        $data = $_POST;
        $map["id"] = $data["id"];
        $nodeModel = M("RbacNode");
        $res = $nodeModel->where($map)->save($data);
        if($res!==false){
           $resultData["success"] = 1;
           $resultData["msg"] = "修改成功";
        }else{
           $resultData["success"] = 2;
           $resultData["msg"] = "修改失败";
        }
        $this->ajaxReturn($resultData);
    }
    
    
    /**
     * 删除节点
     */
    public function remove(){
      $data = $_POST;
      $map["id"] = $data["id"];
      $nodeModel = M("RbacNode");
      $accessModel = M("RbacAccess");
      $res = $nodeModel->where($map)->delete();
      //删除
      $accessModel->where(array("node_id"=>$data["id"]))->delete();
      if($res!==false){
           $resultData["success"] = 1;
           $resultData["msg"] = "删除成功";
      }else{
           $resultData["success"] = 2;
           $resultData["msg"] = "删除失败";
      }
      $this->ajaxReturn($resultData);
    }    

}

?>
