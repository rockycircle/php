<?php
namespace Rbac\Controller;

/**
 * 定时任务控制器
 * @author YangZhe
 */
class CrontabController {
    
    /**
     * 定时运行评教信息
     */
    public function runTeacherReviewGrade(){
        //检查是否有到期的数据
        $reviewInfoModel = M("review_info");
        $reviewTeacherInfoModel = M("review_teacher_info");
        $reviewStudentInfoModel = M("review_student_info");
        $map = array(
            "review_end_time"=>array('ELT',date("Y-m-d H:i:s")),//<当前日期
            "review_status"=>1  //未结束
        );
        $reviewInfoList = $reviewInfoModel->where($map)->select();
        //改变表的状态，评教结束
        foreach($reviewInfoList as $reviewInfo){
              //应评教人数
              $count = $reviewStudentInfoModel->where(array("review_id"=>$reviewInfo["review_id"]))->count();
              //评教人数和分数
              $where = array("review_id"=>$reviewInfo["review_id"],
                             "review_status"=>9);
              $reviewCount = $reviewStudentInfoModel->where($where)->count(); //已评教
              $reviewSumGrade = $reviewStudentInfoModel->where($where)->sum('review_grade');
              $reviewSumGrade = intval($reviewSumGrade);
              //取出学生评教的分数
              if($reviewCount==0){
                  $reviewGrade = 0;
              } else {
                  $reviewGrade = round($reviewSumGrade/$reviewCount,2); //平均分数
              }
              $row["review_grade"] = $reviewGrade;//平均分数
              $row["review_count"] = $reviewCount;//已评教人数
              $row["reivew_origin_count"] = $count;//原本学生人数
              $row["review_sum_grade"] = $reviewSumGrade ;//总分数
              $row["it_id"] = $reviewInfo["it_id"];
              $row["ico_id"] = $reviewInfo["ico_id"];
              $row["cu_name"] = $reviewInfo["cu_name"];
              $row["id_id"] = $reviewInfo["id_id"];
              $row["icl_id"] = $reviewInfo["icl_id"];
              $row["review_id"] = $reviewInfo["review_id"];
              //$row["mc_id"] = $reviewInfo["mc_id"];
              $row["review_create_date"] = date("Y-m-d H:i:s");
              //检测review_id是否存在
              $r = $reviewTeacherInfoModel->where(array("review_id"=>$reviewInfo["review_id"]))->find();
              if(empty($r)){
                  $reviewTeacherInfoModel->add($row);
              }
        }
       echo "计算评教分数完毕\n";
    }
}
