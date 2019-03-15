<?php

namespace Fn;

class Http {

    static $____selfObj;

    static function M() {
        if (!self::$____selfObj || empty(self::$____selfObj)) {
            self::$____selfObj = new self;
        }
        return self::$____selfObj;
    }

    public function multiCurl($request, $callback = false) {
        $mh = curl_multi_init();
        foreach ($request as $i => $v) {
            $timeOut = (isset($v['timeout']) && $v['timeout'] > 0 ) ? $v['timeout'] : 60;
            $conn[$i] = curl_init();
            curl_setopt($conn[$i], CURLOPT_URL, $v['url']);
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYHOST, 0);
            if (isset($v['header']) && !empty($v['header'])) {
                curl_setopt($conn[$i], CURLOPT_HTTPHEADER, $v['header']);
            }if (isset($v['post']) && !empty($v['post'])) {
                $post = is_array($v['post']) ? http_build_query($v['post']) : $v['post'];
                curl_setopt($conn[$i], CURLOPT_POST, 1);
                curl_setopt($conn[$i], CURLOPT_POSTFIELDS, $post);
            } else {
                curl_setopt($conn[$i], CURLOPT_POST, 0);
            }
            curl_setopt($conn[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");
            curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT, $timeOut);
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($mh, $conn[$i]);
        }

        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active and $mrc == CURLM_OK) {// wait for network
            if (curl_multi_select($mh) != -1) {// pull in any new data, or at least handle timeouts
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        $r = array();
        foreach ($request as $i => $v) {
            $data = curl_multi_getcontent($conn[$i]);
            //if(empty())
            curl_multi_remove_handle($mh, $conn[$i]);
            curl_close($conn[$i]);
            if ($callback) {
                if (is_array($callback)) {//回調
                    $fun = $callback[1];
                    $r[$i] = $callback[0]->$fun($data, $request[$i]);
                } else {
                    $r[$i] = $callback($data, $request[$i]);
                }
            } else {
                $r[$i] = $data;
            }
        }

        curl_multi_close($mh);
        return $r;
    }

    public function curl($url, $post = false, $header = false) {
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, $url);
        // Cookie相关设置，这部分设置需要在所有会话开始之前设置
        date_default_timezone_set('PRC'); // 使用Cookie时，必须先设置时区
        curl_setopt($connection, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($connection, CURLOPT_HEADER, 0);
        curl_setopt($conn[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");
        //curl_setopt($connection, CURLOPT_COOKIE, session_name() . '=' . session_id());
        curl_setopt($connection, CURLOPT_COOKIEFILE, 'cookie.txt'); 
        curl_setopt($connection, CURLOPT_COOKIEJAR, 'cookie.txt'); 
        //stop CURL from verifying the peer's certificate
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
        if ($header && !empty($header)) {
            curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
        }if ($post && !empty($post)) {
            $post = is_array($post) ? http_build_query($post) : $post;
            curl_setopt($connection, CURLOPT_POST, 1);
            curl_setopt($connection, CURLOPT_POSTFIELDS, $post);
        }
        //curl_setopt($connection,CURLOPT_REFERER,$url);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        //Send the Request
        return curl_exec($connection);
    }

    public function httpRequest($request, $callback = false) {
        if (count($request) > 1) {
            return $this->multiCurl($request, $callback);
        } else {
            reset($request);
            $key = key($request);
            $request = $request[$key];
            $response = $this->curl(
                    $request['url'], (empty($request['post']) ? false : $request['post']), (empty($request['header']) ? false : $request['header'])
            );
            if ($callback) {
                if (is_array($callback)) {//回調
                    $fun = $callback[1];
                    $response = $callback[0]->$fun($response, $request);
                } else {
                    $response = $callback($response, $request);
                }
            }
            return array($key => $response);
        }
    }

    public function httpGet($request, $callback = false) {
        if (is_string($request)) {
            $request = array($request);
            $onlyOne = true;
        }
        foreach ($request as $k => $v) {
            $request[$k] = array('url' => $v);
        }
        $r = $this->httpRequest($request, $callback);
        reset($r);
        return $onlyOne ? $r[0] : $r;
    }

    public function httpPost($request, $callback = false) {
        if (isset($request['url'])) {
            $request = array($request);
            $r = $this->httpRequest($request, $callback);
            return $r[0];
        }
        return $this->httpRequest($request, $callback);
    }

}

//$r = new HttpComm;
//var_dump( $r->httpPost(array(array('url'=>'http://gw.api.taobao.com/router/rest?sign=551731615760BDAEC5CB27D0C227FC2A'),array('url'=>'hhttp://gw.api.taobao.com/router/rest?sign=551731615760BDAEC5CB27D0C227FC2A'))));