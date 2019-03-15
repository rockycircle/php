<?php

namespace Rbac\Controller;
use Think\Controller;
use Org\Util\Rbac;
/**
 * Description of PublicController
 * 公共的控制器
 * @author Jonas_yang
 */
class PublicController extends Controller {

    /**
     * 登录页面
     */
    public function login() {
        $username = I("post.username");
        $password = I("post.password");
        $userModel = M("RbacUser");
        $map["account"] = $username;
        $map["status"] = array('gt', 0);
        //$user = $userModel->where($map)->find();
        $authInfo = Rbac::authenticate($map);

        if (empty($authInfo)) {
            $resultData["success"] = false;
            $resultData["msg"] = "帐号不存在或已禁用！";
            $this->ajaxReturn($resultData);
            exit;
        } else {
            if ($authInfo['password'] != md5($password)) {
                $resultData["success"] = false;
                $resultData["msg"] = "密码错误！";
                $this->ajaxReturn($resultData);
                exit;
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
            $_SESSION['email'] = $authInfo['email'];
            $_SESSION['loginUserName'] = $authInfo['nickname'];
            $_SESSION['lastLoginTime'] = $authInfo['last_login_time'];
            $_SESSION['login_count'] = $authInfo['login_count'];
            if ($authInfo['account'] == 'admin') {
                $_SESSION['administrator'] = true;
            }
            //保存登录信息
            $ip = get_client_ip();
            $time = time();
            $data = array();
            $data['id'] = $authInfo['id'];
            $data['last_login_time'] = $time;
            $data['login_count'] = array('exp', 'login_count+1');
            $data['last_login_ip'] = $ip;
            $userModel->save($data);
            // 缓存访问权限
            Rbac::saveAccessList();
            $resultData["success"] = true;
            $resultData["msg"] = "登录成功";
            $this->ajaxReturn($resultData);
        }
    }

    /**
     * 退出登录
     */
    public function loginout() {
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
        }
        $this->display("Index/index");
    }

    /**
     * 获取用户组信息
     */
    public function groupList() {
        $groupList = M("RbacRole")->field("id,name")->select();
        $this->ajaxReturn($groupList);
    }
    
   
    /**
     * 输出运行数据
     */
    public function Runtime() {
        $productCategoryList = D("ProductCategory")->getProductCategoryList();
        $customerList = D("customer")->getCustomerList();
        $productList = D("Product")->getProductList();
        //产品列表
        $this->assign('products',  json_encode($productList));
        $this->assign("productcategory",json_encode($productCategoryList));
        $this->assign("customer",json_encode($customerList));
        $this->display();
    }

}

?>
