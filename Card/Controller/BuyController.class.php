<?php
namespace Card\Controller;
use Think\Controller;

class BuyController extends BaseController {

    public function index()
    {
        $categoryModel=M('product_category');
        $categorys=$categoryModel->where(array('category_status'=>1))->select();
        $this->assign('categorys',$categorys);
        $saller=array();
            foreach($categorys as $category){
                $saller[$category[category_id]]=M('product_user')->where(array('category_id'=>$category[category_id],'product_status'=>1))->count();
        }
        $allsal=array();
        foreach($categorys as $category){
            $allsal[$category[category_id]]=M('orders_product')->where(array('category_id'=>$category[category_id],'product_status'=>9))->count();
        }
        $this->assign('saller',$saller);
        $this->assign('allsal',$allsal);
        $this->display('buy');
    }

}