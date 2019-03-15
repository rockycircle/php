<?php
namespace Rbac\C\Xpublic;
// 下载CSV格式
class DownCSV{
    
    private $fp = null;

    public function __construct(){
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $this->fp = fopen('php://output', 'a');
    }
    
    
    /**
     * 生成当前
     */
    public function downRun($title = 't_csv'){
        // 输出Excel文件头，可把user.csv换成你要的文件名
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$title.'.csv"');
        header('Cache-Control: max-age=0');
    }
    
    /**
     * csv的标题
     * @param array $head
     */
    public function saveHeader($head){
        $t_head = array();
        foreach ($head as $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $t_head[] = iconv('utf-8', 'gbk', $v);
        }
        
        // 将数据通过fputcsv写到文件句柄
        fputcsv($this->fp, $t_head);
    }
    
    /**
     *  这一种是 一点一点的保存数据 使用这种一定要记得关闭fp
     *  @param array $dataField   ['id','age, 'name']
     *  @param array $data   [ ['id'=>1, 'age'=>20, 'name'=>'ZH'], ['id'=>1, 'age'=>20, 'name'=>'ZH'] ]
     *  @param array $replace 某些字段需要替换  
     * */
    public function saveSomeData($dataField,$data,$replace=array()){
        // 计数器
        $cnt = 0;
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 5000;
        foreach ($data as $row) {
            $cnt ++;
            if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush();
                flush();
                $cnt = 0;
            }
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $temp = array();
        
            foreach ($dataField as $v){
                if(!empty($replace[$v]) &&  !empty($replace[$v][ $row[$v]])){
                    $temp[] = iconv('utf-8', 'gbk', $replace[$v][ $row[$v] ]);
                }else{
                    $temp[] = iconv('utf-8', 'gbk', $row[$v]);
                }
            }
            fputcsv($this->fp, $temp);
            unset($temp);
        }
    }
    
    /**
     * 直接保存标题 和 数据
     * @param array $head   ['id'=>'编号', 'age'=>'年龄', 'name'=>'姓名']
     * @param array $data   [ ['id'=>1, 'age'=>20, 'name'=>'ZH'], ['id'=>1, 'age'=>20, 'name'=>'ZH'] ]
     */
    public function saveBody($head,$data){
        
        $dataField = array_keys($head);   //键值对
        
        $t_head = array_values($head);
        $this->saveHeader($t_head);
        
        $this->saveSomeData($dataField, $data);
        
        $this->down_close();
    }
    
    /*关闭fp*/
    public function down_close(){
    	if($this->fp){
    	    fclose($this->fp);
    	}
    }
}