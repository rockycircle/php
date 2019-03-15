<?php
/**
 * Created by PhpStorm.
 * User: tt
 * Date: 2017/7/5
 * Time: 19:56
 */

namespace Card\Controller;

class PayController extends BaseController
{
    public function index()
    {

        $this->display('binding');
    }

    public function getMoney()
    {
        $myInfo=M('customer')->where(array('customer_id'=>I('get.id')))->find();
        $this->assign('myInfo',$myInfo);
        $this->display();
    }

    public function viewMoney()
    {
        $myId=I('get.id');

        $myInfo=M('customer')->where(array('customer_id'=>$myId))->find();

        $this->assign('myInfo',$myInfo);
        $this->display();
    }
    
    
    /**
     * 支付完成的回调
     */
    public function payCallback(){
         Vendor('Wxpay.WxPay#Api');
        $raw_xml = file_get_contents("php://input");
        $notify = new \WxPayNotifyCallBack();
        $notify->Handle(false);
        $res = $notify->GetValues();
        if($res['return_code'] ==="SUCCESS" && $res['return_msg'] ==="OK"){
            libxml_disable_entity_loader(true);
            $ret = json_decode(json_encode(simplexml_load_string($raw_xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            \Think\Log::write('微信APP支付成功订单号'.$ret['out_trade_no'], \Think\Log::DEBUG);
            //在此处处理业务逻辑部分
        }
    }
    
    
}