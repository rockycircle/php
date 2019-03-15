<?php
namespace Card\Controller;

class UserController extends BaseController {

    public function index()
    {
        $customerId = \Fn\App::getAuthUserId();
        $customerInfo=M('customer')->where(array('customer_id'=>$customerId))->find();
        //以下是买家模块的省钱和花费计算
        $costInfo=M('orders')
            ->where(array('customer_id'=>$customerId,'orders_status'=>'9'))
            ->field('orders_price')->select();
        $originPrice=0;
        $totalCost=0;
        foreach($costInfo as $info){
            $originInfo=M('orders_product')
                ->where(array('orders_id'=>$info['orders_id']))->field('product_origin_price')->select();
            foreach ($originInfo as $vo){
                $originPrice+=$vo['product_origin_price'];
            }
            $totalCost+=$info['orders_price'];
        }
        $saveMoney=$originPrice-$totalCost;
        //以下是卖家的出售盈利计算
        $saleMoney=0;
        $saleInfo=M('product_user')->where(array('customer_id'=>$customerId))->select();
        foreach ($saleInfo as $info){
            $saleMoney+=$info['product'];
        }
        //我的买单
        $buy=M('orders')->where(array('customer_id'=>$customerId))->select();
        $buyNum[]=0;
        foreach ($buy as $vo){
            $buyNum[$vo[orders_status]]+=1;
        }
        $this->assign('buyNum',$buyNum);
        //我的卖单
        $sale=M('product_user')->where(array('customer_id'=>$customerId))->select();
        $saleNum[]=0;
        foreach ($sale as $vo){
            $saleNum[$vo[product_status]]+=1;
        }

        $this->assign('saleNum',$saleNum);

        $this->assign('totalCost',$totalCost);//总共花费
        $this->assign('saveMoney',$saveMoney);//节省的钱
        $this->assign('saleMoney',$saleMoney);//出售盈利
        $this->assign('customerInfo',$customerInfo);//当前用户信息
        $this->display('center');
    }
}