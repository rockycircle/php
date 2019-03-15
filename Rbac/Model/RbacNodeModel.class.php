<?php
namespace Rbac\Model;
/**
  +----------------------------------------------------------
 * Node模型类
  +----------------------------------------------------------
 * Description of NodeModel
  +----------------------------------------------------------
 * @author Jonas_yang 2016-1-8
  +----------------------------------------------------------
 */
class RbacNodeModel extends ExtendModel {
    //后台验证
    public $_validate = array(
         array("title","require","名称必须"),
         array("name","require","操作名必须"), 
         array("status","require","状态必须"), 
     );
    
    public $_auto = array(
    );
}

?>
