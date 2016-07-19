<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>安装完成 - TripEC旅游电商网站 - 安装向导</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="../Public/Js/jquery.min.js" language="javascript" type="text/javascript"></script>
        <script src="../Public/Js/jquery.form.js" language="javascript" type="text/javascript"></script>
        <script src="../Public/Js/jquery.validate.js" language="javascript" type="text/javascript"></script>
        <link href="../Public/style.css" rel="stylesheet" type="text/css" />

    </head>
    <body>

        <div class="main">
            <h5>感谢您选择旅游电商网站 - TripEC</h5>

            <div class="title">
                <ul>
                    <li  >安装许可协议</li>
                    <li  >运行环境检测</li>
                    <li  >安装参数设置</li>
                    <li  >安装详细过程</li>
                    <li  class="on" >安装完成</li>
                </ul>
            </div>

            <div class="box">
                <div class="b1">
                    <div class="left">
                    </div>	<div class="right">
                        <h2>Step 5 of 5 </h2>
                        <h1><font color="red">恭喜您，安装成功！</font></h1>	 
                        <div id="setupOK"> 
                            <div class="nr">

                                <div class="ok1">
                                    <a href="<?php echo ($url); ?>" target="_blank" >进入后台管理</a> 
                                </div>					
                                <div class="ok2">
                                    <span>*</span>安装完毕请登录后台，更新缓存<br/>
                                    <span>*</span>默认TripEC管理员密码管理员密码相同<br/>
                                    <span>*</span>为了您站点的安全，安装完成后即可将网站根目录下的“Install”文件夹删除。</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
        <div class="power">Powered by <a href=""> TripEC </a></div>
         </div>
        </div>
    </body>
</html>