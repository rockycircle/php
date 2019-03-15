<?php
namespace Rbac\Controller;
use Think\Controller;
use Fn;
/**
  +----------------------------------------------------------
 * 文件管理控制器
  +----------------------------------------------------------
 * Description of FileController
  +----------------------------------------------------------
 * @author Jonas_yang 2016-2-14
  +----------------------------------------------------------
 */
class FileController extends Controller {
    
    /**
     * 上传文件方法
     */
    public function upload(){
        ///Fn\FileUpload::openQiniu();  //开启七牛
        Fn\FileUpload::uploadFile(true);  //上传文件
    }
    
    /**
     * kind图片插件管理图片方法
     */
    public function fileManager(){
        //Fn\FileUpload::openQiniu();   //开启七牛
        Fn\FileUpload::fileManager();  //文件管理
    }
    
}

?>
