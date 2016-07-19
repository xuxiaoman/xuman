<?php

class lineorderAction extends CommonAction {

    public function show_list() {
        $lineOrder = M('lineOrder');
        $user_table = M('user')->getTableName() . " users";
        $order_table = $lineOrder->getTableName() . " line_order";
        $line_table = M('line')->getTableName() . " line";
        $where = array();
        if (!empty($_GET['names'])) {
            $where["line_order.code"] = array("like", "%{$_GET['names']}%");
            $where['line.names'] = array("like", "%{$_GET['names']}%");
            $where['_logic'] = 'OR';
            $this->assign("search_key", $_GET['names']);
        }else{
            $where='1=1';
        }
        $count=$lineOrder->table($order_table)
                ->where($where)
                ->field("*,line_order.`status` o_status,line_order.code o_code,line_order.id o_id,line_order.amount o_amount")
                ->join("$user_table on users.id=line_order.user_id")
                ->join("$line_table on line.id=line_order.line_id")
                ->count();
        $page=  $this->pagebar($count);
        $list = $lineOrder->table($order_table)
                ->where($where)
                ->field("*,line_order.`status` o_status,line_order.code o_code,line_order.id o_id,line_order.amount o_amount")
                ->join("$user_table on users.id=line_order.user_id")
                ->join("$line_table on line.id=line_order.line_id")
                ->order("o_id desc")
                ->page($page)
                ->select();
        
        $this->assign("list", $list);
        $this->display();
    }

    public function select_win() {
        $id = $_GET['id'];
        $lineOrder = M('lineOrder');
        $user_table = M('user')->getTableName() . " user";
        $order_table = $lineOrder->getTableName() . " line_order";
        $line_table = M('line')->getTableName() . " line";
        $order_userinfo = M('order_userinfo');
        $list = $lineOrder->table($order_table)
                ->field("*,line_order.status o_status,line_order.id o_id,line_order.front_money o_front_money,line_order.amount o_amount")
                ->join("$user_table on user.id=line_order.user_id")
                ->join("$line_table on line.id=line_order.line_id")
                ->where("line_order.id='$id'")
                ->find();
        $order_userinfo = M('order_userinfo');
        $order_userinfolist = $order_userinfo->where("order_id='$id' and type='LINE'")->select();
        $list['total_money'] = $list['o_amount'];
        $this->assign("list", $list);
        $this->assign("order_userinfolist", $order_userinfolist);
        $this->display();
    }

    public function edit_win() {
        if (!$_POST) {
            $id = $_GET['id'];
            $lineOrder = M('lineOrder');
            $user_table = M('user')->getTableName() . " user";
            $order_table = $lineOrder->getTableName() . " line_order";
            $line_table = M('line')->getTableName() . " line";
            $order_userinfo = M('order_userinfo');
            $list = $lineOrder->table($order_table)
                    ->field("*,line_order.status o_status,line_order.id o_id,line_order.front_money o_front_money,line_order.amount o_amount")
                    ->join("$user_table on user.id=line_order.user_id")
                    ->join("$line_table on line.id=line_order.line_id")
                    ->where("line_order.id='$id'")
                    ->find();
            if (!in_array($list['o_status'], array('1', '2'))) {
                $this->error("订单状态不可编辑!");
            }
            $order_userinfo = M('order_userinfo');
            $order_userinfolist = $order_userinfo->where("order_id='$id' and type='LINE'")->select();
            $list['total_money'] = $list['o_amount'];
            $this->assign("list", $list);
            $this->assign("order_userinfolist", $order_userinfolist);
            $this->display();
        } else {
            $id = $_GET['id'];
            $lineOrder = M('lineOrder');
            if ($data = $lineOrder->create()) {
                $lineOrder->where("id='$id'")->save();
                $this->success("编辑成功！", U('show_list'));
            } else {
                $this->error("编辑失败！");
            }
        }
    }

    //处理状态
    public function set_status() {
        $id = $_GET['id'];
        $lineOrder = M('lineOrder');
        $orderinfo = $lineOrder->where("id='$id'")->find();
        if ($orderinfo['status'] == 1) {
            $orderinfo = $lineOrder->where("id='$id'")->setField('status', '2');
            $this->success("订单处理成功！");
        } elseif ($orderinfo['status'] == 3) {
            $orderinfo = $lineOrder->where("id='$id'")->setField('status', '4');
            $this->success("订单处理成功！");
        } elseif ($orderinfo['status'] == 5) {
            $orderinfo = $lineOrder->where("id='$id'")->setField('status', '6');
            $this->success("订单处理成功！");
        } else {
            $this->error("订单状态错误！");
        }
    }

}

?>