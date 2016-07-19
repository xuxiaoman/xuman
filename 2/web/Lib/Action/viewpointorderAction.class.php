<?php

/**
 * Description of lineorderAction
 *
 * @author Genini
 */
class viewpointorderAction extends CommonAction {

    public function form() {
        $payment_merchant = M('payment_merchant');
        $user_bill = M('user_bill');
        $merchant = $payment_merchant->where("MerchantID='{$_POST['MerchantID']}' and status='1'")->find();

        $_POST['OrderId'] = $this->create_orderid();
        $_POST['apiName'] = $merchant['api_lists'];
        $_POST['AutoReceive'] = "http://demo_v1.tripec.cn/index.php/lineorder/back"; //C('paymentAPI_Path');
        $_POST['Receive'] = "http://demo_v1.tripec.cn/index.php/lineorder/front"; //C('paymentAPI_Path');
        $_POST['md5info'] = strtoupper(md5($_POST['MerchantID'] . $_POST['OrderId'] . $_POST['amount'] . $_POST['apiName'] . $merchant['MerchantKey']));

        $data['user_id'] = $_SESSION['user_id'];
        $data['code_id'] = $_POST['OrderId'];
        $data['money'] = $_POST['amount'];
        $data['balance'] = 0;
        $data['mark'] = 'LINE';
        $data['create_time'] = time();
        $user_bill->add($data);
        $this->assign("list", $_POST);
        $this->display();
    }

    public function create_orderid() {
        do {
            $code_id = date("YmdHis") . rand_string(3, 1);
        } while (M('user_bill')->where("code_id='$code_id'")->count());
        return $code_id;
    }

    public function front() {
        $user_bill = M('user_bill')->where("code_id='{$_POST['OrderId']}'")->find();
        if(empty($user_bill)){
            echo "找不到订单信息11111";
        }
        if ($_POST['status'] == '01') {
            echo "支付成功111111";
        } else {
            echo "支付失败000000";
        }
    }

    public function back() {
        $user_bill = M('user_bill')->where("code_id='{$_POST['OrderId']}'")->find();
        $key = M('payment_merchant')->where("MerchantID='{$_POST['MerchantID']}'")->getField("MerchantKEY");
        if(empty($user_bill)){
            echo "找不到订单信息11111";
        }
        $md5info = strtoupper(md5($_POST['MerchantID'].$_POST['OrderId'].$_POST['amount'].$_POST['apiName'].$_POST['status'].$key));
        if($md5info != $_POST['md5info']){
            echo "签名错误11111";
        }
        $list =  M('user_bill')->where("order_id='{$_POST['OrderId']}'")->find();
        M('user')->where("id='{$list['user_id']}'")->setInc('amount',$_POST['amount']);
    }

}

?>