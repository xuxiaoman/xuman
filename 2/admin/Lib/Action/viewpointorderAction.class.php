<?php

class viewpointorderAction extends CommonAction {

    public function show_list() {
        $viewOrder = M('viewpointOrder');
        $user_table = M('user')->getTableName() . " user";
        $order_table = $viewOrder->getTableName() . " view_order";
        $view_table = M('viewpoint')->getTableName() . " view";
        $ticket_table = M('viewpoint_ticket')->getTableName() . " ticket";

        $list = $viewOrder->table($order_table)
                ->field("*,view_order.status o_status,view_order.id o_id,view_order.amount o_amount")
                ->join("$user_table on user.id=view_order.user_id")
                ->join("$ticket_table on ticket.id=view_order.ticket_id")
                ->select();
        //echo $viewOrder->getLastSql();exit;
        foreach ($list as $k => $v) {
            $list[$k]['total_money'] = $v['o_amount'] - $v['used_award'] - $v['used_card'];
        };
        $this->assign("list", $list);
        $this->display();
    }

    public function select_win() {
        $id = $_GET['id'];
        $viewOrder = M('viewpointOrder');
        $user_table = M('user')->getTableName() . " user";
        $order_table = $viewOrder->getTableName() . " view_order";
        $impr_table = M('viewpoint_impr')->getTableName() . " impr";
        $view_table = M('viewpoint')->getTableName() . " view";
        $ticket_table = M('viewpoint_ticket')->getTableName() . " ticket";
        $contactor_table = M('ticket_contactor')->getTableName() . " contactor";
        $order_userinfo = M('order_userinfo');
        $list = $viewOrder->table($order_table)
                ->field("impr.award,view_order.serial_num,ticket.names names,view_order.use_award,view_order.status o_status,view_order.id o_id,view_order.amount o_amount,contactor.name contactor_name,contactor.email contactor_email,contactor.cellphone")
                ->join("$user_table on user.id=view_order.user_id")
                ->join("$ticket_table on ticket.id=view_order.ticket_id")
                ->join("$impr_table on impr.order_id=view_order.id")
                ->join("$contactor_table on contactor.id=view_order.contactor_id")
                ->where("view_order.id='$id'")
                ->find();
        //dump($list);
        //echo $viewOrder->getLastSql();exit;
        $order_userinfo = M('order_userinfo');
        $order_userinfolist = $order_userinfo->where("order_id='$id' and type='Viewpoint'")->select();
        $list['total_money'] = $list['o_amount'];
        $this->assign("list", $list);
        $this->assign("order_userinfolist",$order_userinfolist);
        $this->display();
    }
    
    public function edit_win() {
        if(!$_POST){
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
        if(!in_array($list['o_status'],array('1','2')) ){
            $this->error("订单状态不可编辑!");
        }
        $order_userinfo = M('order_userinfo');
        $order_userinfolist = $order_userinfo->where("order_id='$id' and type='LINE'")->select();
        $list['total_money'] = $list['o_amount'];
        $this->assign("list", $list);
        $this->assign("order_userinfolist",$order_userinfolist);
        $this->display();
        }else{
            $id = $_GET['id'];
            $lineOrder = M('lineOrder');
            if($data = $lineOrder->create()){
                $lineOrder->where("id='$id'")->save();
                $this->success("编辑成功！",U('show_list'));
            }else{
                $this->error("编辑失败！");
            }
        }
    }

    //处理状态
    public function set_status(){
        $id = $_GET['id'];
        $lineOrder = M('lineOrder');
        $orderinfo = $lineOrder->where("id='$id'")->find();
        if($orderinfo['status'] == 1){
            $orderinfo = $lineOrder->where("id='$id'")->setField('status','2');
            $this->success("订单处理成功！");
        }elseif($orderinfo['status'] == 3){
            $orderinfo = $lineOrder->where("id='$id'")->setField('status','4');
            $this->success("订单处理成功！");
        }elseif($orderinfo['status'] == 5){
            $orderinfo = $lineOrder->where("id='$id'")->setField('status','6');
            $this->success("订单处理成功！");
        }else{
            $this->error("订单状态错误！");
        }
    }
}

?>