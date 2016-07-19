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
		 $('#show_msg').hide();
                $("#upload1").jUpload({
                    trigger_id: "t_upload1",
                    uploader: "<?php echo U('uploadify/head_img');?>",
                    queueID: "upload1-queue",
                    formData: {
                        "<?php echo session_name(); ?>": "<?php echo session_id(); ?>",
                        "savePath": "/Upload/images/articles/",
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
</script><div id="nav" class="mainnav_title"><ul><a href="<?php echo U('lists');?>">文章列表</a> |
      <a class="on" href="<?php echo U('add');?>">添加文章</a></ul></div><form action="<?php echo U('add');?>" enctype="multipart/form-data" method="post" onSubmit="return formcheck();" id="add_form"><table border="0" cellspacing="0" cellpadding="0" class="table_form" width="100%"><tr><th width="126" style="width:120px;"><span class="red">*</span>栏目选择：</th><td colspan="2"><select name="cid" class="txt"><?php if(is_array($a_sections)): $i = 0; $__LIST__ = $a_sections;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["names"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><th><span class="red">*</span>标题：</th><td colspan="2"><input type="text" name="title" id="title" class="txt" size="60" datatype="*" nullmsg="请填写文章标题" errmsg="请填写文章标题" sucmsg=" "></td></tr><tr><th><span class="red">*</span>内容：</th><td><textarea name="content" cols="100" rows="20" id="content" class="kind-text" datatype="*1-65535" nullmsg="请填写文章内容" errmsg="文章内容最多填写65535个字符" sucmsg=" "></textarea></td><td width="220">&nbsp;</td></tr><tr><th><span class="red">*</span>文章来源：</th><td colspan="2"><input type="text" name="source" id="source" class="txt" size="30" datatype="*" nullmsg="请填写文章来源" errmsg="请填写文章来源" sucmsg=" "></td></tr><tr><th>文章封面：</th><td colspan="2"><div id="img1"></div><div id="upload1-queue" style="margin-top:6px;"></div><input class="isFile" type="hidden" id="photo_1" name="pic_front"><input data-msg-isfile="请上传图片" class="isFile" id="upload1" name="file_upload1" type="file"><div class="f_left" style="margin:12px 0 0 6px;"><a class="upload_bt" href="javascript:void(0);" id="t_upload1" onclick="$('#show_msg').hide();">点击上传</a></div><span class="Validform_checktip f_left Validform_wrong" id="show_msg">请先上传图片再提交</span></td></tr><tr><th>文章来源地址：</th><td colspan="2"><input name="source_url" type="text" class="txt" id="source_url" size="60" datatype="url" ignore="ignore" errmsg="请正确填写文章来源地址" sucmsg=" "></td></tr><tr><th><span class="red">*</span>状态：</th><td colspan="2" height="30"><input name="status" type="radio" id="status_1" value="1" checked="checked"><label for="status_1">启用</label>&nbsp;
           <input type="radio" name="status" id="status_2" value="0"><label for="status_2">停用</label></td></tr><tr><th><span class="red">*</span>排序：</th><td colspan="2" height="30"><input name="sort" class="txt" type="text" id="sort" value="1" datatype="n" nullmsg="请填写排序" errmsg="请填写数字" sucmsg=" "></td></tr></table><div id="btnbox" class="btn"><INPUT TYPE="submit" value=" 添 加 " name="save" class="button"><input TYPE="reset" value=" 重 置 " class="button"></div></form><script type="text/javascript">$(function () {
    $("#upload2").jUpload({
        trigger_id: "jtrigger",
        queueID:"queueID"
    });
})
</script></div></body></html>