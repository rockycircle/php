<?php
namespace Fn;
/**
  +----------------------------------------------------------
 * App.php文件
  +----------------------------------------------------------
 * @name App 类
  +----------------------------------------------------------
 * @author Jonas <jonas.yang@cifang.hk>
  +----------------------------------------------------------
 */
class App {
   
   /**
     * -----------------------------
     * 日志记录
     * -----------------------------
     */
    static function log($fileName, $content = '', $title = '') {
        $fileName = rtrim(LOG_PATH, '/') . '/Public-' . date('Y-m-d') . '/' . trim($fileName, '/');
        $path = dirname($fileName);
        if (!file_exists($path)) {
            $pPath = dirname($path);
            if (!file_exists($pPath)) {
                mkdir($pPath, 0777);
                chmod($pPath, 0777);
            }
            mkdir($path, 0777);
            chmod($path, 0777);
        }
        $s = "\n--------------------------------------" .
                "\n\t时间：" . time() . ' 时期:' . date('Y-m-d H:i') . $title .
                "\n--------------------------------------\n";
        file_put_contents($fileName, $s . var_export($content, true), FILE_APPEND);
    }

    static function setTimeLimit($p = 30) {
        @set_time_limit($p);
    }

    static function setMemoryLimit($p = '130M') {
        @ini_set('memory_limit', $p);
    }

    static function errorReporting($p = 1) {
        @ini_set('display_errors', $p);
        @ini_set('log_errors', $p);
        error_reporting(E_ALL & ~E_NOTICE);
    }
    
    /**
     * 获取当前用户ID
     * @return int
     */
    static function getUserId(){
        return $_SESSION[C('USER_AUTH_KEY')];
    }
    
    /**
     * 获取当前用户信息
     */
    static function getAuthUserId(){
        return 1;
    }
}
