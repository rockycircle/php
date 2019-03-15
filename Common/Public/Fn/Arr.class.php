<?php
namespace Fn;
//Arr Array
class Arr extends Base
{
	
	/**
	 * Enter description here ...
	 * --------------------------
	 * @param array $data
	 * 				$data[pid][key] = array(...)
	 * 
	 * -------------------------
	 * @param array key $startKey
	 * @param string $childrenKey
	 */
	
	function tree(&$data, $startKey=0, $childrenKey='children')
	{
		if(! isset($data[$k])) return null;
		if(!is_array($data[$startKey]) || empty($data[$startKey])) return $data[$startKey];
		
		$r = array();
		foreach($data[$startKey] as $k=>$v){
			if(isset($data[$k])){
				$v['$childrenKey']=self::tree($data, $k, $childrenKey);
				$r[] = $v;
			}		
		}
		return $r;
	}


	
	/**
	 * 返回数组中指定的一列
	 * @param array
	 * @param 个数，默认一个
	 * @return array
	 */
	
	function rand($array,$num=1)
	{
		$len = count($array);
		$num = ($len<$num)? $num: $len;
		return array_rand($array,$num);
	}
		
	/**
	 *-----------------------------
	 * 重置键名
	 * @return array()
	 *-----------------------------
	 */
	static function resetKey(&$array,$mapping=array())
	{
		foreach ($array as $key=>$value) {
			if(isset($mapping[$key])) $r[$mapping[$key]] = $value;
			else $r[] = $value;
		}
		return $r;
	}

	/**
	 *-----------------------------
	 * 键名转换
	 * @param array &$array
	 * @param $param
	 * @param $cmd 
	 *    [+PRE]添加前缀， [-PRE]删除前缀 ,  [/PRE]前缀
	 *    [+SUF]添加后缀， [-SUF]删除后缀
	 *-----------------------------
	 */
		
	static function processKey(&$array,$param,$cmd='+PRE')
	{
		$cmd = strtoupper($cmd);
		$tmp = array();
		if($cmd==='+PRE'){
			foreach ($array as $key=>$v){
				$tmp[$param.$key] = $v;
			}
		}elseif ($cmd==='-PRE'){
			$preLen = strlen($param);
			foreach ($array as $key=>$v){
				$currentPre = substr($key,0,$preLen);
				if($currentPre===$param){
					$key = substr($key,$preLen);
					$tmp[$key] = $v;
				}
			}
		}elseif ($cmd==='/PRE'){
			$param = is_string($param)? explode('>', $param):$param;
			list($from,$to) = $param;
			$startSign = ':>>:';
			foreach ($array as $key=>$v){
				$key = str_replace(array($startSign.$from, $startSign), array($to,''), $startSign.$key);
				$tmp[$key] = $v;
			}
		}
		$array = $tmp;
		unset($tmp);
		return $array;
	}
		
	/**
	 *-----------------------------
	 * 数据简化
	 *-----------------------------
	 */
	
	static function neaten($data,$val='',$key='',$boundsymbol=false)
	{
		reset($data);
		
		$r = array();
		$keyBoundsymbol = $boundsymbol? $boundsymbol: '@';
		if(!empty($data)){
			$i = 0;
			//解析要返回那些值
			if($val==='') $val = array_keys(current($data));
			if(!is_array($val)){
				$val  = str_replace(',','->', preg_replace('/\s+/','',$val) );
				if(strstr($val,'->')){
					$val  = explode('->',$val);
				}
			}
			//解析要用什么作为键名
			if(!is_array($key)){
				$key = str_replace(',','->', preg_replace('/\s+/','',$key) );
				if(strstr($key,'->')){
					$key  = explode('->',$key);
				}
			}
			
			foreach ($data as $k=>&$v){
				//-----------------------------
				if(!is_array($val)){
					$valField = $val===''? $v : $v[$val];
				}else{
					$valField  = array();
					$n = 0;
					foreach ($val as $VText){
						list($VText,$fun) = explode('|',trim($VText).'|');
						list($original,$alias) = explode('>>',$VText.'>>'.$VText);
						//是否要自动编号'[field_1>>,field_2>> =field_2>>n] [field_1==field_1>>field_1]'
						$alias = $alias===''? $n : $alias;
						$valField[$alias]  = empty($fun)? $v[$original] : $fun($v[$original]);
						//$valField[$alias]  = $v[$original];
						$n++;
					}
				}
				//-------------------------------
				if(!is_array($key)){
					$keyField = $key===''?$i : $v[$key];
				}else{
					$keyField  = array();
					foreach($key as $vKey){
						$keyField[$vKey]  = $v[$vKey];
					}
					$keyField = implode($keyBoundsymbol,$keyField);
				}
				//-------------------------------
				$valField = (($boundsymbol && is_array($valField))?implode($boundsymbol,$valField) :$valField);
				$r[$keyField]=$valField;
				$i++;
			}unset($v);
		}
		return $r;
	}
	
	/*
	 * 递归
	 */
	
	static function recursive($array,$callBack)
	{
		if(is_array($array)){
			if(!empty($array)){
				foreach ($array as &$v){
					self::recursive($array,$callBack);
				}unset($v);
			}
		}else{
			if(is_array($callBack)){
				$fn = $callBack[1];
				$callBack[0]->$fn($array);
			}else{
				$callBack($array);
			}
		}
	}
	
	/*
	 * 判断指定的值是否为空
	 */
	
	static function notEmpty(&$array,$key='')
	{
		$orExp = explode('|',trim($key,'|'));
		$r = false;
		foreach($orExp as $item)
		{
			$tmpR = true;
			$andExp = explode( '&', trim($item,'&') );
			
			foreach($andExp as $v)
			{
				$tmpR = $tmpR && !empty($array[$v]);
			}
			$r = $r | $tmpR;
		}
		return $r;
		
	}
	
	/*
	 * 符合 表达式 $fn的个数
	 */
	
	static function countTenable(&$array,$key,$fn, $scope = false)
	{
		$r = 0;
		$keys = explode(',',$key);
		
		$reverse = false;
		if($fn[0]==='!'){
			$fn = substr($fn,1); 
			$reverse = true;
		}
		
		if($scope && is_string($scope)){
				$scope = new $scope();
		}
		
		foreach($keys as $v){
			$v = trim($v);
			$tmp = $scope? $scope->$fn($array[$v]): $fn($array[$v]);
			
			if($reverse) $r += $tmp? 0: 1;
			else         $r += $tmp? 1: 0;
		}
		return $r;
	}
	
	/*
	 * to string
	 */
	
	function toString($array,$tpl='$key:$value'){
		foreach($array as $k=>$v){
			$tpl[] = str_replace(array($k,$v),array('$key','$val'),$tpl);
		}
	}
	
	function nullFilter($arr,$to=null){
		if(is_array($arr) && !empty($arr)){
			foreach ($arr as $key=>$val){
				if(is_array($val)){
					$arr[$key] = self::nullFilter($val,$to);
				}elseif(is_null($val)){
					if(is_null($to)) unset($arr[$key]);
					else $arr[$key] = $to;
				}
			}
		}
		return $arr;
	}
	
	function filterByKey(&$arr,$keyword=null,$reg=false,$fn='delete'){
		$found = array();
		foreach ($arr as $key=>$val){
			if(is_array($val) && !empty($arr)){
				if($reg){
					if(preg_match($keyword, $key))$found[$key]=$val;
				}elseif(strstr($key,$keyword))
					$found[$key]=$val;
			}
		}
		if($fn==='return') return $found;
		else return array_diff_key($arr, $arr);
	}
	
	/**
	 * 传入一个boolean值数组 判断数组元素是否全部是true
	 * @param $booArray array
	 * @return bool
	 */
	
	public static function isAllTrue($booArray){
	    foreach ($booArray as $v){
	        if($v === false){
	            return false;
	        }
	    }
	    return true;
	}
    
	static function sort(&$array,$by,$type='ASC',$IsReturn=false)
	{
	    foreach ($array as $k=>$v){
	        $sort[$k]=is_null($v[$by])?0:$v[$by];
	    }
	    $type = (trim(strtoupper($type))=='ASC' || empty($type))?'asort':'arsort';
	    $type($sort);
	    
	    $r=array();
	    foreach ($sort as $k=>$v){
	        $r[]=$array[$k];
	    }
	    
	    if($IsReturn) return $r;
	    $array=$r;
	}
	
	static function arraySort(&$array,$by,$type='ASC',$IsReturn=false)
	{
		self::sort($array,$by,$type,$IsReturn);
	}
	
}
	