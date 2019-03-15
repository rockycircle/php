<?php
/*
 * 测试代码
 */
namespace Rbac\Controller;
use Think\Controller;
class TestController extends Controller
{

    public function index()
    {
        $this->display();
    }

    public function select()
    {
        $product = M('product');
        /*$Total = $Test->count();
        $Json = '{"total":'.$Total.',"rows":'.json_encode($Test->select()).'}';*/
        $Json = json_encode($product->select());
        echo $Json;


    }


    public function save()
    {
        $data=$_POST;
        $res=M('product')->add($data);
        if($res){
            $resData['success']=true;
            $resData['msg']="添加成功";
        }else{
            $resData['success']=false;
            $resData['msg']="添加失败";
        }
        $this->ajaxReturn($resData);


    }

    public function update()
    {

        $res=$_POST;
        $id=I('get.id');
        $res['product_id']=$id;
        $res=M('product')->save($res);

        if($res!==false){
            $resData['success']=true;
            $resData['msg']="修改成功";
        }else{
            $resData['success']=false;
            $resData['msg']="修改失败";
        }
        $this->ajaxReturn($resData);
//        echo json_encode($resData);

    }

    public function delete()
    {
        $id=I('post.id');
        $res=M('product')->delete($id);
        if ($res){
            $data['success']=true;
        }else{
            $data['success']=false;

        }
        $this->ajaxReturn($data);
    }

    public function test()
    {
        $data="成功";
        $this->ajaxReturn($data);
    }
}
