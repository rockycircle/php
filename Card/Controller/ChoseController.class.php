<?php
/**
 * Created by PhpStorm.
 * User: scotte
 * Date: 2017/7/8
 * Time: 13:18
 */

namespace Card\Controller;


class ChoseController extends BaseController {

    public function index()
    {
        $cid = $_GET["category_id"] ;
        
        $categoryModel=M('product_category');
        $productUserModel = M('product_user');
        $ordersModel  = M("Orders");
        $productModel = M("product");
        
        //获取类目名称
        $categoryInfo = $categoryModel->where(array("category_id"=>$cid))->find();
        //统计类目数量，出售中的数量
        $map1["category_id"] = $cid;
        $map1['product_status'] = 1;
        $productCount = $productUserModel->where($map1)->count();
        $categoryInfo['product_count'] = $productCount;
        //统计已成交数
        $map2["category_id"] = $cid;
        $map2['orders_status'] = 9;
        $orderCount = $ordersModel->where($map2)->count();
        $categoryInfo['orders_count'] = $orderCount;
        //统计每个基本产品的成交信息
        $map3["category_id"] = $cid;
        $productList = $productModel->where($map3)->select();
        
        foreach ($productList as $key=>$product){
            $proCount = $productUserModel->where(array(
                                    "product_id"=>$product["product_id"],
                                    "product_status"=>1
                                    ))->count();
            $oCount =  $ordersModel -> where(array(
                                    "product_id"=>$product["product_id"],
                                    "orders_status"=>9
                                    ))->count();
            $product['product_count'] = $proCount;
            $product["orders_count"] = $oCount;
            $productList[$key] = $product;
        }

        $this->assign('productList',$productList);
        $this->assign('categoryInfo',$categoryInfo);
        $this->display('chose');
    }

}