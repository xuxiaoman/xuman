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
    </script></head><body width="100%"><div id="result" class="result none"></div><div class="mainbox"><div id="nav" class="mainnav_title"><ul><a href="<?php echo U('show_list');?>">线路列表</a> |
        <a class="on" href="<?php echo U('add');?>">添加线路</a></ul></div><link href="../Public/css/tour.css" type='text/css' rel='stylesheet'/><form action="<?php echo U('add');?>" method="post"><ul class="lineNav"><li id="line_1" class="showline yes">基本资料</li><li id="line_2" class="showline">行程安排</li><li id="line_3" class="showline">其它内容</li></ul><table border="0" id="b_line_1" cellspacing="0" cellpadding="0" class="table_form" width="100%"><tr><th width="10%"><span class="red">*</span><em class="tit">线路名称</em>：</th><td><input type="text" datatype="*5-50" name="names" id="names" class="txt"></td></tr><tr><th><span class="red">*</span><em>线路编号</em>：</th><td><input type="text" name="code" id="code" class="txt" datatype="s5-50" ajaxurl="<?php echo U('is_code');?>"></td></tr><tr><th><span class="red">*</span>SEO优化：</th><td><input id="box_SEO" type="checkbox" name="SEO"><label for="box_SEO">SEO优化</label></td></tr><tr name="seo"><th>SEO标题：</th><td><textarea name="title" rows="2" cols="20" id="title" style="height:50px;width:600px;"></textarea><span id="box_title" class="Dp">建议不超出30个字</span></td></tr><tr name="seo"><th>SEO关键词：</th><td><textarea name="keyword" rows="2" cols="20" id="keyword" style="height:50px;width:600px;"></textarea><span id="box_keyword" class="Dp">将被搜索引擎用来搜索您网站的关键内容，每个关键字用英文逗号分隔</span></td></tr><tr name="seo"><th>SEO描述：</th><td><textarea name="detail" rows="2" cols="20" id="detail" style="height:50px;width:600px;"></textarea><span id="box_detail" class="Dp">描述中请不要带英文的逗号，建议不超出80个字</span></td></tr><tr><th><span class="red">*</span><em>参团性质</em>：</th><td><input id="line_type1" type="radio" name="line_type" value="1" checked="checked" datatype="n"><label for="line_type1">参团游</label><input id="line_type2" type="radio" name="line_type" value="2"><label for="line_type2">自由行</label><input id="line_type3" type="radio" name="line_type" value="3"><label for="line_type3">团队游</label><input id="line_type4" type="radio" name="line_type" value="4"><label for="line_type4">自驾游</label></td></tr><tr><th><span class="red">*</span><em>行程天数</em>：</th><td><input type="text" maxlength="3" name="trip_days" id="trip_days" class="txt" value="1" datatype="/^[1-9]\d{0,2}$/"></td></tr><tr><th><span class="red">*</span><em>往返交通</em>：</th><td><input type="text" name="traffic" id="traffic" class="txt" datatype="s2-10"></td></tr><tr><th><span class="red">*</span>出发城市：</th><td><select id="city_id" name="city_id" datatype="hastarget"><?php if(is_array($start_city)): $i = 0; $__LIST__ = $start_city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["cid"]); ?>"><?php echo ($vo["names"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><th>目的地类型：</th><td><select id="target_type" name="target_type"><?php if(is_array($city_type)): $i = 0; $__LIST__ = $city_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["names"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><th><em>目的地</em>：</th><td style="line-height: normal"><div id="Line_target" class="lineDest"></div></td></tr><tr><td align="right">线路主题：</td><td><span id="L_OfferedProperties"><?php if(is_array($line_topic)): $i = 0; $__LIST__ = $line_topic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ltid): $mod = ($i % 2 );++$i;?><input id="topic" type="radio" name="topic" value="<?php echo ($ltid["id"]); ?>"
          <?php if(($key) == "0"): ?>checked="checked"<?php endif; ?>          /><label><?php echo ($ltid["names"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?></span><span id="msg_topic" class="msg"></span></td></tr><tr><td align="right">处理方式：</td><td><input id="deal_type1" type="radio" name="deal_type" value="1" checked="checked"><label for="deal_type1">人工处理</label><input id="deal_type2" type="radio" name="deal_type" value="2"><label for="deal_type2">自动处理</label><label class="red">注："人工处理"订单提交后需经后台审核方可在线支付,"自动处理"订单提交后可直接支付，无需后台审核设置</label><span id="msg_deal_type" class="red"></span></td></tr><tr><td align="right"><em>定金</em>：</td><td><input name="front_money" type="text" class="txt" datatype="/^0|([1-9]d?)|(100)$/" id="front_money" value="" maxlength="10"><label style="float: left"> % 注："0"为前台支付，"100"为全额支付，定金支付请填写相应百份比数字</label></tr><tr><td align="right">属性：</td><td><input id="property1" type="radio" name="property" value="1" checked="checked"><label for="property1">普通</label><input id="property2" type="radio" name="property" value="2"><label for="property2">特价</label><input id="property3" type="radio" name="property" value="3"><label for="property3">新品</label><input id="property4" type="radio" name="property" value="4"><label for="property4">热门</label></td></tr><tr><td align="right">点评奖金：</td><td><input type="text" class="txt" name="bonus_comm" datatype="n"></td></tr><tr><td align="right">抵用奖金：</td><td><input type="text" class="txt" name="award" datatype="n"></td></tr><tr><td align="right">隐藏：</td><td><label><input id="status" type="checkbox" name="status" value="1"></label></td></tr><tr><td align="right"><em>排序</em>：</td><td><input name="sort" type="text" class="input-text-c txt" id="sort" style="width:50px" value="50" maxlength="3"></td></tr><tr><td height="50">&nbsp;</td><td>&nbsp;</td></tr></table><table style="display: none;" id="b_line_2" border="0" cellspacing="0" cellpadding="0" class="table_form" width="100%"><tr><th width="10%">编辑模式：</th><td><input id="type2" type="radio" name="texttype" value="2" checked="checked" onclick="$('#Classical').hide();$('#General').show();">&nbsp;&nbsp;
            <label for="type2">可视化编辑模式</label><input id="type1" type="radio" name="texttype" value="1" onclick="$('#General').hide();$('#Classical').show();">&nbsp;
            <label for="type1">按天编辑模式</label>&nbsp;
        </td></tr><tr><th>行程安排：</th><td><div id="General"><textarea name="General" class="kind-text" style="width:90%"></textarea></div><div id="Classical" style="display:none;"><select id="qh_day" class="valid"><option value="1">第1天</option></select><div id="day_1" class="line_day"><table width="100%"><tr><th width="60">行程安排：</th><td>第1天</td></tr><tr><th>行程标题：</th><td><input class="formTitle" type="text" size="50" value="" name="title_1" id="title_1"></td></tr><tr><th>用餐：</th><td><input type="checkbox" id="dining_1_1" value="1" name="dining_1[]"><label for="dining_1_1">早餐</label><input id="dining_1_2" type="checkbox" value="2" name="dining_1[]"><label for="dining_1_2">中餐</label><input id="dining_1_3" type="checkbox" value="3" name="dining_1[]"><label for="dining_1_3">晚餐</label></td></tr><tr><th>住宿：</th><td><input id="stay_1" class="valid" type="text" maxlength="50" name="stay_1"></td></tr></table><div class="lineRowText"><ul id="ActivityTitle_1" class="lineRowSub"><li><img class="add-con" alt="增加1段" src="../Public/images/toolsAdd.png"></li><li><img class="del-con" alt="删除1段" src="../Public/images/toolsDell.png"></li><li class="yes">第<b>1</b>段</li></ul><div id="TravelText_1" class="lineRowText"><table style="width: 100%;" id="container_1_1" style="background:rgb(255, 243, 217);"><tr><th width="6%">标题：</th><td><input type="text" class="formTitle" id="activity_title_1_1"
                                           name="activity_title_1_1"></td></tr><tr><th>内容：</th><td><textarea class="linePlan-text" id="activity_text_1_1" name="activity_text_1_1">第1天第1段内容....</textarea></td></tr></table></div></div></div></div></table><table width="100%" border="0" cellpadding="0" cellspacing="0" id="b_line_3" style="display: none" class="table_form"><tr><th width="10%"><em>特色介绍</em>：</th><td><textarea style="width:100%;height:350px" name="special_info" id="special_info" class="kind-text" datatype="*25-65535" errmsg="请输入25到65535个字符之间的特色介绍"></textarea></td><td style="width:150px;"><div class='Validform_checktip'></div></td></tr><tr><th><em>预定需知</em>：</th><td><textarea name="order_info" style="width:100%" id="order_info" class="kind-text" datatype="*25-65535" errmsg="请输入25到65535个字符之间的预定需知"></textarea></td><td><div class='Validform_checktip'></div></td></tr><tr><th><em>温馨提示</em>：</th><td><textarea name="tip" style="width:100%" id="L_Reminder" class="kind-text" datatype="*25-65535" errmsg="请输入25到65535个字符之间的温馨提示"></textarea></td><td><div class='Validform_checktip'></div></td></tr></table><div id="btnbox" class="btn"><INPUT TYPE="submit" value=" 保 存 " class="button"><input TYPE="reset" value=" 重 置 " class="button"></div></form><script type="text/javascript" src="../Public/js/jquery.linePlan.js"></script><script type="text/javascript">    $(function () {
        $('#box_SEO').trigger("click").click(function () {
            if ($(this).attr("checked") == "checked") {
                $('tr[name="seo"]').show();
            } else {
                $('tr[name="seo"]').hide();
            }
        });
        $(".showline").bind("click", function () {
            var i = $(".showline").index($(this)) + 1;
            for (var j = 1; j <= 3; j++) {
                if (i == j) {
                    $("#line_" + j).addClass("yes");
                    $("#b_line_" + j).show();
                } else {
                    $("#line_" + j).removeClass("yes");
                    $("#b_line_" + j).hide();
                }
            }
        });
        $("#city_id,#target_type").bind("change",function () {

            var city_id = $("#city_id").val();
            var target_type = $("#target_type").val();
            var reurl = "<?php echo U('ajax_target');?>";

            var data={city_id:city_id,target_type:target_type};
            $.ajax({
                type: "POST",
                url: reurl,
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                },
                data:data,
                dataType: "text",
                success: function (html) {

                    try {
                        var obj = jQuery.parseJSON(html);
                    } catch (e) {
                        $("#Line_target").html('<div class="Validform_checktip Validform_wrong" style="display: block;">目的地获取异常</div>');
                    }
                    if (obj.status == 1) {
                        $("#Line_target").html(obj.data);
                    } else {
                        $("#Line_target").html('<div class="Validform_checktip Validform_wrong" style="display: block;">无目的地记录</div>');
                    }
                    $("#city_id").trigger("blur");
                }
            });
        }).trigger("change");
        $("#trip_days").linePlan({data: ""});
    })

</script></div></body></html>