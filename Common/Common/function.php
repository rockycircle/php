<?php

/**
  +----------------------------------------------------------
 * 公共函数
  +----------------------------------------------------------
 * Description of common
  +----------------------------------------------------------
 * @author Jonas_yang 2015-11-25
  +----------------------------------------------------------
 */
//公共函数
function todate($time, $format = 'Y-m-d H:i:s') {
    if (empty($time)) {
        return date("Y-m-d");
    }
    $format = str_replace('#', ':', $format);
    return date($format, $time);
}

function showStatus($status, $id) {
    switch ($status) {
        case 0 :
            $info = '<a href="javascript:resume(' . $id . ')">恢复</a>';
            break;
        case 2 :
            $info = '<a href="javascript:pass(' . $id . ')">批准</a>';
            break;
        case 1 :
            $info = '<a href="javascript:forbid(' . $id . ')">禁用</a>';
            break;
        case - 1 :
            $info = '<a href="javascript:recycle(' . $id . ')">还原</a>';
            break;
    }
    return $info;
}

function getStatus($status, $imageShow = true) {
    switch ($status) {
        case 0 :
            $showText = '禁用';
            $showImg = '<IMG SRC="__PUBLIC__/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
            break;
        case 2 :
            $showText = '待审';
            $showImg = '<IMG SRC="__PUBLIC__/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
            break;
        case - 1 :
            $showText = '删除';
            $showImg = '<IMG SRC="__PUBLIC__/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
            break;
        case 1 :
        default :
            $showText = '正常';
            $showImg = '<IMG SRC="__PUBLIC__/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';
    }
    return ($imageShow === true) ? $showImg : $showText;
}
?>
