<?php
/**
 * Created by PhpStorm.
 * User: scotte
 * Date: 2017/7/19
 * Time: 1:36
 */

namespace Rbac\Controller;


class DisputeController extends ExtendController
{
    public function index()
    {//覆盖继承类的index方法，取数据

        $map = array();

        $filterFunction= "_filterOrders";
        if(method_exists($this,$filterFunction)){
            $map = $this->$filterFunction();
        }
        $model = D('orders');
//        $condition['orders_status']=3;
//        $mod=$model->where($condition)->select();
//
//        var_dump($model);die;
        $data = null;
        if (!empty($model)) {
                $data=$this->_liast($model, $map);
 //            $data = $this->where($condition)->_list($model, $map);
//            $aaa=$data[rows][0][orders_status];
//            $condition[rows][orders_status]=3;

//            $aaa=$data->where()->select();
//            var_dump($aaa);
//
//            ->where('orders_status=3')->select()

        }

        if ($data["rows"] == null) {
            $data["rows"] = array();

        }
//        var_dump($data);die;
        $this->ajaxReturn($data);
    }


    public function _liast($model, $map, $sortBy = '', $asc = false) {
        //排序
        if (isset($_REQUEST['_order'])) {
            $order = $_REQUEST['_order'];
        } else {
            $order = !empty($sortBy) ? $sortBy : $model->getPk();
        }
        //排序方式，
        if (isset($_REQUEST["_sort"])) {
            $sort = $_REQUEST["_sort"] ? 'asc' : 'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        $voList = null;
        //取出满足条件的记录数据
        $count = $model->where($map)->count($model->getPk());

        if ($count > 0) {
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
            //分页查询出数据
            $map['orders_status']=3;
            $voList = $model->where($map)->order($order . " " . $sort)->limit($offset . " , " . $pageSize)->select();
        }
        return array("total" => $count, "rows" => $voList);
    }

    public function dispute()
{
    $this->display();
}

    protected function _filterOrders(){
        $map = array();

            if (!empty($_POST["orders_id"])) {
                $map["orders_id"] = array("like", trim($_POST["orders_id"]) . "%");
            }
            if (!empty($_POST["customer_id"])) {
                $map["customer_id"] = $_POST["customer_id"];
            }
            if (!empty($_POST["orders_status"])) {
                $map["orders_status"] = $_POST["orders_status"];
            }

        return $map;
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