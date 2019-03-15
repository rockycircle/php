<?php
namespace Card\Controller;

class OrdersController extends BaseController{
    public function index()
    {
        $customerId = \Fn\App::getAuthUserId();

        $orders=D('orders')->where(array('customer_id'=>$customerId))->select();
        $map_img=array();
        foreach ($orders as $order){
            $map_img[$order['orders_id']]=M('product_category')->where(array('category_id'=>$order['category_id']))->find();
        }
        foreach ($orders as $order){
            $map_title[$order['orders_id']]=M('orders_product')->where(array('orders_id'=>$order['orders_id']))->find();
        }
        $this->assign('map_title',$map_title);
        $this->assign('map_img',$map_img);
        $this->assign('orders',$orders);
        $this->display();
    }

    public function mySale()
    {
        $customerId = \Fn\App::getAuthUserId();
        $proUser=M('product_user');
        $products=$proUser->where(array('customer_id'=>$customerId))->select();
        $map_img=array();
        foreach($products as $product){
            $map_img[$product['product_customer_id']]=M('product_category')->where(array('category_id'=>$product['category_id']))->find();
        }

        $this->assign('map_img',$map_img);
        $this->assign('products',$products);
        $this->display();
    }
    
}