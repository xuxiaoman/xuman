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
    </script></head><body width="100%"><div id="result" class="result none"></div><div class="mainbox"><div id="nav" class="mainnav_title"><ul><a class="on" href="<?php echo U('show_list');?>/t/<?php echo ($types); ?>">所属城市</a> |
        <a href="javascript:;" onclick="art.dialog.open('<?php echo U('citybelong/add',array('t'=>$types));?>',{title:'添加所属城市',width:560,height:300,window:'top',lock:'true'});">添加</a></ul></div><form action="" method="get"><table class="search_table" width="100%"><tr><td class="search">                关键词: <input id="title" type="text" class="input-text" name="names" value="<?php echo ($search_key); ?>">                &nbsp;
            </select><input type="submit" name="submit" value="查询" class="button"></td></tr></table></form><form name="myform" id="myform" action="" method="post"><div class="table-list"><table cellspacing="0" cellpadding="0" width="100%"><thead><tr><th width="135"><a href="javascript:void(0);" onclick="sel_all();">全选</a></th><th style="text-align: center;">名称</th><th width="140">排序</th><th>操作</th></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="ulbc" id="tr_<?php echo ($vo["id"]); ?>"><td style="text-align:center;"><input type="checkbox" class="sel" id="sel_<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["id"]); ?>" name="items_<?php echo ($vo["id"]); ?>"></td><td style="text-align: center;"><?php echo ($vo["names"]); ?></td><td align="center"><input type="text" name="sort[<?php echo ($vo["id"]); ?>]" id="sort_<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["sort"]); ?>"></td><td align="center"><?php if(($types) == "HOTEL"): ?><a href="<?php echo U('list_areas',array('city'=>$vo[id]));?>">城市区域列表</a>&nbsp;|<?php endif; ?>&nbsp;<a href="#" onclick="javascript:if(confirm( '确定要删除该条记录吗？ '))ajax_del(<?php echo ($vo["id"]); ?>);">删除</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table></div><div class="btn"><input type="submit" class="button" name="dosubmit" value="删除" onclick="myform.action='<?php echo U('deleteall');?>';"><!-- input type="button" class="button" name="dosubmit" value="删除" onclick="myform.action='<?php echo U('deleteall');?>';return confirm_deleteall() / --><input type="button" class="button" name="dosubmit" value="排序" onclick="myform.action='<?php echo U('sort_list');?>/t/<?php echo ($types); ?>';$('#myform').submit();"></div></form><script language="javascript">    function ajax_del(id){
        var url = "<?php echo U(ajax_del);?>?id="+id;
		
        $.ajax({
            url: url,
            type: "POST",
            complete :function(){
            }, 
            dataType: 'json', 
            error: function() { alert('Ajax request error');},
            success: function(result){
                if(result==1){
					art.dialog({
						title: '删除提示',
						content: '删除成功',
						ok:function(){location.reload();},
					});
					//$("#tr_"+id).remove();
					
                    
                }
            }
        }); 
    }
	
	function sel_all(){
        $('input.sel').each(function(index){
            if($(this).attr('checked')){
                $(this).removeAttr('checked');
            }else{
                $(this).attr('checked', 'checked');
            }
        });
    }
</script><div class="page"><?php echo ($page); ?></div></div></body></html>