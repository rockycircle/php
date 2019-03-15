<?php
namespace Rbac\Controller;
/**
 * Description of UserController
 * 用户管理控制器
 * @author Jonas_yang
 */
class UserController extends ExtendController {

	/**
	 * 进入用户管理
	 */
	public function user() {
		$this->display();
	}

	/**
	 * 添加数据
	 */
	public function insert() {
		$userModel = D("RbacUser");
		if (false === $userModel->create()) {
			$data["success"] = 2;
			$data["msg"] = $userModel->getError();
		} else {
			$last_id = $userModel->add();
			//添加数据到user-role
			$roleUserModel = M("RbacRoleUser");
			$roleUser["role_id"] = $_POST["group"];
			$roleUser["user_id"] = $last_id;
			$res = $roleUserModel->add($roleUser);
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
	 * 删除数据
	 */
	public function delete() {
		$ids = $_POST["ids"];
		$ids = json_decode($ids);
		$map["user_id"] = array("in", $ids);
		$map1["id"] = array("in", $ids);
		$res1 = M("RbacUser")->where($map1)->delete();
		$res2 = M("RbacRoleUser")->where($map)->delete();
		if ($res1 !== false && $res2 !== false) {
			$data["success"] = 1;
			$data["msg"] = "删除数据成功";
		} else {
			$data["success"] = 2;
			$data["msg"] = "删除数据失败";
		}
		$this->ajaxReturn($data);
	}

	/**
	 * 修改密码页面
	 */
	public function passwd() {
		$this->display();
	}

	/**
	 * 修改用户信息
	 */
	public function edit() {
		$userModel = D("RbacUser");
		$roleUserModel = M("RbacRoleUser");
		$map["id"] = $_GET["id"];
		if (empty($_POST)) {
			$roleuser = $roleUserModel->field("role_id")
				->where(array("user_id" => $_GET["id"]))
				->find();
			$user = $userModel->where($map)->find();
			$user = array_merge($roleuser, $user);
			if (!empty($user)) {
				$data["success"] = true;
				$data["user"] = $user;
			}
			$this->ajaxReturn($data);
			exit;
		} else {
			$role["role_id"] = $_POST["group"];
			unset($_POST["group"]);
			$map1["role_id"] = $_GET["role"];
			$map1["user_id"] = $_GET["id"];
			$res = $userModel->where($map)->save($_POST);
			$roleUserModel->where($map1)
				->save($role);
			if ($res !== false) {
				$data["success"] = 1;
				$data["msg"] = "修改数据成功";
			} else {
				$data["success"] = 3;
				$data["msg"] = "修改数据失败";
			}
			$this->ajaxReturn($data);
		}
	}

	/**
	 * 管理员修改用户的密码
	 */
	public function updateuserpasswd() {
		$userId = $_GET["id"];
		$password = $_POST["password"];
		$newPassword = $_POST["newpassword"];
		if($password!==$newPassword){
			$response = array("success" =>1, "msg" => "两次输入的密码不一致");
			$this->ajaxReturn($response);
		}
		$map["id"] = $userId;
		$data["password"] = md5($password);
		$res = D("RbacUser")->where($map)->save($data);
		if ($res !== false) {
			$response = array("success" => 1, "msg" => "密码修改成功");
		} else {
			$response = array("success" => 2, "msg" => "密码修改失败");
		}
		$this->ajaxReturn($response);
	}

	/**
	 * 修改密码接口
	 */
	public function updatePassword() {
		$userId = $_SESSION[C('USER_AUTH_KEY')];
		$oldPassword = $_REQUEST["old_password"];
		$newPassword = $_REQUEST["new_password"];
		$map["id"] = $userId;
		$data["password"] = md5($newPassword);
		$user = D("RbacUser")->where($map)->find();
		if (empty($user)) {
			$response = array("success" => false, "message" => "账号不存在");
			$this->ajaxReturn($response);
			exit;
		} else {
			if ($user["password"] == md5($oldPassword)) {
				$res = D("RbacUser")->where($map)->save($data);
				if ($res !== false) {
					$response = array("success" => true, "message" => "密码修改成功");
				} else {
					$response = array("success" => false, "message" => "密码修改失败");
				}
				$this->ajaxReturn($response);
				exit;
			} else {
				$response = array("success" => false, "message" => "输入密码错误");
				$this->ajaxReturn($response);
				exit;
			}
		}
	}

}

?>
