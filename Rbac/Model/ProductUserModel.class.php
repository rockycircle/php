<?php
/**
 * Product_user表的验证
 */

namespace Rbac\Model;


class ProductUserModel extends ExtendModel
{
    public $_validate=array(
        array("product_title","require","产品名称必须"),
    );
}