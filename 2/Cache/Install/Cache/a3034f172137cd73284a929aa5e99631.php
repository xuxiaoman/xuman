<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>安装参数设置 - TripEC旅游电商网站 - 安装向导</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="../Public/Js/jquery.min.js" language="javascript" type="text/javascript"></script>
        <script src="../Public/Js/jquery.form.js" language="javascript" type="text/javascript"></script>
        <script src="../Public/Js/jquery.validate.js" language="javascript" type="text/javascript"></script>
        <link href="../Public/style.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript">
            <!--
                $(document).ready(function() {
                $('#dbPwd').blur();
                $(".table tr").each(function() {
                    $(this).children("td").eq(0).addClass("on");
                });
                $("input[type='text']").addClass("input_blur").mouseover(function() {
                    $(this).addClass("input_focus");
                }).mouseout(function() {
                    $(this).removeClass("input_focus");
                });
                $(".table tr").mouseover(function() {
                    $(this).addClass("mouseover");
                }).mouseout(function() {
                    $(this).removeClass("mouseover");
                });
            }); 
            $.ajaxSetup({cache: false});
            function TestDbPwd()
            {
                
                var dbHost = $('#dbHost').val();
                var dbUser = $('#dbUser').val();
                var dbPwd = $('#dbPwd').val();
                var dbName = $('#dbName').val();
                var dbPort = $('#dbPort').val();
                data = {'dbHost': dbHost, 'dbUser': dbUser, 'dbPwd': dbPwd, 'dbName': dbName, 'dbPort': dbPort};
                var url = "./index.php?step=3&testdbpwd=1";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    beforeSend: function() {				 
                    },
                    success: function(msg) {				
                        if (msg) {
                            $('#pwd_msg').html("数据库配置正确");
                            $('#dosubmit').attr("disabled", false);
                            $('#dosubmit').removeAttr("disabled");				
                            $('#dosubmit').removeClass("nonext");
                        } else {
                            $('#dosubmit').attr("disabled", true);
                            $('#dosubmit').addClass("nonext");
                            $('#pwd_msg').html("数据库链接配置失败");	
                        }
                    },
                    complete: function() {
                    },
                    error: function() {
                        $('#pwd_msg').html("数据库链接配置失败");						
                    }
                });
            }
            
            
            -->
        </script>


    </head>
    <body>

        <div class="main">
            <h5>感谢您选择旅游电商网站 - TripEC</h5>

            <div class="title"><ul>
                    <li  >安装许可协议</li>
                    <li  >运行环境检测</li>
                    <li  class="on" >安装参数设置</li>
                    <li  >安装详细过程</li>
                    <li  >安装完成</li>
                </ul></div>

            <div class="box"><div class="b1">


                    <div class="left">

                    </div>
                    <div class="right">
                        <h2>Step 3 of 5 </h2>

                        <h1>数据库设定</h1>

                        <form id="myform" action="index.php?step=4" method="post" name="form1" autocomplete="off" >
                            <table   border="0" align="center" cellpadding="0" cellspacing="0" class="table">


                                <!--<tr>
                            <td width="170"><strong>网站类型</strong></td>
                            <td width="410"><input type="radio" name="lang" value="1"  checked /> 多语网站 &nbsp;<input type="radio" name="lang" value="0" /> 单语网站 </td>
                        </tr>-->

                                <tr>
                                    <td width="170"><strong>数据库主机：</strong></td>
                                    <td width="410"><input name="dbHost" type="text" id="dbHost" value="localhost" size="30" />
                                        <small>(一般为localhost)</small></td>
                                </tr>
                                <tr>
                                    <td><strong>数据库端口：</strong></td>
                                    <td><input name="dbPort" type="text" id="dbPort" value="3306" size="10" /></td>
                                </tr>
                                <tr>
                                    <td><strong>数据库名称：</strong></td>
                                    <td>
                                        <input name="dbName" type="text" id="dbName" value="TripEC" size="20" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>数据库用户：</strong></td>
                                    <td><input name="dbUser" type="text" id="dbUser" value="root" size="20" /></td>
                                </tr>
                                <tr>
                                    <td><strong>数据库密码：</strong></td>
                                    <td>
                                        <input name="dbPwd" type="text" id="dbPwd" size="20"  onblur="TestDbPwd()" />
                                        <span id='pwd_msg'></span>            </td>
                                </tr>
                                <tr>
                                    <td><strong>数据表前缀：</strong></td>
                                    <td><input name="dbPrefix" type="text" id="dbPrefix" value="jee_" size="20" />
                                        <small>(如无特殊需要,请不要修改)</small></td>
                                </tr>
                            </table>

                            <h1>管理员帐号</h1>
                            <table   border="0" align="center" cellpadding="0" cellspacing="0" class="table">

                                <tr>
                                    <td width="170"><strong>用户名：</strong></td>
                                    <td width="410">
                                        <input name="username" type="text" autocomplete="off" id="username" value="admin" size="20"  validate=" required:true, minlength:5, maxlength:80,"  title="标题必填5-20个字"  />
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>密　码：</strong></td>
                                    <td><input name="password" type="password" autocomplete="off" style="padding:4px" id="password" value="" size="20"  validate=" required:true, minlength:5, maxlength:80,"  title="标题必填5-20个字"  /></td>
                                </tr>
                                <tr>
                                    <td><strong>URL模式：</strong></td>
                                    <td style="padding: 0; line-height: 20px;text-indent: 0">
                                        <label style="font-weight: 700; margin-left: 0; display: block"><input type="radio" name="url_mode" value="1" checked> PATHINFO默认</label>
                                        <p>地址示例: http://localhost/index.php/user/info </p>
                                        <label style="font-weight: 700; margin-left: 0; "><input type="radio" name="url_mode" value="0"> 普通，不支持PATHINFO的服务器请选择此方式 </label>
                                        <p>地址示例: http://localhost/index.php?s=user/info</p>
                                        <label style="font-weight: 700; margin-left: 0; "><input type="radio" name="url_mode" value="2"> 伪静态，开启了伪静态的服务器可以使用此方式</label>
                                        <p>地址示例: http://localhost/user/info </p>
                                        
                                       
                                    </td>
                                </tr>
                                <tr style="color: red"><td style="color: red">备注:</td>
                                    <td style="text-align: left;padding: 0;text-indent: 0;color: red">安装成功后若想改<strong>URL模式</strong>可到admin/Conf/config.php和web/Conf/config.php中修改URL_MODEL的值</td>
                                </tr>
                                
                            </table>




                            <table  border="0" align="center" cellpadding="0" cellspacing="0" class="table" style="display:none;">
                                <tr>
                                    <td width="170"><strong>网站名称：</strong></td>
                                    <td width="410">
                                        <input name="site_name" type="text" id="site_name" value="TripEC 旅游电商网站" size="30" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong> 网站域名：</strong></td>
                                    <td><input name="site_url" type="text" value="http://localhost/tripec" id="site_url" size="30" /></td>
                                </tr>
                                <tr>
                                    <td><strong>关键词(keywords)：</strong></td>
                                    <td>
                                        <input name="seo_keywords" type="text" id="seo_keywords" value="TripEC" size="30" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong> 描述(description)：</strong></td>
                                    <td><textarea id="seo_description" name="seo_description" rows="5" cols="60" size="30">TripEC 旅游电商网站</textarea></td>
                                </tr>
                                <tr>
                                    <td><strong>管理员邮箱：</strong></td>
                                    <td><input name="site_email" type="text" id="site_email" value="admin@tripec.com" size="30" /></td>
                                </tr>


                            </table>

                            <div class="butbox">
                                <input type="button" class="inputButton" value=" 上一步 " onclick="history.back();" style="margin-right:20px" />
                                <input name="dosubmit" type="submit" class="inputButton nonext" id="dosubmit" value=" 下一步 " disabled  />
                            </div>
                        </form>

                    </div>


                </div>
                <div class="power">Powered by <a href="">TripEC</a></div>
            </div></div>
    </body>
</html>