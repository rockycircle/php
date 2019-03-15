<?php
/**
 * Created by PhpStorm.
 * User: scotte
 * Date: 2017/7/14
 * Time: 11:04
 */

namespace Rbac\Controller;


class CustomerInfoController extends ExtendController
{
    public function customerinfo()
    {
        $this->display();
    }

    /**
     * 显示数据
     */
    public function index()
    {
        $modelName = $_GET["cmd"];
        $map = array();
        $filterFunction = "_filter" . $modelName;
        if (method_exists($this, $filterFunction)) {
            $map = $this->$filterFunction();
        }
        $model = D($modelName);
        $data = null;
        if (!empty($model)) {
            $data = $this->_list($model, $map);
        }

        if ($data["rows"] == null) {
            $data["rows"] = array();
        }
        $processFunction = "_process" . $modelName;
        if (method_exists($this, $processFunction)) {
            $data = $this->$processFunction($data);
        }
        //过滤数据
        $this->ajaxReturn($data);
    }

//    protected function _filterCustomer()
//    {
//        $map = array();
//        if (!empty($_POST["customer_title"])) {
//            $map["customer_title"] = $_POST["customer_title"];
//        }
//        return $map;
//    }
    protected function _filterCustomer(){
        $map = array();

        if(!empty($_POST["customer_title"])){
            $map["customer_title"] = $_POST["customer_title"];
        }
        if(!empty($_POST["wx_code"])){
            $map["wx_code"] = $_POST["wx_code"];
        }
        return $map;
    }

    public function edita()
    {
        $modelName = $_GET["cmd"];
        $map = array();
        $model = D($modelName);
        $id = $model->getPk(); //获取主键
        $map[$id] = $_GET[$id];
        if (!empty($model)) {
            $res = $model->where($map)->save($_POST);
            if ($res !== false) {
                if (function_exists('_editCallback')) {
                    $this->_editCallback();
                }
                $data["success"] = 1;

            } else {
                $data["success"] = 2;
                $data["msg"] = "修改数据失败";
            }
        } else {
            $data["success"] = 2;
            $data["msg"] = "修改数据失败,参数错误";
        }
        $this->ajaxReturn($data);
    }

    public function rel()
    {
        $modelName = $_GET["cmd"];
        $map = array();
        $model = M($modelName);
        $map["customer_id"] = $_POST["customer_id"];
        $price = $_POST["customer_no_balance"]; //解冻金额
        if (!empty($model)) {
            $obj = $model->where($map)->find();
            $obj["customer_no_balance"] = $obj['customer_no_balance']-$price;
            $obj["customer_balance"] = $obj['customer_balance']+$price;
            $res = $model->where($map)->save($obj);
            if ($res !== false) {
                $data["success"] = 1;
            } else {
                $data["success"] = 2;
                $data["msg"] = "解冻金额失败";
            }
        } else {
            $data["success"] = 2;
            $data["msg"] = "解冻金额失败,参数错误";
        }
        $this->ajaxReturn($data);
    }



}