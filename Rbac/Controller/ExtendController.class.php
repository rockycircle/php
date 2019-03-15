<?php

namespace Rbac\Controller;

use Think\Controller;
use Org\Util\Rbac;

/**
 * Description of ExtendCtroller
 * 公共的控制器
 * @author Jonas_yang
 */
class ExtendController extends Controller {

    public $tablePrefix = "Rbac";

    function _initialize() {
        // 用户权限检查
        if (!empty($_GET["prefix"])) {
            $prefix = $_GET["prefix"];
            $this->tablePrefix = ucfirst($prefix);  //首字母大写
        }
        if (empty($_SESSION[C('USER_AUTH_KEY')])) {
            //跳转到登录
            $this->redirect("Public/loginout");
        }
    }

    public function getLimitPage() {
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
        return array("pagesize"=>$pageSize,"pagenow"=>"pagenow","offset"=>$offset);
    }

    /**
     * 导出excel
     * @param $title 一维数组 array('A','B')
     * @param $data 二维数组 array(array('a','b'),array('a','b')
     * @param $filename string 文件名
     */
    public function exportexcel($data = array(), $title = array(), $filename = 'report') {
        /* header("Content-type:application/octet-stream");
          header("Accept-Ranges:bytes");
          header("Content-type:application/vnd.ms-excel");
          header("Content-Disposition:attachment;filename=" . $filename . ".xls");
          header("Pragma: no-cache");
          header("Expires: 0"); */

        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;Filename=' . $filename . '.xls');

        $excelString = '<html><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"><body><table border="1">';

        if (!empty($title)) {
            $excelString .= '<tr>';
            foreach ($title as $k => $v) {
                $excelString .= '<th>';
                $excelString .= $title[$k];
                $excelString .= '</th>';
            }
            $excelString .= '</tr>';
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $excelString .= '<tr>';
                foreach ($val as $ck => $cv) {
                    $excelString .= '<td>';
                    $excelString .= $cv;
                    $excelString .= '</td>';
                }
                $excelString .= '</tr>';
            }
        }
        $excelString .= '</table></body></html>';
        echo $excelString;
    }

    public function index() {

        $map = $this->_search();//
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        $name = CONTROLLER_NAME; //获取控制器名称
        $model = D($this->tablePrefix . $name);
        $data = null;
        if (!empty($model)) {
            $data = $this->_list($model, $map);
        }
        $this->ajaxReturn($data);
    }

    /**
      +----------------------------------------------------------
     * 简单插入处理，支持json返回和页面跳转返回
      +----------------------------------------------------------
     */
    public function insert() {

        if (empty($_GET["cmd"])) {
            //判断请求中是否含有json_callback字段，如果有表示，以json输出，木有表示默认跳转
            $name = CONTROLLER_NAME; //获取控制器名称
            $model = D($this->tablePrefix . $name);  //根据名称找到对应Model
        } else {
            $modelName = $_GET["cmd"]; //获取控制器名称
            $model = D($modelName);  //根据名称找到对应Model
        }
        if (false === $model->create()) {
            $data["success"] = 2;
            $data["msg"] = $model->getError();
        } else {
            $res = $model->add();
            if ($res !== false) {
                $data["success"] = 1;
                $data["msg"] = "添加数据成功";
            } else {
                $data["success"] = 3;
                $data["msg"] = "添加数据失败";
            }
        }
        $this->ajaxReturn($data);
    }

    /**
     * 取得操作成功后要返回的Url地址
     * 默认返回当前模块的默认操作
     * 可以在action控制器中重载
     * @return string
     */
    public function getReturnUrl() {
        return __URL__ . '?' . C('VAR_MODULE') . '=' . MODULE_NAME . '&' . C('VAR_ACTION') . '=' . C('DEFAULT_ACTION');
    }

    /**
      +----------------------------------------------------------
     * 列表条件过率
      +----------------------------------------------------------
     * @param string $name 数据对象名称
      +----------------------------------------------------------
     * @return type
      +----------------------------------------------------------
     */
    public function _search($name = "") {
        if (empty($name)) {
            $name = CONTROLLER_NAME; //获取控制器名称
        }
        $model = D($this->tablePrefix . $name);

        $map = array();
        foreach ($model->getDbFields() as $key => $v) {
            if (isset($_REQUEST[$v]) && $_REQUEST[$v] != '') {
                $map[$v] = $_REQUEST[$v];
            }
        }
        return $map;
    }

    /**
      +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
      +----------------------------------------------------------
     * @param type $model
     * @param type $map
     * @param type $sortBy
     * @param type $asc
      +----------------------------------------------------------
     */
    public function _list($model, $map, $sortBy = '', $asc = false) {
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
            $voList = $model->where($map)->order($order . " " . $sort)->limit($offset . " , " . $pageSize)->select();
        }
        return array("total" => $count, "rows" => $voList);
    }

    /**
     * 编辑
     */
    public function edit() {
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
                $data["msg"] = "修改数据成功";
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

    /**
     * 删除
     */
    public function del() {
        $modelName = $_GET["cmd"];
        $map = array();
        $model = D($modelName);
        $id = $model->getPk(); //获取主键
        $ids = json_decode($_REQUEST[$id]);
        $map[$id] = array("in", $ids);
        if (!empty($model)) {
            $res = $model->where($map)->delete();
            if ($res !== false) {
                $data["success"] = true;
                $data["msg"] = "删除数据成功";
            } else {
                $data["success"] = false;
                $data["msg"] = "删除数据失败";
            }
        } else {
            $data["success"] = false;
            $data["msg"] = "删除数据失败,参数错误";
        }
        $this->ajaxReturn($data);
    }

}

?>
