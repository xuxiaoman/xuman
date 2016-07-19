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
    </script></head><body width="100%"><div id="result" class="result none"></div><div class="mainbox"><script type="text/javascript">    var root = "__ROOT__", index = "__Index__";
</script><link rel="stylesheet"
	href="__ROOT__/Public/Plugins/uploadify/uploadify.css" /><script type="text/javascript"
	src="__ROOT__/Public/Plugins/uploadify/jquery.uploadify-3.1.min.js"></script><script type="text/javascript"
	src="__ROOT__/Public/Plugins/uploadify/jquery.jUpload.js"></script><script type="text/javascript">     $(function () {
		 $('#show_msg').show();
                $("#upload1").jUpload({
                    trigger_id: "t_upload1",
                    uploader: "<?php echo U('uploadify/head_img');?>",
                    queueID: "upload1-queue",
                    formData: {
                        "<?php echo session_name(); ?>": "<?php echo session_id(); ?>",
                        "savePath": "/Upload/images/section_pic/",
                        "saveRule": "time",
                        "divId": "img1"
                    },
                    success: function (data, info) {
						$("#photo_1").val(data[0].path);
						art.dialog.alert(info);
                    }
                });
            })
function formcheck()
{
	return true;
}

</script><div id="nav" class="mainnav_title"><ul><a href="<?php echo U('classify_management');?>">栏目列表</a> |
        <a class="on" href="<?php echo U('classify_add');?>">添加栏目</a></ul></div><form action="<?php echo U('classify_add');?>" name="form1"enctype="multipart/form-data" method="post" onsubmit="return formcheck();"><input type="hidden" value="2" name="cid"><table border="0" cellspacing="0" cellpadding="0" class="table_form" width="100%"><tr><th width="128"><span class="red">*</span>请选择模型：</th><td colspan="2"><select id="model" name="model" class="txt"><option value="0" selected="selected">文章模型</option><option value="1">单页模型</option></select></td></tr><tr><th>选择上级栏目：</th><td colspan="2"><select id="parentid" name="pid" class="txt"><option value="0">作为顶级栏目</option><?php echo ($psection); ?></select></td></tr><tr><th><span class="red">*</span>栏目名称：</th><td colspan="2"><input type="text" name="names" id="names" class="txt" datatype="*" nullmsg="请填写栏目名称" errmsg="请填写栏目名称" sucmsg=" "></td></tr><tr><th><span class="red">*</span>栏目英文名称：</th><td colspan="2"><input type="text" name="e_names" id="e_names" class="txt" datatype="*" nullmsg="请填写栏目英文名称" errmsg="请填写栏目英文名称" sucmsg=" "></td></tr><tr style="display:none;"><th>栏目图片：</th><td colspan="2"><div id="img1"></div><div id="upload1-queue" style="margin-top:6px;"></div><input class="isFile" type="hidden" id="photo_1" name="pic_front"><input data-msg-isfile="请上传图片" class="isFile" id="upload1" name="file_upload1" type="file"><div class="f_left" style="margin:12px 0 0 6px;"><a class="upload_bt" href="javascript:void(0);" id="t_upload1" onclick="$('#show_msg').hide();">点击上传</a></div><span class="Validform_checktip f_left" id="show_msg" style="color:#F00"></span></td></tr><tr><th><span class="red">*</span>描述：</th><td colspan="2"><textarea name="memo" cols="50" rows="8" id="memo" datatype="*" nullmsg="请填写描述" errmsg="请填写描述" sucmsg=" " class="f_left"></textarea></td></tr><tr><th height="30"><span class="red">*</span>是否在导航显示：</th><td colspan="2"><input type="radio" checked="" id="n1" value="1" name="status"><label for="n1">是</label>&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" value="0" id="n2" name="status"><label for="n2">否</label></td></tr><tr style="display:none;"><th><span class="red">*</span>排序：</th><td colspan="2"><input name="sort" type="text" id="sort" class="txt" value="0" datatype="n" nullmsg="请填写排序" errmsg="请填写数字" sucmsg=" "><span id="red_sort" class="red"></span></td></tr></table><div id="btnbox" class="btn"><INPUT TYPE="submit" value=" 添 加 " name="save" class="button"><input name="rset" TYPE="reset" class="button" id="rset" value=" 重 置 "></div></form><div class="clear"></div><div id="footer">途乐欢迎您
    </div></body></html>