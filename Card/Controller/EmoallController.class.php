<?php
/**
 * Created by PhpStorm.
 * User: scotte
 * Date: 2017/7/20
 * Time: 15:22
 */

namespace Card\Controller;


use Rbac\Controller\ExtendController;

class EmoallController extends BaseController
{
    public function index(){
        $this->display('emoall');
    }

}