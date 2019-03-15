<?php
namespace Rbac\Model;
/**
  +----------------------------------------------------------
 * 内容模型类
  +----------------------------------------------------------
 * Description of CmsContentModel
  +----------------------------------------------------------
 * @author Jonas_yang 2016-1-27
  +----------------------------------------------------------
 */
class CmsContentModel extends ExtendModel{
    //自动填充
     public $_auto = array(
         array("content_last_date","todate",self::MODEL_INSERT,"function"),
         array("content_last_time","time",self::MODEL_INSERT,"function"),
         array("content_last_date","todate",self::MODEL_UPDATE,"function"),
         array("content_last_time","time",self::MODEL_UPDATE,"function"),
         array("content_count",0),
         array('content_is_release',0)
     );
}

?>
