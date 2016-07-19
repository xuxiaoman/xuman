<?php

/**
 * Description of loginAction
 *
 * @author Gemini
 */
class testAction extends CommonAction {

    public function _initialize() {
        
    }

    public function index() {
        $this->debug($a = 1, $b = 2);
    }

    public function debug() {
        dump(microtime());
    }

    public function forms() {
        $data['OrderId'] = "201402081425551245675";
        $data['MerchantID'] = "20140208";
        $data['amount'] = "0.01";
        $data['apiName'] = "wangyin";
        $data['AutoReceive'] = "http://demo_v1.tripec.cn/index.php/test/return_test";
        $data['Receive'] = "http://demo_v1.tripec.cn/index.php/test/return_test";
        $data['bank'] = "1025";
        $data['remark1'] = "测试";
        $data['remark2'] = "[url:=http://demo_v1.tripec.cn/index.php/paymentapi/autoreceive/paymentname/wangyin]";
        header("Content-Type:text/html; charset=utf-8");
        echo "<form action=\"" . U("yaml") . "\" method=\"post\" target=\"_blank\"\>";
        foreach ($data as $form => $input) {
            echo "<label>$form :</label><input type=\"text\" name=\"$form\" value=\"$input\"><br>";
        }
        echo "<input type=\"submit\" value=\"生成表单\"><br>";
        echo "</form>";
    }

    public function test_cache() {
        echo $this->cache("aaaa","bbbbb",3600);
    }

    public function post_test() {
        if (!$_POST) {
            $this->redirect("forms");
            exit;
        }
        $key = "aabbccddeeffgg";
        $data = $_POST;
        $data['md5info'] = strtoupper(md5($data['MerchantID'] . $data['OrderId'] . $data['amount'] . $data['apiName'] . $key));

        header("Content-Type:text/html; charset=utf-8");
        echo "<form action=\"http://demo_v1.tripec.cn/index.php/paymentapi/manage\" method=\"post\" target=\"_blank\"\>";
        foreach ($data as $form => $input) {
            echo "<label>$form :</label><input type=\"text\" name=\"$form\" value=\"$input\"><br>";
        }
        echo "<input type=\"submit\" value=\"测试支付\"><br>";
        echo "</form>";
    }

    public function return_test() {
        echo "ok";
    }

    public function return_post() {
        $key = "test";
        $data['v_oid'] = "20140212-22883787-20140212173915742581";
        $data['v_pstatus'] = "20";
        $data['v_pstring'] = "支付完成";
        $data['v_pmode'] = "1025";
        $data['v_amount'] = "77.60";
        $data['v_moneytype'] = "CNY";
        $data['remark1'] = "测试";
        $data['remark2'] = "测试2";
        $data['v_md5str'] = strtoupper(md5($data['v_oid'] . $data['v_pstatus'] . $data['v_amount'] . $data['v_moneytype'] . $key));
        header("Content-Type:text/html; charset=utf-8");
        echo "<form action=\"http://demo_v1.tripec.cn/index.php/paymentapi/autoreceive/paymentname/wangyin\" method=\"post\" target=\"_blank\"\>";
        foreach ($data as $form => $input) {
            echo "<label>$form :</label><input type=\"text\" name=\"$form\" value=\"$input\"><br>";
        }
        echo "<input type=\"submit\" value=\"测试支付\"><br>";
        echo "</form>";
    }

    public function yaml() {
        dump($argv);
        dump($http_response_header);
        dump($GLOBALS);
        dump($_ENV);
        dump($_POST);
        dump(file_get_contents("php://input"));
    }
    
    public function test_a(){
        $this->setv("1") or $this->setv("2");
    }
    public function setv($v){
        dump($v);
        return true;
    }

}

?>
