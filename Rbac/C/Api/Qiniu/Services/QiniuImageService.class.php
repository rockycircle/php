<?php
namespace Rbac\C\Api\Qiniu\Services;
use Rbac\C\Api\Qiniu\Storage;
use Rbac\C\Api\Qiniu;

/**
  +----------------------------------------------------------
 * 七牛图片处理业务类
  +----------------------------------------------------------
 * Description of QiniuUpload
  +----------------------------------------------------------
 * @author Jonas_yang 2016-2-15
  +----------------------------------------------------------
 */
class QiniuImageService {

    private $auth;

    public function __construct() {
        $this->auth = new Qiniu\Auth(C("QINIU_AKEY"), C("QINIU_SKEY"));
    }

    /**
     * 上传方法
     * @param type $bucket  空间
     * @param type $filePath   文件地址
     * @param type $key  七牛的文件名
     */
    public function uploadImage($bucket, $filePath, $key) {
        // 生成上传 Token
        $token = $this->auth->uploadToken($bucket);
        $uploadMgr = new Storage\UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return null;  //上传错误
        } else {
            return $ret;  //返回成功的值
        }
    }

    /**
     * 图片列表
     * @param type $bucket  命名空间
     * @param type $marker 上次列举返回的位置标记，作为本次列举的起点信息。
     * @param type $prefix 要列取文件的公共前缀
     * @param type $limit 本次列举的条目数
     */
    public function imageList($bucket,$marker='',$prefix = '',$limit = 3) {
        $bucketMgr = new Storage\BucketManager($this->auth);
        // 列举文件
        list($iterms, $marker, $err) = $bucketMgr->listFiles($bucket, $prefix, $marker, $limit);
        if ($err !== null) {
            return null;
        } else {
            return $iterms;
        }
    }

}

?>
