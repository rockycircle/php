<?php
namespace Rbac\Model;
/**
  +----------------------------------------------------------
 * SystemConfigModel.php文件
  +----------------------------------------------------------
 * @name SystemConfigModel 类
  +----------------------------------------------------------
 * @author Jonas <jonas.yang@cifang.hk>
  +----------------------------------------------------------
 */
class SystemConfigModel  extends ExtendModel{
	//获取配置文件
	public function  getConfig($key){
		$map["config_key"] = $key;
        $config = $this->where($map)->find();
		if(empty($config)){
			return "";
		}else{
			return $config["config_key"];
		}
	}
	
	/**
	 * 修改配置文件
	 * @param type $key
	 * @param type $value
	 * @return boolean
	 */
	public function  setConfig($key,$value){
		$map["config_key"] = $key;
		$data["config_value"] = $value;
        $res = $this->where($map)->save($data);
		if($res!==false){
			return true;
		}else{
			return false;
		}
		
	}
}
