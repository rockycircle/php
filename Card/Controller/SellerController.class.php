<?php
namespace Card\Controller;

class SellerController extends BaseController
{
    public function index()
    {
        $products=D('product')->field('product_title,product_price,product_from,product_id,category_id')->select();
        $categories=D('product_category')->field('category_id,category_title')->select();

        $this->assign('categories',$categories);
        $this->assign('products',$products);
        $this->display('sell');
    }

    public function release()
    {
        $customerId = \Fn\App::getAuthUserId();
        $id=I('get.id');
        $product=D('product')->where(array('product_id'=>$id))->find();
        $category = D('product_category')->where(array('category_id'=>$product['category_id']))->find();
        $customer=M('customer')->where(array('customer_id'=>$customerId))->find();
        $this->assign('customer_id',$customer['customer_id']);
        $this->assign('category',$category);
        $this->assign('product',$product);
        $this->display();
    }

    public function save()
    {
        $proUser=D('product_user');
        if (IS_POST){
            if($proUser->create()){//create和$_POST有点相似 接受全部post上来的数据.
                if ($proUser->add()){
                    $this->success("添加成功");
                    $this->redirect('Seller/index');
                }else{
                    $this->error("添加失败",U('sell'));
                    $this->redirect('Seller/index');
                }

            }else{
                $this->error($proUser->getError());
            }
        }
    }
}