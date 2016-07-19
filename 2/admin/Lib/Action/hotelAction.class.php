<?php

import("ORG.Util.Page");
import("ORG.Net.UploadFile");
import("@.ORG.file");

class hotelAction extends CommonAction {

    //添加
    public function add() {
        if (!isset($_POST['save'])) {
            //所属品牌列表
            $Hotel_chain = M('HotelChain');
            $list_hotel_chain = $Hotel_chain->where("status=1")->select();
            //酒店类型列表
            $Hotel_type = M('HotelType');
            $list_hotel_type = $Hotel_type->where("status=1")->Order('sort ASC')->select();
            //所属地区列表
            $city_belong = M('city_belong');
            $list_city_belong = $city_belong->join(C('DB_PREFIX') . "area" . C('DB_SUFFIX') . " ON " . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".cid=" . C('DB_PREFIX') . "area" . C('DB_SUFFIX') . ".id")
                    ->where("types='HOTEL'")
                    ->field("*," . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".sort as sort")
                    ->select();

            //支持卡类列表
            $Paycart = M('Paycart');
            $list_paycart = $Paycart->where("status=1")->select();
            //特殊服务列表
            $Special_service = M('Special_service');
            $list_special_service = $Special_service->where("status=1")->select();
            //服务项目列表
            $Service_item = M('Service_item');
            $list_service_item = $Service_item->where("status=1")->select();
            //餐饮服务列表
            $Service_dinner = M('Service_dinner');
            $list_service_dinner = $Service_dinner->where("status=1")->select();
            //休闲娱乐列表
            $Recreation = M('Recreation');
            $list_recreation = $Recreation->where("status=1")->select();
            //房间设施列表
            $Room_facilitie = M('Room_facilitie');
            $list_room_facilitie = $Room_facilitie->where("status=1")->select();

            //休闲娱乐列表
            $Recreation = M('Recreation');
            $list_recreation = $Recreation->where("status=1")->select();

            $area = M('area');
            $area_list = $area->select();

            $this->assign('area_list', $area_list);
            $this->assign('list_hotel_chain', $list_hotel_chain);
            $this->assign('list_hotel_type', $list_hotel_type);
            $this->assign('list_city_belong', $list_city_belong);
            $this->assign('list_paycart', $list_paycart);
            $this->assign('list_special_service', $list_special_service);
            $this->assign('list_service_item', $list_service_item);
            $this->assign('list_service_dinner', $list_service_dinner);
            $this->assign('list_recreation', $list_recreation);
            $this->assign('list_room_facilitie', $list_room_facilitie);
            $this->display();
        } else {
            $Hotel = M('Hotel');
            $Hotel->create();
            $Hotel->names = $_POST['names'];
            $Hotel->hotel_chain_id = $_POST['sel_hotel_chain'];
            $Hotel->hotel_type_id = $_POST['sel_hotel_type'];
            $Hotel->city_id = $_POST['sel_city_belong'];
            $Hotel->area_id = $_POST['area_id'];
            $Hotel->video = $_POST['video'];
            $Hotel->open_time = strtotime($_POST['open_time']);
            $Hotel->address = $_POST['address'];
            $Hotel->contact = $_POST['contact'];
            $Hotel->detail = $_POST['detail'];
            $Hotel->traffic_info = $_POST['traffic_info'];
            $Hotel->around_info = $_POST['around_info'];
            $Hotel->location = $_POST['location'];
            $Hotel->tip = $_POST['tip'];
            $Hotel->adds_service = $_POST['adds_service'];
            $Hotel->fix_time = ($_POST['fix_time'] != "" && $_POST['fix_time'] != null) ? strtotime($_POST['fix_time']) : 0;

            $Hotel->paycart = implode(',', $_POST['paycart']);
            $Hotel->special_service = implode(',', $_POST['special_service']);
            $Hotel->service_item = implode(',', $_POST['service_item']);
            $Hotel->service_dinner = implode(',', $_POST['service_dinner']);
            $Hotel->recreation = implode(',', $_POST['recreation']);
            $Hotel->room_facilitie = implode(',', $_POST['room_facilitie']);

            if ($_POST['location_x'] != '' && $_POST['location_y'] != '') {
                $Hotel->position = "{$_POST['location_x']},{$_POST['location_y']}";
            }

            $Hotel->status = $_POST['status'];
            $Hotel->sort = $_POST['sort'];
            $hotelid = $Hotel->add();

            $hb = M("HotelAreabelong");
            $_POST["city_id"] = $_POST['sel_city_belong'];
            $_POST["hotel_id"] = $hotelid;
            $hb->create();
            $hb->add();

            $this->success("保存成功！", "show_list");
        }
    }

    //编辑
    public function edit() {
        if (!isset($_POST['save'])) {
            $id = $_GET['id'];
            $Hotel = M('Hotel');
            $map["id"] = array("eq", $id);
            $objHotel = $Hotel->where($map)->find();
            $arry_paycart = explode(',', $objHotel['paycart']);
            $arry_special_service = explode(',', $objHotel['special_service']);
            $arry_service_item = explode(',', $objHotel['service_item']);
            $arry_service_dinner = explode(',', $objHotel['service_dinner']);
            $arry_recreation = explode(',', $objHotel['recreation']);
            $arry_room_facilitie = explode(',', $objHotel['room_facilitie']);

            //所属品牌列表
            $Hotel_chain = M('HotelChain');
            $list_hotel_chain = $Hotel_chain->where("status=1")->select();
            //酒店类型列表
            $Hotel_type = M('HotelType');
            $list_hotel_type = $Hotel_type->where("status=1")->select();
            //所属城市列表
            $city_belong = M('city_belong');
            $list_city_belong = $city_belong->join(C('DB_PREFIX') . "area" . C('DB_SUFFIX') . " ON " . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".cid=" . C('DB_PREFIX') . "area" . C('DB_SUFFIX') . ".id")
                    ->where("types='HOTEL'")
                    ->field("*," . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".sort as sort")
                    ->select();
            //所属地区列表
            $area = M('area');
            $area_list = $area->where('pid=' . $objHotel['city_id'])->select();

            //支持卡类列表
            $Paycart = M('Paycart');
            $list_paycart = $Paycart->where("status=1")->select();
            //特殊服务列表
            $Special_service = M('Special_service');
            $list_special_service = $Special_service->where("status=1")->select();
            //服务项目列表
            $Service_item = M('Service_item');
            $list_service_item = $Service_item->where("status=1")->select();
            //餐饮服务列表
            $Service_dinner = M('Service_dinner');
            $list_service_dinner = $Service_dinner->where("status=1")->select();
            //休闲娱乐列表
            $Recreation = M('Recreation');
            $list_recreation = $Recreation->where("status=1")->select();
            //房间设施列表
            $Room_facilitie = M('Room_facilitie');
            $list_room_facilitie = $Room_facilitie->where("status=1")->select();

            if ($objHotel['position'] != NULL) {
                $position = explode(",", $objHotel['position']);
                $objHotel['location_x'] = $position[0];
                $objHotel['location_y'] = $position[1];
                //dump($objHotel);
            }

            $this->assign('list_hotel_chain', $list_hotel_chain);
            $this->assign('list_hotel_type', $list_hotel_type);
            $this->assign('list_city_belong', $list_city_belong);
            $this->assign('area_list', $area_list);
            $this->assign('list_paycart', $list_paycart);
            $this->assign('list_special_service', $list_special_service);
            $this->assign('list_service_item', $list_service_item);
            $this->assign('list_service_dinner', $list_service_dinner);
            $this->assign('list_recreation', $list_recreation);
            $this->assign('list_room_facilitie', $list_room_facilitie);
            $this->assign('id', $id);
            $this->assign('objHotel', $objHotel);
            $this->assign('arry_paycart', $arry_paycart);
            $this->assign('arry_special_service', $arry_special_service);
            $this->assign('arry_service_item', $arry_service_item);
            $this->assign('arry_service_dinner', $arry_service_dinner);
            $this->assign('arry_recreation', $arry_recreation);
            $this->assign('arry_room_facilitie', $arry_room_facilitie);

            $hb = M("HotelAreabelong");
            $this->assign("selectid", $hb->where("hotel_id=$id")->find());

            $this->display();
        } else {
            $Hotel = M('Hotel');
            $Hotel->create();
            $Hotel->id = $_POST['id'];
            $Hotel->names = $_POST['names'];
            $Hotel->hotel_chain_id = $_POST['sel_hotel_chain'];
            $Hotel->hotel_type_id = $_POST['sel_hotel_type'];
            $Hotel->city_id = $_POST['sel_city_belong'];
            $Hotel->area_id = $_POST['area_id'];
            $Hotel->video = $_POST['video'];
            $Hotel->open_time = strtotime($_POST['open_time']);
            $Hotel->address = $_POST['address'];
            $Hotel->contact = $_POST['contact'];
            $Hotel->detail = $_POST['detail'];
            $Hotel->traffic_info = $_POST['traffic_info'];
            $Hotel->around_info = $_POST['around_info'];
            $Hotel->location = $_POST['location'];
            $Hotel->tip = $_POST['tip'];
            $Hotel->adds_service = $_POST['adds_service'];
            $Hotel->fix_time = ($_POST['fix_time'] != "" && $_POST['fix_time'] != null) ? strtotime($_POST['fix_time']) : 0;

            $Hotel->paycart = implode(',', $_POST['paycart']);
            $Hotel->special_service = implode(',', $_POST['special_service']);
            $Hotel->service_item = implode(',', $_POST['service_item']);
            $Hotel->service_dinner = implode(',', $_POST['service_dinner']);
            $Hotel->recreation = implode(',', $_POST['recreation']);
            $Hotel->room_facilitie = implode(',', $_POST['room_facilitie']);

            if ($_POST['location_x'] != '' && $_POST['location_y'] != '') {
                $Hotel->position = "{$_POST['location_x']},{$_POST['location_y']}";
            }

            $Hotel->status = $_POST['status'];
            $Hotel->sort = $_POST['sort'];
            $Hotel->save();

            $hb = M("HotelAreabelong");
            $_POST["city_id"] = $_POST['sel_city_belong'];
            $id = $_POST["id"];
            unset($_POST["id"]);
            $hb->create();
            $hb->where('hotel_id=' . $id)->save();

            $this->success("修改成功！", "show_list");
        }
    }

    //点击了列表下方的删除按钮之后
    public function deleteall() {
        if (isset($_POST['dosubmit'])) {
            $done = false;
            $Hotel = M("Hotel");
            $Hotelpic = M("HotelPic");
            $count = $Hotel->count();
            $id = $Hotel->getField("id", true);
            for ($i = 0; $i < $count; $i++) {
                if ($_POST["items_" . $id[$i]]) {
                    $picpath = $Hotelpic->where("hotel_id=" . $id[$i])->getField("picpath");
                    $Hotel->where("id=" . $id[$i])->delete();

                    @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $picpath);
                    $dt = new HotelDeleterModel($id[$i]); //关联表删除
                    $dt->deleteFromTable();
                    $done = true;
                }
            }
            if ($done)
                $this->success("删除成功！");
            else
                $this->error("请勾选至少1项。");
        }
        else {
            $this->redirect("show_list");
        }
    }

    //部分编辑
    public function ajax_save() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $names = $_POST['names'];
        $sort = $_POST['sort'];
        $Hotel = M('Hotel');
        $Hotel->create();
        $Hotel->id = $id;
        $Hotel->names = $names;
        $Hotel->sort = $sort;
        $Hotel->save();
        echo 1;
        exit;
    }

    //删除    
    public function ajax_del() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $Hotel = M('Hotel');
        $Hotelpic = M('HotelPic');
        $picpath = $Hotelpic->where("hotel_id=$id")->getField("picpath");
        $Hotel->where("id=$id")->delete();
        @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $picpath);

        $dt = new HotelDeleterModel($id); //关联表删除
        $dt->deleteFromTable();

        echo 1;
        exit;
    }

    //全部列表
    public function show_list() {
        $Hotel = M("Hotel");
        if (!empty($_GET['title'])) {
            $where['names'] = array('like', "%{$_GET['title']}%");
            $this->assign("title", $_GET['title']);
        } else {
            $where = "1=1";
        }
        $count = $Hotel->where($where)->count();
        $page = $this->pagebar($count);
        $list = $Hotel->page($page)->where($where)
                ->order('sort,id desc')
                ->select();


        $this->assign('list', $list);
        $this->display();
    }

    //图片列表
    public function pic_list() {
        $HotelPic = M("HotelPic");
        $hotel_id = $_GET["hotel_id"];
        $map["hotel_id"] = array("eq", $hotel_id);
        $list = $HotelPic->where($map)->select();
        $this->assign('list', $list);
        $this->assign('hotel_id', $hotel_id);
        $this->display();
    }

    //图片新增
    public function pic_add() {
        if (!isset($_POST['save'])) {
            $hotel_id = $_GET["hotel_id"];
            $Hotel = M("Hotel");
            $map["id"] = array("eq", $hotel_id);
            $objHotel = $Hotel->where($map)->find();
            $this->assign('hotel_id', $hotel_id);
            $this->assign('objHotel', $objHotel);
            $this->display();
        } else {
            $HotelPic = M('HotelPic');
            $HotelPic->create();
            $HotelPic->title = $_POST['names'];
            $HotelPic->hotel_id = $_POST['hotel_id'];
            $HotelPic->istitlepage = 0;
            $HotelPic->picpath = $_POST['pic_front'];
            $HotelPic->add();
            $this->success("添加成功", "pic_list?hotel_id={$_POST['hotel_id']}");
        }
    }

    //图片编辑
    public function pic_edit() {
        if (!isset($_POST['save'])) {
            $id = $_GET['id'];
            $HotelPic = M("HotelPic");
            $map["id"] = array("eq", $id);
            $objHotelPic = $HotelPic->where($map)->find();
            $this->assign('id', $id);
            $this->assign('objHotelPic', $objHotelPic);
            $this->display();
        } else {
            $id = $_POST['id'];
            $names = $_POST['names'];
            $HotelPic = M('HotelPic');
            $HotelPic->create();
            $HotelPic->id = $id;
            $HotelPic->title = $names;
            $HotelPic->save();
            $this->success("保存成功");
        }
    }

    //图片删除
    public function ajax_pic_del() {
        $id = $_GET['id'];
        $HotelPic = M('HotelPic');
        $picpath = $HotelPic->where("id=$id")->getField("picpath");

        $HotelPic->where("id=$id")->delete();

        @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $picpath);
        echo 1;
        exit;
    }

    //设置为封面
    public function ajax_cover() {
        $id = $_GET['id'];
        $HotelPic = M('HotelPic');
        $HotelPic->create();
        $HotelPic->istitlepage = 0;
        $map["istitlepage"] = array("eq", '1');
        $HotelPic->where($map)->save();

        $HotelPic->create();
        $HotelPic->id = $id;
        $HotelPic->istitlepage = 1;
        $HotelPic->save();
        echo 1;
        exit;
    }

    //酒店订单
    public function order_list() {
        $order = M("HotelOrder");
        //$order_user = M("OrderUserinfo");
        $user = M("User");
        
        if (!empty($_GET['title'])) {
            $where['ord.order_id'] = array('like', "%{$_GET['title']}%");
            $this->assign("title", $_GET['title']);
        } else {
            $where = "1=1";
        }
        $count = M()->table($order->getTableName() . " ord")
                ->join($user->getTableName() . " user ON ord.user_id = user.id")
                ->where($where)                
                ->count();
                
        $page = $this->pagebar($count);
        $orders = M()->table($order->getTableName() . " ord")
                ->join($user->getTableName() . " user ON ord.user_id = user.id")
                ->where($where)
                ->field("*, ord.id as id, ord.status as order_status, ord.order_id as order_num, ord.add_time as orderAddTime")
                ->page($page)
                ->order('ord.id desc')
                ->select();        
        $this->assign("list", $orders);
        $this->display();
    }

    //订单处理
    public function order_control() {
        $order = M("HotelOrder");
        $old_status = null; //status被更改前的status
        $info = $order->where("id={$_GET['id']}")->find();
        if (!empty($info)) {
            $old_status = $info["status"];
            $order->where("id={$_GET['id']}")->save(array("status" => $_GET["status"]));
        }

        //取消订单退钱
        if ($_GET["status"] == 6 && $old_status >= 2) {
            //要退的奖金
            $bonus_money = $info["bonus_money"];
            //要退的已付金额
            $pay_money = $info["pay_money"];
            //要退的代金券（编号）
            $serial_num = $info["serial_num"];
            //退钱给用户
            M()->query("UPDATE " . M("User")->getTableName() . " SET `award` = `award`+{$bonus_money}, `amount` = `amount`+{$pay_money} WHERE `id`={$info["user_id"]}");
            //退代金券
            M("CashCoupon")->where("serial_num='$serial_num'")->save(array("use_time" => null, "status" => 0, "use_userid" => null));
            $order->where("id={$_GET['id']}")->save(array("serial_money" => 0, "serial_num" => null));
        }
        if ($_GET["status"] == "auto") {
            $order->where("id={$_GET['id']}")->save(array("status" => $info["front_status"]));
        }

        $this->ajaxReturn(true);
    }

    //查看订单详情
    public function hotel_showorder() {
        $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
        //以订单id号查询订单，储存为数组 $order
        $user_order = M("HotelOrder");
        $order = $user_order->where("id= $id ")->find();
        //查询入住人信息且合并数据到 $order
        $order["users"] = M("OrderUserinfo")->where("order_id={$order['id']} AND type='HOTEL'")->select();
        //查询订单中的酒店和房间信息且合并数据到 $order
        $info = M("");
        $other_info = $info->table(M("HotelRoomType")->getTableName() . " room")
                ->join(M("Hotel")->getTableName() . " hotel ON room.hotel_id=hotel.id")
                ->where("room.id={$order['room_id']}")
                ->getField("room.bonus_comm,room.names as roomname,hotel.names as hotelname");
        foreach ($other_info as $o)
            $order = array_merge($o, $order);
        //查询代金券信息且合并数据到 $order
        $serial = M("CashCoupon")->where("serial_num='{$order["serial_num"]}'")->getField("serial_num,coupon_value,use_time");
        foreach ($serial as $o)
            $order = array_merge($o, $order);

        $this->assign("order", $order);
        $this->display();
    }

    //修改订单的应付价格
    public function update_all_money() {
        $order = M("HotelOrder");
        $order->where("id={$_POST['order_id']}")->save(array("all_money" => $_POST['all_money']));
        $this->ajaxReturn($order->where("id={$_POST['order_id']}")->getField("all_money"));
    }

    //根据选择的城市返回地区
    public function ajax_area() {
        $areas = D('Hotel');
        $list = $areas->areas($_POST['id']);
        $this->ajaxReturn($list);
        exit;
    }

    public function sort_list() {
        $Hotel = M("Hotel");
        foreach ($_POST["sort"] as $key => $val) {
            $Hotel->where("id={$key}")->setField("sort", $val);
        }
        $this->redirect("show_list");
    }

}
