<?php
namespace Rbac\Controller;
/**
  +----------------------------------------------------------
 * 类目控制器
  +----------------------------------------------------------
 * Description of CategoryController
  +----------------------------------------------------------
 * @author Jonas_yang 2016-2-1
  +----------------------------------------------------------
 */
class CategoryController extends ExtendController{
    
    public function category(){
        $this->display();
	}
	
    //分页显示栏目列表的数据
    public function index(){
        $id = empty($_GET["id"])?0:$_GET["id"];  //获取类目ID
        $categoryModel = M("cms_category");
        $map["category_pid"] = $id;
        $categoryList = $categoryModel->where($map)->select();
        $this->ajaxReturn($categoryList);
    }
    
    //显示栏目列表的数据
    public function lists(){
        $id = empty($_POST["id"])?0:$_POST["id"];  //获取类目ID
        $categoryModel = M("cms_category");
        $map["category_pid"] = $id;
        $map["category_status"] = 1;
        $categoryList = $categoryModel->where($map)->select();
        $data = array();
        foreach($categoryList as $category){
            $row["id"] = $category["category_id"];
            $row["text"] = $category["category_title"];
            $row["state"] = $category["state"];
            $data[] = $row;
        }
        $this->ajaxReturn($data);
    }
    
    /**
     * 插入栏目数据
     */
    public function insert(){
        //获取post数据
        if(empty($_POST["category_pid"])){
            $_POST["state"] = "closed";
        }
        else{
            $_POST["state"] = "open";
        }
        //获取父亲节点
        M("cms_category")->where(array("category_id"=>$_POST["category_pid"]))
                         ->save(array("state"=>"closed"));
        parent::insert();
    }
    
    /**
     * 修改数据
     */
    public function update(){
        $id = $_GET["category_id"];
        $res = M("cms_category")->where(array("category_id"=>$id))
                         ->save(array("category_title"=>$_GET["category_title"]));
        if($res!==false){
            echo "修改成功";
        }else{
            echo "修改失败";
        }
    }
}

?>
