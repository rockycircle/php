<?php
/**
 * Product表的验证
 */

namespace Rbac\Model;


class ProductModel extends ExtendModel
{
    public $_validate=array(
      array("product_title","require","产品名称必须"),
    );
    
    /**
     * 产品列表
     */
    public function getProductList(){
        $map["product_status"] = 1;  
        $list = $this->field("product_id,product_title")
                     ->where($map)
                     ->order("product_id")
                     ->select();
        if(!empty($list)){
           return $list; 
        }else{
            return null;
        }
    }
}