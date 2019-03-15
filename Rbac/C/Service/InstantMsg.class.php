<?php

namespace Rbac\C\Service;

use Rbac\C\Api\Jpush\JPush;
use Fn;

/**
  +----------------------------------------------------------
 * InstantMsg.php文件
  +----------------------------------------------------------
 * @name InstantMsg 类
  +----------------------------------------------------------
 * @author Jonas <jonas.yang@cifang.hk>
  +----------------------------------------------------------
 */
class InstantMsg {

	private static $client = null;
	private static $app_key = "e9293e2c092da8cb057e0ae8";
	private static $master_secret = "a081439a601567da2ff6ce53";

	//获取到单例的对象
	public static function getObj() {
		if (self::$client != null) {
			return self::$client;
		} else {
			self::$client = new JPush(self::$app_key, self::$master_secret);
			return self::$client;
		}
	}

	/**
	 * 跟据用户ID推送消息
	 */
	public static function sendInstantMsgByUid($uid, $content) {
		self::getObj(); //调用
		$user = D("RbacUser")->field("device_tags,account")
			->where(array("id" => $uid))
			->find();
		Fn\App::log("instastant" . date("H") . "log", $user);
		//发送推送消息
		try {
			$result = self::$client->push()
				->setPlatform(array('android'))
				->addAlias($user["account"])
				//->setNotificationAlert('Hi, JPush')
				->addAndroidNotification($content, '消息题醒', 1, array("key1" => "value1", "key2" => "value2"))
				//->setMessage("msg content", 'msg title', 'type', array("key1"=>"value1", "key2"=>"value2"))
				//->setOptions(100000, 3600, null, false)
				->send();
		} catch (\Think\Exception $e) {
			Fn\App::log("instastant" . date("H") . "log", $e->getMessage());
		}
	}

	/**
	 * 跟据用户ID推送消息
	 */
	public static function sendInstantMsgByTags($tags, $content) {
		self::getObj(); //调用
		$user = D("RbacUser")->field("device_tags,account")
			->where(array("device_tags" => $tags))
			->select();
		Fn\App::log("instastant" . date("H") . "log", $user);
		$u = array();
		foreach ($user as $v) {
			$u[] = $v["account"];
		}
		Fn\App::log("instastant" . date("H") . "log", $u);
		//发送推送消息
		try {
		$result = self::$client->push()
			->setPlatform(array('android'))
			->addAlias($u)
			->addAndroidNotification($content, '消息题醒', 1, array("key1" => "value1", "key2" => "value2"))
			->send();
		} catch (\Think\Exception $e){
			Fn\App::log("instastant" . date("H") . "log", $e->getMessage());
		}
	}

}
