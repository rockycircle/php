<?php

define("TOKEN", "");
define("APPID", "");
define("APPSECRET", "");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {    //第一次微信号绑定 我们的url  
    $wechatObj->valid();
} else if (isset($_GET['code'])) {  //授权后 我们获取到的code
    // 授权后 根据code得到openid
    $userOpendId = $wechatObj->getUserOpendIdByCode($_GET['code']);
    $userArray = $wechatObj->getUserInfoByOpenID($userOpendId);
    foreach ($userArray as $key => $value) {
        echo $key . ' = ' . $value;
        echo '<br/>';
    }
    exit;
} else {
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest {
    public function valid() {
        $echoStr = $_GET["echostr"];

        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature() {
        $signature = '';
        $timestamp = '';
        $nonce = '';
        if (!empty($_GET["signature"])) {
            $signature = $_GET["signature"];
        }
        if (!empty($_GET["timestamp"])) {
            $timestamp = $_GET["timestamp"];
        }
        if (!empty($_GET["nonce"])) {
            $nonce = $_GET["nonce"];
        }

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    //授权请求
    public function OAuth($redirect) {
        //$redirect = 'http://demo.zxtms.net/car/Weixin/index.php';   //回调地址 这个地址就可以得到我们的
        $appid = "wxcfc7058fdad95584";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        header('Location:' . $url);
    }

    /*
     * 根据请求 发送消息
     * */

    public function responseMsg() {
        $postStr = '';
        if (!empty($GLOBALS["HTTP_RAW_POST_DATA"])) {
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        }
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            $result = "";
            switch ($RX_TYPE) {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->sendMessage($postObj);
                    break;
                case "image":
                    $result = $this->sendMessage($postObj);

                    $data["weixin_openid"] = $postObj->ToUserName;
                    $data["weixin_from_user"] = $postObj->FromUserName;
                    $data["weixin_img_createtime"] = $postObj->CreateTime;
                    $data["weixin_msgtype"] = $postObj->MsgType;
                    $data["weixin_picurl"] = $postObj->PicUrl;
                    $data["weixin_media_id"] = $postObj->MediaId;
                    $data["weixin_msg_id"] = $postObj->MsgId;
                    $this->saveDataToImage($data);
            }
            echo $result;
            exit();
        } else {
            echo "";
            exit;
        }
    }

    public function getUserInfoByOpenID($openID) {
        $access_token = $this->getAccessToken();
        $openid = $openID;
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $output = $this->https_request($url);
        return $output;
    }

    /*
     * 根据授权code来获取用户的openid
     * */

    public function getUserOpendIdByCode($code) {
        $appid = APPID;
        $appSecret = APPSECRET;

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appSecret&code=$code&grant_type=authorization_code";
        $reutrnArray = $this->https_request($url);
        if (is_array($reutrnArray)) {
            return $reutrnArray['openid'];
        }
        return '';
    }

    /*
     * 请求url 返回rul请求的数据
     * $url 一个完整的地址
     * reutrn: array
     * */

    public function https_request($url) {
        //$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        return $jsoninfo;
    }

    /*
     * 获取AccessToken
     * */

    public function getAccessToken() {
        $appid = APPID;
        $appsecret = APPSECRET;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        $access_token = $jsoninfo["access_token"];
        return $access_token;
    }

    /*
     * 监听事件
     *
     * */

    public function receiveEvent($object) {
        $result = '';
        switch ($object->Event) {
            case "subscribe":     //关注监听
                $content = "";

                $result = $this->transmitText($object, $content);
                break;
            case "unsubscribe":  //取消关注监听
                break;
            case "CLICK":        //点击监听
                switch ($object->EventKey) {
                    case "review":
                        $result = $this->transmitText($object, "尊敬的客户您好，请您文字或语音告知您的客服编号是哪位，以及简单描述您的投诉原因，售后经理会马上跟您取得联系，帮您妥善处理问题。若未能立即与您联系，可直接拨打售后吴经理电话：<a href='tel:13325812932'>13325812932</a>");
                        break;
                    case "tel":
                        $result = $this->transmitText($object, "有意向合作请联系客服经理,电话：<a href='tel:18968176579'>18968176579</a>　　　　　　　<a href='tel:021-31200664'>021-31200664</a>　　　微信：18968176579");
                        break;
                    default:
                        break;
                }
                break;
        }
        return $result;
    }

    /* 回复文本消息 */

    public function transmitText($object, $content) {
        if (!isset($content) || empty($content)) {
            return "";
        }
        $textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    /*
     * sendMessage
     *
     * */

    public function sendMessage($postObj, $contentStr = '') {
        $keyword = trim($postObj->Content);
        //简介页面测试
        switch ($keyword) {
            case '1':
                $resultStr = $this->transmitText($postObj, 'http://www.baidu.com');
                return $resultStr;
                break;
            default:
                # code...
                break;
        }


        if ($keyword == "下载") {
            $content = "下载地址：<a href='https://itunes.apple.com/cn/app/qi-pei-wu-you/id1025006945?mt=8'>请点击下载，汽配无忧app</a>";
            $resultStr = $this->transmitText($postObj, $content);
            return $resultStr;
        }
        if ($keyword == "?" || $keyword == "？") {
            $resultStr = $this->transmitText($postObj, '您好！谢谢关注汽配无忧!!' . $postObj->FromUserName);
            return $resultStr;
        }
    }

    //回复多客服消息
    private function transmitService($object) {
        $xmlTpl = "<xml>".
                "<ToUserName><![CDATA[%s]]></ToUserName>".
                "<FromUserName><![CDATA[%s]]></FromUserName>".
                "<CreateTime>%s</CreateTime>".
                "<MsgType><![CDATA[transfer_customer_service]]></MsgType>".
                "</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    // 获取客服聊天记录
    public function getCustomerServiceLog() {
        $appid = APPID;
        $appsecret = APPSECRET;

        $post_data = array(
            "endtime" => 987654321,
            "pageindex" => 1,
            "pagesize" => 10,
            "starttime" => 123456789
        );

        $access_token = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/customservice/msgrecord/getrecord?access_token=$access_token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        $jsoninfo = json_decode($output, true);
        print_r(access_token);
    }

    /**
     * 添加数据到到图片数据表
     */
    private function saveDataToImage($data) {
        $conn = @mysql_connect("localhost", "root", "13339874981");
        if (!$conn) {
            die(" ：" . mysql_error());
        }
        mysql_select_db("hrain_car", $conn);
        mysql_query("set names 'utf8'");  //为避免中文乱码做入库编码转换
        $sql = "INSERT INTO car_weixin_img(weixin_openid,weixin_from_user,weixin_img_createtime,weixin_msgtype,weixin_picurl,weixin_media_id,weixin_msg_id)VALUES(" .
                "'" . $data["weixin_openid"] . "' , '" . $data["weixin_from_user"] . "' , " .
                $data["weixin_img_createtime"] . " , '" . $data["weixin_msgtype"] . "' , '" .
                $data["weixin_picurl"] . "' , '" . $data["weixin_media_id"] . "' , '" . $data["weixin_msg_id"] . "')";
        //退出程序并打印 SQL 语句，用于调试
        if (!mysql_query($sql, $conn)) {
            return false;
        } else {
            return true;
        }
    }

}
?>

