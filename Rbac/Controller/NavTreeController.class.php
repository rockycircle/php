<?php
namespace Rbac\Controller;
use Think\Controller;
use Org\Util\Rbac;
/**
  +----------------------------------------------------------
 * 树结点
  +----------------------------------------------------------
 * Description of NavTreeController
  +----------------------------------------------------------
 * @author Jonas_yang 2016-1-5
  +----------------------------------------------------------
 */
class NavTreeController extends Controller {

    /**
     * 获取目录树
     */
    public function test() {
        if (empty($_POST["id"])) {
            $map["nid"] = 0;
        } else {
            $map["nid"] = $_POST["id"];
        }
        $data = D("RbacNavTree")->where($map)->select();
        $this->ajaxReturn($data);
    }

    /**
     * 根据权限获取目录树
     */
    public function tree() {
        //读取数据库模块列表生成菜单项
        if (empty($_POST["id"])) {
            $where["pid"] = 0;
            $where['level'] = 1;
        } else {
            $where["pid"] = $_POST["id"];
            $where['level'] = 2;
        }
        $where['status'] = 1;
        $menu = array();
        $node = M("RbacNode");
        $list = $node->where($where)->field('id,name,level,title text,pid')->order('sort asc')->select();
        foreach ($list as $key => $node) {
            $node["state"] = "open";
            if($node["level"] == 1) {
                $node["state"] = "closed";
            }
            $list[$key] = $node;
        }

        if (isset($_SESSION['_ACCESS_LIST'])) {
            $accessList = $_SESSION['_ACCESS_LIST'];
        } else {
            $accessList = Rbac::getAccessList($_SESSION[C('USER_AUTH_KEY')]);
        }

        foreach ($list as $module) {
            if ($_SESSION['administrator']) {
                $module['access'] = 1;
                $menu[] = $module;
            }else {
                if ($module["level"] == "1") {
                    if (!empty($accessList[strtoupper($module['name'])])) {
                        $module['access'] = 1;
                        $menu[] = $module;
                    }
                } else {
                    foreach ($accessList as $access) {
                        if (isset($access[strtoupper($module['name'])])) {
                            //设置模块访问权限
                            $module['access'] = 1;
                            $menu[] = $module;
                        }
                    }
                }
            }
        }
        //缓存菜单访问
        $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]]	=	$menu;
        $this->ajaxReturn($menu);
    }

}

?>
