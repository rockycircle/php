<?php
namespace Rbac\Controller;
class IndexController extends ExtendController {
    
    public  function index(){
        $this->display("index");
    }
    
    /*
     * 主页
     */
    public function manager(){
        $this->display("manager");
    }
    
}