<?php

namespace Rbac\Model;

class CustomerModel extends ExtendModel {

    public function getCustomerList() {
        $list = $this->field("customer_id,customer_title")
                ->order("customer_id")
                ->select();
        if (!empty($list)) {
            return $list;
        } else {
            return null;
        }
    }

}
