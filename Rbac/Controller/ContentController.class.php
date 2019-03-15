<?php
namespace Rbac\Controller;
/**
  +----------------------------------------------------------
 * 内容控制器
  +----------------------------------------------------------
 * Description of ContentController
  +----------------------------------------------------------
 * @author Jonas_yang 2016-1-20
  +----------------------------------------------------------
 */
class ContentController extends ExtendController{
    /**
     * 进入权限页面
     */
    public function content(){
        $this->display();
    }
 
    
    /**
     * 进入添加页面
     */
    public function add(){
        $this->display();
    }
    
    /**
     * 插入文章数据
     */
    public function  insert(){
        $model = D("CmsContent");
        if (false === $model->create()) {
            $data["success"] = 2;
            $data["msg"] = $model->getError();
        } else {
            //生成缓存文件
            //设置变量
            $this->assign("title",$_POST["content_title"]);
            $this->assign("content",$_POST["content_text"]);
            $tplcontent = $this->fetch("Content:cms_content");//根据模板生成内容
            $res = $model->add();
            $path = "cms_content/".date("Y-m-d")."/".$model->getLastInsID().".html";
            F($path,$tplcontent);//保存在指定的地方
            if ($res !== false) {
                $data["success"] =1;
                $data["msg"] = "添加数据成功";
            } else {
                $data["success"] = 3;
                $data["msg"] = "添加数据失败";
            }
        }
        $this->ajaxReturn($data);
    }
    
    /**
     * 预览文章
     */
    public function seeContent(){
       $id =  $_GET["id"];   //id
       $date = $_GET["date"];  //日期
       $path = "cms_content/".$date."/".$id.".html";
       if(!F($path)){
            $content = M("zx_word")->where(array("word_id"=>$id))->find();
            $this->assign("title",$content["word_title"]);
            $this->assign("content",htmlspecialchars_decode($content["word_content"]));
            $tplcontent = $this->fetch("Content:cms_content");//根据模板生成内容
            F($path,$tplcontent);//保存在指定的地方
       }
       echo F($path);
    }
    
    /***
     * 删除内容
     */
    public function delete(){
          $ids = $_POST["ids"];
          $ids = json_decode($ids);
          $map["content_id"] =array("in",$ids);
          $res = M("CmsContent")->where($map)->delete();
          if($res!==false){
              //删除缓存的文件
              $data["success"] =1;
              $data["msg"] = "删除数据成功";
          }else{
              $data["success"] = 2;
              $data["msg"] = "删除数据失败";
          }
          $this->ajaxReturn($data);
    }
    
    
}

?>
