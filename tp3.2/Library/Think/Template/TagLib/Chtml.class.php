<?php

namespace Think\Template\TagLib;

use Think\Template\TagLib;

/**
  +----------------------------------------------------------
 * 自定义标签类
  +----------------------------------------------------------
 * Description of TagLibHtml
  +----------------------------------------------------------
 * @author Jonas_yang 2015-11-24
  +----------------------------------------------------------
 */
class Chtml extends TagLib {

    //定义自定义标签
    // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
    protected $tags = array(
        'list' => array('attr' => 'id,pk,style,action,actionlist,show,datasource,checkbox', 'close' => 0),
        'select' => array('attr' => 'name,options,values,output,multiple,id,size,first,change,selected,dblclick', 'close' => 0),
        'imagebtn' => array('attr' => 'id,name,value,type,style,click', 'close' => 0),
        'checkbox' => array('attr' => 'name,checkboxes,checked,separator', 'close' => 0),
        'radio' => array('attr' => 'name,radios,checked,separator', 'close' => 0)
    );

      /**
     +----------------------------------------------------------
     * checkbox标签解析
     * 格式： <html:checkbox checkboxes="" checked="" />
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string|void
     +----------------------------------------------------------
     */
    public function _checkbox($tag) {
        $name       = $tag['name'];
        $checkboxes = $tag['checkboxes'];
        $checked    = $tag['checked'];
        $separator  = $tag['separator'];
        $checkboxes = $this->tpl->get($checkboxes);
        $checked    = $this->tpl->get($checked)?$this->tpl->get($checked):$checked;
        $parseStr   = '<table class="table table-bordered table-striped table-condensed" style="margin: 0 auto;">';
        foreach($checkboxes as $key=>$val) {
            $parseStr .='<tr><td>';
            if($checked == $key  || in_array($key,$checked) ) {
                $parseStr .= '<input type="checkbox" checked="checked" name="'.$name.'[]" value="'.$key.'">'.$val.$separator;
            }else {
                $parseStr .= '<input type="checkbox" name="'.$name.'[]" value="'.$key.'">'.$val.$separator;
            }
            $parseStr .='</tr></td>';
        }
        $parseStr .='</table>';
        return $parseStr;
    }

    
    
    public function _select($tag) {
        $name = $tag['name'];
        $options = $tag['options'];
        $values = $tag['values'];
        $output = $tag['output'];
        $multiple = $tag['multiple'];
        $id = $tag['id'];
        $size = $tag['size'];
        $first = $tag['first'];
        $selected = $tag['selected'];
        $style = $tag['style'];
        $ondblclick = $tag['dblclick'];
        $onchange = $tag['change'];
        if (!empty($multiple)) {
            $parseStr = '<select id="' . $id . '" name="' . $name . '" ondblclick="' . $ondblclick . '" onchange="' . $onchange . '" multiple="multiple" class="' . $style . '" size="' . $size . '" >';
        } else {
            $parseStr = '<select id="' . $id . '" name="' . $name . '" onchange="' . $onchange . '" ondblclick="' . $ondblclick . '" class="' . $style . '" >';
        }
        if (!empty($first)) {
            $parseStr .= '<option value="" >' . $first . '</option>';
        }
        if (!empty($options)) {
         
            $parseStr .= '<?php  foreach($' . $options . ' as $key=>$val) { ?>';
            if (!empty($selected)) {
                $parseStr .= '<?php if(!empty($' . $selected . ') && ($' . $selected . ' == $key || in_array($key,$' . $selected . '))) { ?>';
                $parseStr .= '<option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option>';
                $parseStr .= '<?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option>';
                $parseStr .= '<?php } ?>';
            } else {
                $parseStr .= '<option value="<?php echo $key ?>"><?php echo $val ?></option>';
            }
            $parseStr .= '<?php } ?>';
        } else if (!empty($values)) {
            $parseStr .= '<?php  for($i=0;$i<count($' . $values . ');$i++) { ?>';
            if (!empty($selected)) {
                $parseStr .= '<?php if(isset($' . $selected . ') && ((is_string($' . $selected . ') && $' . $selected . ' == $' . $values . '[$i]) || (is_array($' . $selected . ') && in_array($' . $values . '[$i],$' . $selected . ')))) { ?>';
                $parseStr .= '<option selected="selected" value="<?php echo $' . $values . '[$i] ?>"><?php echo $' . $output . '[$i] ?></option>';
                $parseStr .= '<?php }else { ?><option value="<?php echo $' . $values . '[$i] ?>"><?php echo $' . $output . '[$i] ?></option>';
                $parseStr .= '<?php } ?>';
            } else {
                $parseStr .= '<option value="<?php echo $' . $values . '[$i] ?>"><?php echo $' . $output . '[$i] ?></option>';
            }
            $parseStr .= '<?php } ?>';
        }
        $parseStr .= '</select>';
        return $parseStr;
    }

    
    /**
     * list id="checkList" name="user" style="list" checkbox="true" action="true" datasource="list" 
     * show="id:编号|8%,account:用户名:edit,nickname:昵称,create_time|toDate='Y-m-d H#i#s':添加时间,last_login_time|toDate='Y-m-d H#i#s':上次登录,login_count:登录次数,status|getStatus:状态" 
     * actionlist="status|showStatus=$user['id']
      +----------------------------------------------------------
     * list标签解析
     * 格式： <html:list datasource = "" show = "" />
      +----------------------------------------------------------
     * @param type $attr 标签属性
      +----------------------------------------------------------
     * @return string 标签属性
      +----------------------------------------------------------
     */
    public function _list($tag) {
        // $tag = $this->parseXmlAttr($attr, 'list');
        //print_r($tag);

        $id = $tag["id"]; //表格ID
        $datasource = $tag["datasource"]; //列表显示的数据源Volist的名称
        $pk = empty($tag['pk']) ? 'id' : $tag["pk"];  //主键名，默认为ID
        $style = $tag["style"];  //样式
        $name = !empty($tag["name"]) ? $tag["name"] : 'vo';   //vo 对象名
        $action = $tag["action"] === 'true' ? true : false;   //是否显示功能操作
        $key = !empty($tag['key']) ? true : false;
        $sort = $tag['sort'] === 'false' ? false : true;
        $checkbox = $tag['checkbox'];
        //功能列表
        if (isset($tag['actionlist'])) {
            $actionlist = explode(',', trim($tag['actionlist']));
        }
        //获取字段列表
        if (substr($tag['show'], 0, 1) == '$') {
            $show = $this->tpl->get(substr($tag['show'], 1));
        } else {
            $show = $tag['show'];
        }

        $show = explode(',', $show);                //列表显示字段列表
        //计算表格的列数
        $colNum = count($show);
        if (!empty($checkbox))
            $colNum++;
        if (!empty($action))
            $colNum++;
        if (!empty($key))
            $colNum++;
        //print_r($show);
        //显示开始
        $parseStr = "<!--Think 系统列表组件开始-->\n";
        $parseStr .= '<table id="' . $id . '" class="' . $style . '" >';
        $parseStr .= '<thead><tr>';
        //列表要显示的字段
        $fields = array();
        foreach ($show as $val) {
            $fields[] = explode(":", $val);
        }
        //print_r($fields);
        if (!empty($checkbox)) {
            $parseStr .= '<th><input type="checkbox" name="key"	value="">选择</th>';
        }
        if (!empty($key)) {
            $parseStr .= '<th>序号</th>';
        }

        //显示指定的标题头字段
        foreach ($fields as $field) {
            $property = explode("|", $field[0]);
            $showname = explode('|', $field[1]);
            if (isset($showname[1])) {
                $parseStr .='<th width="' . $showname[1] . '">';
            } else {
                $parseStr .='<th>';
            }
            $showname[2] = isset($showname[2]) ? $showname[2] : $showname[0];
            //排序按钮（稍后添加）
            if ($sort) {
                $parseStr .= '<a href="javascript:sortBy(\'' . $property[0] . '\',\'{$sort}\',\'' . ACTION_NAME . '\')" title="按照' . $showname[2] . '{$sortType} ">' . $showname[0] . '<eq name="order" value="' . $property[0] . '" ><span class="halflings-icon arrow-down" ></span></eq></a></th>';
            } else {
                $parseStr .= $showname[0] . '</th>';
            }
        }
        //如果指定显示操作功能列
        if (!empty($action)) {
            $parseStr .= '<th>操作</th>';
        }

        $parseStr .='</tr></thead><tbody>';
        $parseStr .='<volist name="' . $datasource . '" id="' . $name . '" ><tr/>';
        //checkBox 显示
        if (!empty($checkbox)) {
            $parseStr .= '<td><input type="checkbox" name="key"	value="{$' . $name . '.' . $pk . '}"></td>';
        }
        //是否显示序号
        if (!empty($key)) {
            $parseStr .= '<td>{$i}</td>';
        }
        //其他字段
        foreach ($fields as $field) {
            $parseStr .='<td>';
            //显示超链接
            if (!empty($field[2])) {
                $href = explode('|', $field[2]);
                //print_r($href);
                if (count($href) > 1) {
                    //指定链接，支持多个字段传递
                    $array = explode('^', $href[1]);
                    //addslashes函数在每个双引号（"）前添加反斜杠
                    if (count($array) > 1) {
                        foreach ($array as $a) {
                            $temp[] = '\'{$' . $name . '.' . $a . '|addslashes}\'';
                        }
                        $parseStr .='<a href="javascript:' . $href[0] . '(' . implode(',', $temp) . ')">';
                    } else {
                        $parseStr .='<a href="javascript:' . $href[0] . '(\'{$' . $name . '.' . $href[1] . '|addslashes}\')">';
                    }
                } else {
                    $parseStr .='<a href="javascript:' . $href[0] . '(\'{$' . $name . '.' . $pk . '|addslashes}\')">';
                }
            }
            if (strpos($field[0], '^')) {
                $property = explode('^', $field[0]);
                foreach ($property as $p) {
                    $unit = explode('|', $p);
                    if (count($unit) > 1) {
                        $parseStr .= '{$' . $name . '.' . $unit[0] . '|' . $unit[1] . '}';
                    } else {
                        $parseStr .= '{$' . $name . '.' . $p . '}';
                    }
                }
            } else {
                $property = explode('|', $field[0]);
                if (count($property) > 1) {
                    $parseStr .= '{$' . $name . '.' . $property[0] . '|' . $property[1] . '}';
                } else {
                    $parseStr .= '{$' . $name . '.' . $field[0] . '}';
                }
            }

            if (!empty($field[2])) {
                $parseStr .='</a>';
            }
            $parseStr .= '</td>';
        }

        //显示功能列表
        if (!empty($action)) {
            if (!empty($actionlist[0])) {
                $parseStr .= '<td>';
                foreach ($actionlist as $val) {
                    if (strpos($val, ':')) {
                        $a = explode(':', $val);
                        if (count($a) > 2) {
                            $parseStr .= '<a href="javascript:' . $a[0] . '(\'{$' . $name . '.' . $a[2] . '}\')">' . $a[2] . '</a>&nbsp;';
                        } else {
                            $parseStr .= '<a href="javascript:' . $a[0] . '(\'{$' . $name . '.' . $a[2] . '}\')">' . $pk . '</a>&nbsp;';
                        }
                    } else {
                        $array = explode('|', $val);
                        if (count($array) > 2) {
                            $parseStr .= '<a href="javascript:' . $array[1] . '(\'{$' . $name . '.' . $array[0] . '}\')">' . $array[2] . '</a>&nbsp;';
                        } else {
                            $parseStr .= ' {$' . $name . '.' . $val . '}&nbsp;';
                        }
                    }
                }
                $parseStr .='</td>';
            }
        }
        $parseStr .='</tr></volist></tbody></table>';
        $parseStr .="\n<!-- Think 系统列表组件结束 -->\n";
        return $parseStr;
    }

}

?>
