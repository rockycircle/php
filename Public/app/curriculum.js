/**
 *学课程基本信息管理模块
 **/
$(function () {
    //课程信息列表
    $('#manager-curriculum').datagrid({
        url: Think.APP + "Curriculum/index&cmd=InfoCurriculum", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#curriculum-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
                {title: 'ID', field: '', checkbox: true},
                {field: 'cu_id', title: '课程编号', width: 100, align: 'center'},
                {field: 'cu_name', title: '名称', width: 100, align: 'center'},
                {field: 'cu_point', title: '学分', width: 100, align: 'center'},
                {field: 'cu_time', title: '课时', width: 100, align: 'center'},
                {field: 'cu_book', title: '对应书籍', width: 100, align: 'center'},
                {field: 'ico_id', title: '学部', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.college("STORE"), value);
                    }},
                {field: 'cu_note', title: '备注', width: 100, align: 'center'},
            ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-curriculum').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-curriculum').datagrid("selectRow", rowIndex);  //选中当前行
        },
        onDblClickRow: function (field, row) {
            var url = Think.APP + "Curriculum/edit&cmd=InfoCurriculum&cu_id=" + row.cu_id;
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-curriculum", "#add-curriculum-form", "修改学生", url, row, 3);
        }
    });
    
    //专业课程列表
    $('#manager-my-curriculum').datagrid({
        url: Think.APP + "Curriculum/index&cmd=MgCurriculum&is_user=my", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#curriculum-my-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
                {title: 'ID', field: 'mc_id', checkbox: true},
                {field: 'mc_year', title: '选课年份', width: 100, align: 'center'},
                {field: 'cu_id', hidden: true, width: 100, align: 'center'},
                {field: 'cu_name', title: '课程', width: 100, align: 'center'},
                {field: 'mc_number', title: '学期', width: 100, align: 'center'},
                {field: 'id_id', title: '专业', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.discipline("STORE"), value);
                    }},
                {field: 'icl_id', title: '班级', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.classinfo("STORE"), value);
                    }},
                {field: 'mc_grade', title: '专业年级', width: 100, align: 'center'},
                {field: 'ico_id', title: '学院', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.college("STORE"), value);
                    }},
                {field: 'it_id', title: '任课教师', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.teacher("STORE"), value);
                    }},
                {field: 'mc_status', title: '是否创建选课', width: 100, align: 'mc_status', formatter: function (value, row, index) {
                        if (value == "9") {
                            return "<font style='color:red'>已创建<font>";
                        } else {
                            return "未创建";
                        }

                    }},
                {field: 'mc_note', title: '备注', width: 100, align: 'center'},
            ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-my-curriculum').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-my-curriculum').datagrid("selectRow", rowIndex);  //选中当前行
        }
    });
   
    //专业课程列表
    $('#manager-mg-curriculum').datagrid({
        url: Think.APP + "Curriculum/index&cmd=MgCurriculum", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#curriculum-mg-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
                {title: 'ID', field: 'mc_id', checkbox: true},
                {field: 'mc_year', title: '选课年份', width: 100, align: 'center'},
                {field: 'cu_id', hidden: true, width: 100, align: 'center'},
                {field: 'cu_name', title: '课程', width: 100, align: 'center'},
                {field: 'mc_number', title: '学期', width: 100, align: 'center'},
                {field: 'id_id', title: '专业', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.discipline("STORE"), value);
                    }},
                {field: 'icl_id', title: '班级', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.classinfo("STORE"), value);
                    }},
                {field: 'mc_grade', title: '专业年级', width: 100, align: 'center'},
                {field: 'ico_id', title: '学院', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.college("STORE"), value);
                    }},
                {field: 'it_id', title: '任课教师', width: 100, align: 'center', formatter: function (value, row, index) {
                        if (value <= 0) {
                            return "无";
                        }
                        return Store().converter(app_data.info.teacher("STORE"), value);
                    }},
                {field: 'mc_status', title: '是否创建选课', width: 100, align: 'mc_status', formatter: function (value, row, index) {
                        if (value == "9") {
                            return "<font style='color:red'>已创建<font>";
                        } else {
                            return "未创建";
                        }

                    }},
                {field: 'mc_note', title: '备注', width: 100, align: 'center'},
            ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-mg-curriculum').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-mg-curriculum').datagrid("selectRow", rowIndex);  //选中当前行
        }
    });

    //添加方法
    curriculum_toolbar = {
        addCurriculum: function () {  //添加
            var url = Think.APP + "Curriculum/insert&cmd=InfoCurriculum";
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-curriculum", "#add-curriculum-form", "添加课程", url);
        },
        /**
         *  创建课程评价信息
         */
        createReview: function () {
            var url = Think.APP + "TeachReview/createReview";
            //获取选中的数据信息
            var selectData = $("#manager-mg-curriculum").datagrid("getSelections");
            var data = [];
            if (selectData.length > 0) {
                //组装数据
                for (var i = 0; i < selectData.length; i++) {
                    data.push(selectData[i].mc_id);
                }
            }
            var loaddata = {"mc_id": data.join(',')};
            Xwindow.baseDialogInit("#manager-mg-curriculum", "#create-review-form", "创建评教", url, loaddata, 2, true);
        },
        //导入专业课程信息
        importCurriculumInfo:function(){
            Xwindow.upload(Think.APP + "Curriculum/uploadMgCurriculumStep1",
                           Think.APP + "Curriculum/uploadMgCurriculumStep2");
        },
        importCurriculum:function(){
            Xwindow.upload(Think.APP + "Curriculum/uploadCurriculumStep1",
                           Think.APP + "Curriculum/uploadCurriculumStep2");
        },
         //搜索课程信息
        searchCurriculum:function(value, name){
            //if (value || false) {
                $('#manager-curriculum').datagrid("load", {
                    "cu_name": value
                });
            //}
        },
        searchMyCurriculum:function(value,name){
            $('#manager-my-curriculum').datagrid("load", {
                    "cu_name": value
            });
        },
        //删除课程信息
        deleteCurriculum:function(){
            var url =  Think.APP + "Curriculum/del&cmd=InfoCurriculum";
            Xwindow.baseDelete( "#manager-curriculum",url,"cu_id");
        },
        //删除选课
        deleteMgCurriculum:function(){
            var url =  Think.APP + "Curriculum/del&cmd=MgCurriculum";
            Xwindow.baseDelete( "#manager-mg-curriculum",url,"mc_id");
        }
        
    };

});