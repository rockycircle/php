<?php
namespace Rbac\Model;
class ProductCategoryModel extends ExtendModel
{
    public function getProductCategoryList(){
        $map["category_status"] = 1;  
        $list = $this->field("category_id,category_title")
                     ->where($map)
                     ->order("category_id")
                     ->select();
        if(!empty($list)){
           return $list; 
        }else{
            return null;
        }
    }
}