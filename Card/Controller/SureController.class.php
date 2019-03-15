<?php

namespace Card\Controller;

/**
 * 确认订单控制器
 */
class SureController extends BaseController {

    public function index() {
        $productId = $_GET["product_id"];  //获取产品信息
        $num = $_GET["num"];  //产品数量
        $productModel = M('product');
        $products = $productModel->where(array('product_id' => $productId))->find();
        $products["product_num"] = $num;
        $products['product_sum_price'] = round($num*$products["product_price"],2);
        $ordersModel = M("orders");
        if (empty($_GET['oid'])) {
            //创建订单信息
            $ordersData = array(
                "category_id" => $products["category_id"],
                "orders_num" => $num,
                "orders_price" => $num * $products["product_price"],
                "orders_status" => 1,
                "orders_create" => date("Y-m-d H:i:s"),
                "orders_pay" => "微信支付",
                "customer_id" => \Fn\App::getAuthUserId(),
                "product_id" => $products["product_id"]
            );
            $ordersId = $ordersModel->add($ordersData);
            $this->assign('is_hasorder', '2');
        }else{
            $ordersId = $_GET['oid'];
            $ordersData = $ordersModel->where(array("orders_id"=>$ordersId))->find();
            $this->assign('is_hasorder', '1');
        }
        $products["orders_id"] = $ordersId;
        $products['orders_create'] = $ordersData['orders_create'];
        $this->assign('products', $products);
        $this->display('sure');
    }
    
    /**
     * 取消订单
     */
    public function canceOrders(){
        $oid = $_GET["oid"];
        $ordersModel = M("orders");
        $map["orders_id"] = $oid;
        $res = $ordersModel->where($map)->save(array("orders_status"=>8));
        if($res!==false){
            //取消成功
        }else{
            //取消失败，对应的错误
        }
    }

}
