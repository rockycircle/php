<?php
/**
 * 订单管理控制器
 */

namespace Rbac\Controller;


class OrderController extends ExtendController
{
    public function index()
    {//覆盖继承类的index方法，取数据

        $map = array();
        $filterFunction= "_filterOrders";
        if(method_exists($this,$filterFunction)){
            $map = $this->$filterFunction();
        }
        $model = D('orders');
        $data = null;
        if (!empty($model)) {
            $data = $this->_list($model, $map);
        }

        if ($data["rows"] == null) {
            $data["rows"] = array();
        }
        $this->ajaxReturn($data);
    }

    //搜索的过滤数据
    protected function _filterOrders(){
        $map = array();
        if(!empty($_POST["orders_id"])){
            $map["orders_id"] = array("like",trim($_POST["orders_id"])."%");
        }
        if(!empty($_POST["customer_id"])){
            $map["customer_id"] = $_POST["customer_id"];
        }
        if(!empty($_POST["orders_status"])){
            $map["orders_status"] = $_POST["orders_status"];
        }
        return $map;
    }

    public function order()
    {
        $this->display();
    }

    //根据订单信息显示产品信息
    public function ordersProduct() {
        $ordersId = $_GET["orders_id"];
        $ordersProductModel = M("orders_product");
        $map["orders_id"] = $ordersId;

        if(!empty($_POST["product_id"])){
            $map["product_id"] = $_POST["product_id"];
        }
        //创建分页对象，是否设置行数
        if (!empty($_REQUEST['rows'])) {
            $pageSize = $_REQUEST['rows'];
        } else {
            $pageSize = "10";
        }
        if (!empty($_REQUEST['page'])) {
            $pageNow = $_REQUEST['page'];
        } else {
            $pageNow = "1";
        }
        $offset = $pageSize * ($pageNow - 1); //定义偏移量
        $ordersProductInfo = $ordersProductModel->where($map)->limit($offset,$pageSize)->select();
        $productModel = M("product");
        $count = $ordersProductModel->where($map)->count();
        foreach ($ordersProductInfo as $key => $info) {
            //获取产品信息
            $product = $productModel->find($info["product_id"]);
            $info["product_from"] = $product["product_from"];
            $reviewStudentInfo[$key] = $info;
        }
        $data["rows"] = $reviewStudentInfo;
        $data["total"] = $count;

        $this->ajaxReturn($data);
    }

}