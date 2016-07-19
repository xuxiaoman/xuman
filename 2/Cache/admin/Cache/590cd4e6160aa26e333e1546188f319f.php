<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo C('DEFAULT_CHARSET');?>"/><title><?php echo C('welcome');?></title><script type="text/javascript">        var _root_ = '__ROOT__';
        var _url_ = '__URL__';
        var _upload_ = '__UPLOAD__';
        var _app_ = '__APP__';
        var _public_ = '__PUBLIC__';
        var _index_ = '__Index__';
    </script><script type="text/javascript" src="../Public/js/public.js"></script><link rel="stylesheet" type="text/css" href="../Public/css/style.css"/><script type="text/javascript" src="__ROOT__/Public/js/jquery-1.8.2.min.js"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/jquery.artDialog/jquery.artDialog.js?skin=simple"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/jquery.artDialog/iframeTools.js"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/jquery.artDialog/atrDialog.function.js"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/uploadify/jquery.uploadify-3.1.min.js"></script><link rel="stylesheet" href="__ROOT__/Public/Plugins/uploadify/uploadify.css"/><script type="text/javascript" src="__ROOT__/Public/Plugins/uploadify/jquery.jUpload.js"></script><link rel="stylesheet" type="text/css" href="../Public/css/ui-lightness/jquery-ui-1.9.0.custom.min.css" /><link rel="stylesheet" type="text/css" href="../Public/css/datepicker.css" /><script type="text/javascript" src="../Public/js/jquery.ui.core.min.js"></script><script type="text/javascript" src="../Public/js/jquery.ui.datepicker.min.js"></script><script src="__ROOT__/Public/js/jquery.cityLink.js"></script><script src="http://api.map.baidu.com/api?key=02b627b5ad5892889e9384d61d91bd08&v=1.1&services=true" type="text/javascript"></script><script src="http://api.map.baidu.com/getscript?v=1.1&ak=&services=true&t=20130716024057" type="text/javascript"></script><script src="__ROOT__/Public/js/baidu.js"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/Validform/Validform_v5.3.2_min.js"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/Validform/Validform_Datatype.js"></script><link rel="stylesheet" href="__ROOT__/Public/Plugins/Validform/validform.css"/><script type="text/javascript" src="__ROOT__/Public/Kindeditor/kindeditor-min.js"></script><link rel="stylesheet" type="text/css" href="__ROOT__/Public/Kindeditor/plugins/code/prettify.css"/><script type="text/javascript" src="__ROOT__/Public/js/jquery.allselect.js?23"></script><script type="text/javascript">        if (self == top) {
            //window.top.location.href = '<?php echo U("login/index");?>';
        }
        window.kinds = {};
        KindEditor.ready(function (K) {
            KindEditor.options.upImgUrl = "<?php echo U('uploadify/kind');?>";
            KindEditor.options.uploadJson = "<?php echo U('uploadify/kind');?>";
            KindEditor.options.upFlashUrl = "<?php echo U('uploadify/kind');?>";
            KindEditor.options.upMediaUrl = "<?php echo U('uploadify/kind');?>";
            KindEditor.options.minWidth = 750;
            KindEditor.options.minHeight = 280;
			 KindEditor.options.items = ["source","|","undo","redo","|","cut","copy","paste","plainpaste","wordpaste","|","justifyleft","justifycenter","justifyright","justifyfull","insertorderedlist","insertunorderedlist","indent","outdent","subscript","superscript","clearhtml","quickformat","selectall","|","fullscreen","/","formatblock","fontname","fontsize","|","forecolor","hilitecolor","bold","italic","underline","strikethrough","lineheight","removeformat","|","image","flash","media","insertfile","table","hr","emoticons","pagebreak","anchor","link","unlink","|","about"];
            $(".kind-text").each(function (i, n) {
                var id = $(n).attr("id") || "kind_" + i;
                $(n).attr("id", id);
                var width = $(n).css("width");
                var height = $(n).css("height");
                window.kinds[id] = K.create(this, $.extend(KindEditor.options,
                        {
                            editorid: id,
                            width: width,
                            height: height,
                            afterBlur: function () {
                                window.kinds[id].sync();
                                $("#" + id).trigger("blur");
                            },
                            afterFocus: function () {
                                window.kinds[id].sync();
                                $("#" + id).trigger("focus");
                            },
                            afterChange: function () {
                                window.kinds[id].sync();
                                $("#" + id).trigger("change");
                            }
                        }));
            });
        });
        $(function () {
		
            $.fn.extend({
                create_calender: function () {
                    var formats = $(this).attr("format") || "yy-mm-dd";
                    var yearRange = $(this).attr("year") || "c-60:c+20";
                    try {
                        $(this).datepicker({
                            changeMonth: true,
                            changeYear: true,
                            yearRange: yearRange,
                            dateFormat: formats,
                            onSelect: function () {
                                if (window.Validform[this.form.id]) {
                                    window.Validform[this.form.id].check(false,this);
                                }
                            }
                        });
                    } catch (ex) {
                    }
                }})
            $("input.calender,#birthday_picker").create_calender();
        })

        function getRandom(n) {
            return Math.floor(Math.random() * n + 1)
        }
    </script></head><body width="100%"><div id="result" class="result none"></div><div class="mainbox"><style type="text/css">                .mainnav_title{ display:none;}
                h1 { height:30px;line-height:30px;font-size:14px;padding-left:15px;background:#EEE;border-bottom:1px solid #ddd;border-right:1px solid #ddd;overflow:hidden;zoom:1;margin-bottom:10px;}
                h1 b {color:#3865B8;}
                h1 span {color:#ccc;font-size:10px;margin-left:10px;}
                #Profile{ width:48%; height:191px; float:left;margin:5px 15px 0 0;}
                #system {width:48%;float:left;margin:5px 15px 0 0;}
                .list ul{ border:1px #ddd solid;  overflow:hidden;border-bottom:none;}
                .list ul li{ border-bottom:1px #ddd solid; height:26px;  overflow:hidden;zoom:1; line-height:26px; color:#777;padding-left:5px;}
                .list ul li span{ display:block; float:left; color:#777;width:100px;}

                #sitestats {width:48%; height:191px; float:left;margin:5px 0  0 0;overflow:hidden;}
                #sitestats div {_width:99.5%;border:1px solid #ddd;overflow:hidden;zoom:1;}
                #sitestats ul {overflow:hidden;zoom:1;width:102%;padding:2px 0 0 2px;_padding:1px 0 0 1px;height:132px;}
                #sitestats ul li {float:left;height:44px; float:left; width:16.1%;_width:16.3%;text-align:center;border-right:1px solid #fff;border-bottom:none;}
                #sitestats ul li b {float:left;width:100%;height:21px;line-height:22px;  background:#EFEFEF;color:#777;font-weight:normal;}
                #sitestats ul li span {float:left;width:100%;color:#3865B8;background:#F8F8F8;height:21px;line-height:21px;overflow:hidden;zoom:1;}

                #tripecnews {width:48%;float:left;margin:5px 15px 0 0;}

                #tabs {margin:0px auto;overflow:hidden;border:1px solid #ccc; height:214px;}
                #tabs .title {overflow:hidden;background:url(../Public/Images/tab_bottom_line_1.jpg) repeat-x 0 26px;height:27px;}
                #tabs .title ul li {float:left;margin-left:-1px;height:26px;line-height:26px;background:#EAEEF4;padding:0px 10px;border:1px solid #ccc;border-top:0;border-bottom:0;}
                #tabs .title ul li.on {background:#FFF;height:27px;}
                #tabs .content_1 { overflow:hidden;border-top:none;}
                #tabs .tabbox {display:none;padding:10px;}

                #tabs .tabbox ul li {background:url(../Public/Images/ico_1.gif) no-repeat 4px 11px;padding-left:13px;border-bottom:1px #ddd dashed; height:27px;  line-height:26px;color:#333 }
                #tabs .tabbox ul li a {color:#333}
                #tabs .tabbox ul li a:hover {color:#FB0000;}
                #tabs .tabbox ul li span.date {float:right;color:#777}
                #tripec_sn {color:#FB0000;font-weight:normal;}
                #tripec_license {font-weight:normal;color:blue;}
                #tripec_license a {color:#FB0000;}
            </style><div id="Profile" class="list"><h1><b>个人信息</b><span>Profile&nbsp; Info</span></h1><ul><li><span>会员名:</span><?php echo ($_SESSION["admin_login_names"]); ?></li><li><span>所属会员组:</span><?php echo ($group_name); ?></li><li><span>最后登陆时间:</span><?php echo (date("Y-m-d H:i:s",$_SESSION["last_login_time"])); ?></li><li><span>最后登陆IP:</span><?php echo ($_SESSION["last_login_ip"]); ?></li><li><span>登陆次数:</span><?php echo ($_SESSION["login_count"]); ?>次</li></ul></div><div id="sitestats" ><h1><b>网站统计</b><span>Site&nbsp; Stats</span></h1><div><ul><li><b>旅游线路</b><br><span><?php echo ($counts["line"]); ?></span></li><li><b>酒店宾馆</b><br><span><?php echo ($counts["hotel"]); ?></span></li><li><b>景点门票</b><br><span><?php echo ($counts["viewpoint"]); ?></span></li><li><b>线路订单</b><br><span><?php echo ($counts["line_order"]); ?></span></li><li><b>酒店订单</b><br><span><?php echo ($counts["hotel_order"]); ?></span></li><li><b>景点订单</b><br><span><?php echo ($counts["viewpoint_order"]); ?></span></li><li><b>文章总数</b><br><span><?php echo ($counts["article"]); ?></span></li><li><b>会员总数</b><br><span><?php echo ($counts["user"]); ?></span></li><li><b>栏目总数</b><br><span><?php echo ($counts["article_section"]); ?></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li><li><b></b><span></span></li></ul></div></div><div id="system"  class="list"><h1><b>系统信息</b><span>System&nbsp; Info</span></h1><ul><li><span>运行环境:</span><?php echo ($tripec_info["SERVER_SOFTWARE"]); ?></li><li><span>PHP运行方式:</span><?php echo ($tripec_info["mysql_get_server_info"]); ?></li><li><span>MYSQL版本:</span><?php echo ($tripec_info["MYSQL_VERSION"]); ?></li><li><span>上传附件限制:</span><?php echo ($tripec_info["upload_max_filesize"]); ?></li><li><span>执行时间限制:</span><?php echo ($tripec_info["max_execution_time"]); ?></li><li><span>剩余空间:</span><?php echo ($tripec_info["disk_free_space"]); ?></li></ul></div><div class="clear"></div><div id="footer">途乐欢迎您
    </div></body></html>