<?php

/**

 */

namespace Rbac\Controller;

class ProductCatgController extends ExtendController {

    public function productCatg() {
        $this->display();
    }

    /**
     * 显示数据
     */
    public function index() {
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


}
