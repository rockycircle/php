<?php
/**
 * 产品管理里的：
 * 产品基本信息,
 * 产品列表
 */
namespace Rbac\Controller;

class ProductController extends ExtendController
{
    public function product()
    {
        $this->display();
    }

    public function prolist()
    {
        $this->display();
    }

    public function index()
    {
        //InfoCollege
        $modelName = $_GET["cmd"];
        $map = array();
        $filterFunction= "_filter".$modelName;
        if(method_exists($this,$filterFunction)){
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
//        $processFunction = "_process".$modelName;
//        if(method_exists($this,$processFunction)){
//            $data = $this->$processFunction($data);
//        }
        //过滤数据

        $this->ajaxReturn($data);
    }
//    protected function _processProduct()
//    {
//
//    }
//    protected function _processProductUser()
//    {
//
//    }
}