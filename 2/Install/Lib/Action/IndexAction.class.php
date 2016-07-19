<?php

class IndexAction extends Action {

    private $stiedit = "";
    private $sqlFile = array("TripEC.sql", "base.sql", "beta.sql");
    private $addadmin = "";
    private $importfile = array();

    public function _initialize() {
        $this->stiedit = $this->stiedit ? $this->stiedit : realpath('../');
        $this->importfile = explode("|", $_GET["importfile"]);
        $this->addadmin = file_get_contents(_INSTALL_PATH_ . "/SqlFile/adminuser.sql");
    }

    public function delete_install() {
        $installlock = $this->stiedit . "/BIN.LOCK";
        @unlink($installlock);
        $this->success("删除成功！");
    }

    public function index() {
        $step = isset($_GET["step"]) ? intval($_GET["step"]) : 1;
        $installlock = $this->stiedit . "/BIN.LOCK";
        if (is_file($installlock)) {
            header("Content-Type:text/html; charset=utf-8");
            echo "你已经安装过该系统，如果想重新安装，请先删除站点根目录下的 BIN.LOCK文件，然后再安装。<a  href=\"" . U("delete_install") . "\">立即删除并重新安装</a>";
            exit;
        }
        if ($step >= 5) {
            $this->step_5();
        } elseif ($step > 1) {
            $fn = "step_" . $step;
            $this->$fn();
        } else {
            $this->display();
        }
    }

    public function step_2() {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            die('本系统需要PHP5.3.0+MYSQL >=5.0环境，当前PHP版本为：' . phpversion());
        }
        $assign['err'] = 0;
        $assign["phpv"] = PHP_VERSION;
        $assign['install'] = version_compare(PHP_VERSION, '5.3.0', '<');
        if ($assign['install']) {
            $assign['err'] = 1;
            $assign["phpv"].="<font color=red>[×]</font>请升级您的php版本，最低要求5.3.0版本";
        } else {
            $assign["phpv"].="<font color=green>[√]</font>";
        }

        if (function_exists("curl_init")) {
            $assign["curl"] = "<font color=green>[√]On</font>";
        } else {
            $assign["curl"] = "<font color=red>[×]Off</font>";
            $assign['err'] ++;
        }
        if (function_exists("fsockopen")) {
            $assign["fsockopen"] = "<font color=green>[√]On</font>";
        } else {
            $assign["fsockopen"] = "<font color=red>[×]Off</font>";
            $assign['err'] ++;
        }
        if (function_exists("mail")) {
            $assign["mail"] = "<font color=green>[√]On</font>";
        } else {
            $assign["mail"] = "<font color=red>[×]Off</font>";
            $assign['err'] ++;
        }

        $assign["os"] = PHP_OS;
        $assign["os"] = php_uname();
        $assign["tmp"] = function_exists('gd_info') ? gd_info() : array();
        $assign["server"] = $_SERVER["SERVER_SOFTWARE"];
        $assign["host"] = (empty($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_HOST"] : $_SERVER["SERVER_ADDR"]);
        $assign["name"] = $_SERVER["SERVER_NAME"];
        $assign["max_execution_time"] = ini_get('max_execution_time');
        $assign["allow_reference"] = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $assign["allow_url_fopen"] = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $assign["safe_mode"] = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');
        $assign["stiedit"] = $this->stiedit;



        if (empty($assign["tmp"]['GD Version'])) {
            $assign["gd"] = '<font color=red>[×]Off</font>';
            $assign['err'] ++;
        } else {
            $assign["gd"] = '<font color=green>[√]On</font> ' . $assign["tmp"]['GD Version'];
        }
        if (function_exists('mysql_connect')) {
            $assign["mysql"] = '<font color=green>[√]On</font>';
        } else {
            $assign["mysql"] = '<font color=red>[×]Off</font>';
            $assign['err'] ++;
        }
        if (ini_get('file_uploads')) {
            $assign["uploadSize"] = '<font color=green>[√]On</font> 文件限制:' . ini_get('upload_max_filesize');
        } else {
            $assign["uploadSize"] = '禁止上传';
        }
        if (function_exists('session_start')) {
            $assign["session"] = '<font color=green>[√]On</font>';
        } else {
            $assign["session"] = '<font color=red>[×]Off</font>';
            $assign['err'] ++;
        }
        $assign["folder"] = array('/', 'Upload', 'config', 'Cache');
        $assign["readwrite"] = $this->getreadwrite($assign["folder"], $assign['err']);
        $this->assign($assign);
        $this->display("step_2");
    }

    public function step_3() {
        if ($_POST && isset($_GET['testdbpwd']) && $_GET['testdbpwd'] == 1) {
            $conn = @mysql_connect($_POST["dbHost"] . ":" . $_POST["dbPort"], $_POST["dbUser"], $_POST["dbPwd"]);
            if ($conn) {
                $err = @mysql_select_db($_POST["dbName"], $conn);
            }
            header("Content-Type:text/html; charset=utf-8");
            if ($conn && $err) {
                echo "1";
            } else {
                echo "";
            }
        } else {
            $this->display("step_3");
        }
    }

    public function step_4() {
        $n = isset($_GET['n']) ? intval($_GET['n']) : -1;
        $arr = array();
        $dbHost = trim($_POST['dbHost']);
        $dbPort = trim($_POST['dbPort']);
        $dbName = trim($_POST['dbName']);
        $dbUser = trim($_POST['dbUser']);
        $dbPwd = trim($_POST['dbPwd']);
        $url_mode = trim($_POST['url_mode']);
        $dbPrefix = empty($_POST['dbPrefix']) ? 'jee_' : trim($_POST['dbPrefix']);
        $username = trim($_POST['username']);
        $password = md5(trim($_POST['password']));
        $site_name = addslashes(trim($_POST['site_name']));
        $site_url = trim($_POST['site_url']);
        $site_email = trim($_POST['site_email']);
        $seo_description = trim($_POST['seo_description']);
        $seo_keywords = trim($_POST['seo_keywords']);
        $conn = @ mysql_connect($dbHost . ":" . $dbPort, $dbUser, $dbPwd);
        if (!$conn) {
            $arr["importfile"] = join("|", $this->importfile);
            $arr['msg'] = "连接数据库失败!";
        }
        if (!$arr) {
            mysql_query("SET NAMES 'utf8'");
            $version = mysql_get_server_info($conn);
        }
        if (!$arr && $version < 4.1) {
            $arr["importfile"] = join("|", $this->importfile);
            $arr['msg'] = '数据库版本太低!';
        }
        if (!$arr && !mysql_select_db($dbName, $conn)) {
            if (!mysql_query("CREATE DATABASE IF NOT EXISTS `" . $dbName . "`;", $conn)) {
                $arr['msg'] = '数据库 ' . $dbName . ' 不存在，也没权限创建新的数据库！';
            } else {
                $arr['n'] = 0;
                $arr["importfile"] = join("|", $this->importfile);
                $arr['msg'] = "成功创建数据库:{$dbName}<br>";
            }
        }
        if (!$arr && $n != -1) {
            foreach ($this->sqlFile as $v) {
                if (!in_array($v, $this->importfile)) {
                    $this->sql_execute($v, $n, $dbPrefix, $conn);
                    $arr['n'] = $n;
                    $arr["importfile"] = join("|", $this->importfile) . "|$v";
                    $arr['msg'] = "<font color=\"gree\">导入{$v}成功</font><br>";
                    echo json_encode($arr);
                    exit;
                }
            }
        }
        if (!$arr && $n != -1) {
            $result = mysql_query("SELECT count(*) as count FROM {$dbPrefix}admin_user");
            $row = @mysql_fetch_assoc($result);
            if ($row && $row["count"] > 0) {
                $insert_user = true;
            }
            $time = date("Y-m-d H:i:s");

            $parttern = array("{{dbPrefix}}", "{{username}}", "{{password}}", "{{time}}");
            $replace = array($dbPrefix, $username, $password, $time);
            $query1 = str_replace($parttern, $replace, $this->addadmin);
            if (!$insert_user) {
                $reslut = mysql_query($query1);
                if ($reslut) {
                    $arr['n'] = $n;
                    $arr["importfile"] = join("|", $this->importfile);
                    $arr['msg'] = "<font color=\"gree\">管理员帐号添加成功</font><br>";
                } else {
                    $arr['msg'] = "添加管理员失败！$query1" . $this->addadmin;
                }
                echo json_encode($arr);
                exit;
            }
        }
        if ($n != -1 && !$arr) {
            $configs = glob($this->stiedit . "/install/config/*.php");
            $con_str = array("#DB_HOST#", "#DB_NAME#", "#DB_USER#", "#DB_PWD#", "#DB_PORT#", "#DB_PREFIX#", "#Web_name#", "#keywords#", "#description#", "#site_url#", "#url_mode#");
            $re_con_str = array($dbHost, $dbName, $dbUser, $dbPwd, $dbPort, $dbPrefix, $site_name, $seo_keywords, $seo_description, $site_url, $url_mode);
            foreach ($configs as $k => $v) {
                $configinfo = pathinfo($v);
                $configname = $configinfo['basename'];
                if (in_array($configname, $this->importfile)) {
                    continue;
                }
                if ($k == (count($configs) - 1)) {
                    $n = 999999;
                }
                $configstr = file_get_contents($v);
                $configstring = str_replace($con_str, $re_con_str, $configstr);
                if ($configname == "web.php") {
                    file_put_contents($this->stiedit . "/web/Conf/config.php", $configstring);
                } elseif ($configname == "admin.php") {
                    file_put_contents($this->stiedit . "/admin/Conf/config.php", $configstring);
                } else {
                    file_put_contents($this->stiedit . "/config/{$configname}", $configstring);
                }
                $arr["n"] = $n;
                $arr["importfile"] = join("|", $this->importfile) . "|$configname";
                $arr['msg'] = "<font color=\"gree\">配置文件{$configname}设置成功</font><br>";
                break;
            }
        }
        if ($n != -1 && $arr) {
            echo json_encode($arr);
            exit;
        }
        $this->assign("arr", $arr);
        $this->display("step_4");
    }

    public function step_5() {
        $h = fopen($this->stiedit . "/BIN.LOCK", "w");
        fclose($h);
        $this->assign("url", "../admin/index.php");
        $this->display("step_5");
    }

    public function sql_execute($v, $n, $dbPrefix, $conn) {
        $sqlpath = $this->stiedit . "/install/SqlFile/" . $v;
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM)( DEFAULT CHARSET=[^; ]+)?/", "TYPE=\\1 DEFAULT CHARSET=utf8", file_get_contents($sqlpath));
        if ($dbPrefix != "jee_") {
            $sql = str_replace("jee_", $dbPrefix, $sql);
        }
        $sqls = str_replace("\r", "\n", $sql);
        $queriesarray = explode(";\n", trim($sqls));
        unset($sql);
        unset($sqls);
        $i = 0;
        foreach ($queriesarray as $query) {
            $str1 = substr(trim($query), 0, 1);
            if ($str1 != '#' && $str1 != '-' && $query != "") {
                if (strstr($query, 'CREATE TABLE')) {
                    if ($n == $i) {
                        preg_match('/CREATE TABLE `([^ ]*)`/', $query, $matches);
                        mysql_query("DROP TABLE IF EXISTS `$matches[1]", $conn);
                        $exec = mysql_query(trim($query, ";"), $conn);
                        if ($exec) {

                            $arr = array("n" => $n + 1, "msg" => "<font color=\"gree\">成功创建数据表{$matches[1]}成功</font><br />");
                        } else {
                            $arr = array("msg" => "<font  color=\"red\">创建数据表{$matches[1]}失败</font><br />");
                        }
                        $arr["importfile"] = join("|", $this->importfile);
                        echo json_encode($arr);
                        exit;
                    }
                    $i++;
                } elseif (strstr($query, 'INSERT INTO')) {
                    //如果记录已存在则跳过
                    if ($this->record_exist($query, $conn,$dbPrefix))
                        continue;
                    $exec = mysql_query(trim($query, ";"), $conn);
                    if (!$exec) {
                        $arr = array("msg" => "执行{$query}失败");
                        $arr["importfile"] = join("|", $this->importfile);
                        echo json_encode($arr);
                        exit;
                    }
                }
            }
        }
    }

    public function record_exist($query, $conn,$dbPrefix) {
        $keys=array(
            $dbPrefix."payment_api"=>"api_id",
        );
        preg_match('/INSERT\s+INTO\s+(`[a-zA-Z0-9_]+`)\s+VALUES\s*\(\'?(\d+)\'?[\s\S]+/', $query, $match);
        if (!$match)
            return false;
        $key=isset($keys[trim($match[1],"`")])?$keys[trim($match[1],"`")]:"id";
        $result = mysql_query("SELECT count(*) as count FROM {$match[1]} WHERE {$key}={$match[2]}",$conn);
        $row = @mysql_fetch_assoc($result);
        if ($row && $row["count"] > 0) {
            return true;
        }
        return false;
    }

    public function getreadwrite($folder, &$err = 0) {
        foreach ($folder as $dir) {
            $Testdir = $this->stiedit . "/" . $dir;
            if (TestWrite($Testdir)) {
                $w = '<font color=green>[√]写</font>';
            } else {
                $w = '<font color=red>[×]写</font>';
                $err++;
            }
            if (is_readable($Testdir)) {
                $r = '<font color=green>[√]读</font>';
            } else {
                $r = '<font color=red>[×]读</font>';
                $err++;
            }
            $str.="<tr><td>$dir</td><td>读写</td><td>{$r} {$w}</td></tr>";
        }
        return $str;
    }

}

function testwrite($d) {
    $tfile = "_test.txt";
    $fp = @fopen($d . "/" . $tfile, "w");
    if (!$fp) {
        return false;
    }
    fclose($fp);
    $rs = @unlink($d . "/" . $tfile);
    if ($rs) {
        return true;
    }
    return false;
}

if (!function_exists("get_client_ip")) {

    function get_client_ip() {
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
        return $ip;
    }

}