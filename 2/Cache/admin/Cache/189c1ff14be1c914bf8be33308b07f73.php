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
    </script></head><body width="100%"><div id="result" class="result none"></div><div class="mainbox"><script type="text/javascript">$(function(){
	$.fn.allselect({
			allselectclass:"allselect",//class
			cselectname: "c_selected"//name
	}); 
});
</script><div id="nav" class="mainnav_title"><ul><a class="on" href="<?php echo U('show_list');?>">线路列表</a> |
        <a href="<?php echo U('add');?>">添加线路</a></ul></div><form action="<?php echo U('show_list');?>" method="get"><table class="search_table" width="100%"><tr><td class="search">                标题: <input id="title" type="text" class="input-text" name="names" value="<?php echo ($get["names"]); ?>">                &nbsp;
                <input type="submit" value="查询" class="button"></td></tr></table></form><form name="myform" id="myform" action="" method="post"><div class="table-list"><table cellspacing="0" cellpadding="0"  width="100%"><thead><tr><th width="80"><a href="javascript:void(0);" onclick="sel_all();">全选</a></th><th>封面</th><th>编号</th><th style="text-align: left;">名称</th><th>属性</th><th>出发城市</th><th>排序</th><th>操作</th></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="ulbc" id="tr_<?php echo ($vo["id"]); ?>"><td id="td_sel"><input type="checkbox" name="deleteall[<?php echo ($vo["id"]); ?>]" class="sel c_selected" id="sel_<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["id"]); ?>"></td><td><img src='<?php echo (get_line_img($vo["id"])); ?>' style="width:100px;height:80px" /></td><td><span><?php echo ($vo["code"]); ?></span></td><td style="text-align: left;"><a href="__ROOT__/index.php/travel/detail/id/<?php echo ($vo["id"]); ?>" target="_blank"><?php echo ($vo["names"]); ?></a></td><td><?php if($vo["property"] == 1): ?>普通<?php elseif($vo["property"] == 2): ?>特价<?php elseif($vo["property"] == 3): ?>新品<?php elseif($vo["property"] == 4): ?>热门<?php endif; ?></td><td><?php echo (_get_city($vo["city_id"])); ?></td><td><input type="text" name="sort[<?php echo ($vo["id"]); ?>]" id="sort_<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["sort"]); ?>"></td><td><a href="<?php echo U('lineorder/show_list');?>">订单</a>&nbsp;
                    <a href="__URL__/pic_list/line_id/<?php echo ($vo["id"]); ?>">图片</a>&nbsp;
                    <a href="__URL__/price_list/line_id/<?php echo ($vo["id"]); ?>">价格</a>&nbsp;
                    <a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>">编辑</a>&nbsp;
                    <a href="__URL__/del/id/<?php echo ($vo["id"]); ?>" onclick="return atr_confirm(this.href)">删除</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><input type="hidden" name="base_pos" value="<?php echo MODULE_NAME;?>"><div class="btn"><input type="button" class="button" name="dosubmit" value="删除" onclick="myform.action = '<?php echo U('deleteall');?>';return confirm_deleteall(this.form)"><input type="button" class="button" name="dosubmit" value="排序"
                   onclick="myform.action = '<?php echo U('line/reset_sort',array('jumpurl'=>base64_encode('line/show_list')));?>'; $('#myform').trigger('submit');"/></div><div class="page"><?php echo ($page); ?></div></div></form><script type="text/javascript">	function sel_all(){
	    $('input.sel').each(function(index){
	        if($(this).attr('checked')){
	            $(this).removeAttr('checked');
	        }else{
	            $(this).attr('checked', 'checked');
	        }
	    });
	}

</script><div class="clear"></div><div id="footer">途乐欢迎您
    </div></body></html>