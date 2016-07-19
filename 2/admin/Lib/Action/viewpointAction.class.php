<?php

import("ORG.Util.Page");
import("ORG.Net.UploadFile");
import("@.ORG.file");

class viewpointAction extends CommonAction {

    //添加
    public function add() {
        if (!isset($_POST['submit'])) {

            //所属地区列表
            $city_belong = M('city_belong');
            $list_city_id = $city_belong->join(C('DB_PREFIX') . "area" . C('DB_SUFFIX') . " ON " . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".cid=" . C('DB_PREFIX') . "area" . C('DB_SUFFIX') . ".id")
                    ->where("types='Viewpoint'")
                    ->field("*," . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".sort as sort")
                    ->select();

            //景点类型列表
            $view_type = M('viewpoint_type');
            $list_view_type = $view_type->where("status=1")->select();

            //适宜人群型列表
            $fit_person = M('fit_person');
            $list_fit_person = $fit_person->where("status=1")->select();

            $this->assign('list_city_id', $list_city_id);
            $this->assign('list_view_type', $list_view_type);
            $this->assign('list_fit_person', $list_fit_person);

            $this->display();
        } else {
            $Viewpoint = M('Viewpoint');
            $Viewpoint->create();
            $Viewpoint->names = $_POST['names'];
            if ($_POST['SEO'] != '') {
                $seo_info = M('seo_info');
                $seo_info->create();
                $seo_info->title = $_POST['title'];
                $seo_info->keyword = $_POST['keyword'];
                $seo_info->detail = $_POST['detail'];
                $Viewpoint->seo_id = $seo_info->add();
            }

            if ($_POST['location_x'] != '' && $_POST['location_y'] != '') {
                $Viewpoint->position = "{$_POST['location_x']},{$_POST['location_y']}";
            }
            $Viewpoint->city_id = $_POST['city_id'];
            $Viewpoint->rank = $_POST['rank'];
            $Viewpoint->view_type = $_POST['view_type'];
            $Viewpoint->fit_person = join(",", $_POST['fit_person']);
            $Viewpoint->video = $_POST['video'];
            $Viewpoint->view_address = $_POST['view_address'];
            $Viewpoint->tickets_address = $_POST['tickets_address'];
            $Viewpoint->contact = $_POST['contact'];
            $Viewpoint->view_info = $_POST['view_info'];
            $Viewpoint->traffic_info = $_POST['traffic_info'];
            $Viewpoint->buy_info = $_POST['buy_info'];
            $Viewpoint->special_shopping = $_POST['special_shopping'];
            $special_food->special_food = $_POST['special_food'];
            $Viewpoint->sort = $_POST['sort'];
            $Viewpoint->status = $_POST['status'];
            $Viewpoint->add();
            $this->redirect("show_list");
        }
    }

    //编辑
    public function edit() {
        if (!isset($_POST['submit'])) {
            $id = $_GET['id'];
            $Viewpoint = M('Viewpoint');
            $map["id"] = array("eq", $id);
            $objViewpoint = $Viewpoint->where($map)->find();
            $seo_id = array("eq", $id);
            $seo_info = M('seo_info');
            $map["id"] = array("eq", $objViewpoint['seo_id']);
            $objseo_info = $seo_info->where($map)->find();

            //所属地区列表
            $city_belong = M('city_belong');
            $list_city_id = $city_belong->join(C('DB_PREFIX') . "area" . C('DB_SUFFIX') . " ON " . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".cid=" . C('DB_PREFIX') . "area" . C('DB_SUFFIX') . ".id")
                    ->where("types='Viewpoint'")
                    ->field("*," . C('DB_PREFIX') . "city_belong" . C('DB_SUFFIX') . ".sort as sort")
                    ->select();

            //景点类型列表
            $view_type = M('viewpoint_type');
            $list_view_type = $view_type->where("status=1")->select();
            //适宜人群型列表
            $fit_person = M('fit_person');
            $list_fit_person = $fit_person->where("status=1")->select();

            $objViewpoint['fit_person'] = explode(",", $objViewpoint['fit_person']);


            if ($objViewpoint['position'] != NULL) {
                $position = explode(",", $objViewpoint['position']);
                $objViewpoint['location_x'] = $position[0];
                $objViewpoint['location_y'] = $position[1];
                //dump($objViewpoint);
            }
            $this->assign('objViewpoint', $objViewpoint);
            $this->assign('objseo_info', $objseo_info);

            $this->assign("id", $id);
            $this->assign('list_city_id', $list_city_id);
            $this->assign('list_view_type', $list_view_type);
            $this->assign('list_fit_person', $list_fit_person);

            $this->display();
        } else {
            $Viewpoint = M('Viewpoint');
            $Viewpoint->create();
            $Viewpoint->id = $_POST['id'];
            $Viewpoint->names = $_POST['names'];
            if ($_POST['SEO'] != '' && $_POST['SEO'] != null) {
                $seo_info = M('SeoInfo');
                $seo_info->create();
                $seo_info->title = $_POST['title'];
                $seo_info->keyword = $_POST['keyword'];
                $seo_info->detail = $_POST['detail'];
                if ($_POST['seo_id'] > 0)
                    $seo_info->where("id=" . $_POST['seo_id'])->save();
                else
                    $Viewpoint->seo_id = $seo_info->add();
            }
            else {
                $seo_info = M('SeoInfo');
                $seo_info->where('id=' . $_POST['seo_id'])->delete();
                $Viewpoint->seo_id = 0;
            }
            if ($_POST['location_x'] != '' && $_POST['location_y'] != '') {
                $Viewpoint->position = "{$_POST['location_x']},{$_POST['location_y']}";
                //dump($Viewpoint->position);
            }
            $Viewpoint->city_id = $_POST['city_id'];
            $Viewpoint->rank = $_POST['rank'];
            $Viewpoint->view_type = $_POST['view_type'];
            $Viewpoint->fit_person = join(",", $_POST['fit_person']);
            $Viewpoint->video = $_POST['video'];
            $Viewpoint->view_address = $_POST['view_address'];
            $Viewpoint->tickets_address = $_POST['tickets_address'];
            $Viewpoint->contact = $_POST['contact'];
            $Viewpoint->view_info = $_POST['view_info'];
            $Viewpoint->traffic_info = $_POST['traffic_info'];
            $Viewpoint->buy_info = $_POST['buy_info'];
            $Viewpoint->special_shopping = $_POST['special_shopping'];
            $special_food->special_food = $_POST['special_food'];
            $Viewpoint->sort = $_POST['sort'];
            $Viewpoint->status = $_POST['status'];
            $Viewpoint->save();
            $this->redirect("show_list");
        }
    }

    //景点订单
    public function order_list() {
        $order = M("ViewpointOrder");
        $user = M("User");        
         if (!empty($_GET['title'])) {
             $where['ord.serial_num'] = array('like', "%{$_GET['title']}%");
            $this->assign("title", $_GET['title']);
        } else {
            $where = '1=1';
        }
        $count = M()->table($order->getTableName() . " ord")
                ->join($user->getTableName() . " user ON ord.user_id = user.id")
                ->where($where)               
                ->count();
        $page = $this->pagebar($count);
        $orders = M()->table($order->getTableName() . " ord")
                ->join($user->getTableName() . " user ON ord.user_id = user.id")
                ->where($where)
                ->field("*, ord.id as id, ord.status as order_status, ord.serial_num as order_num, ord.create_time as orderAddTime")
                ->order("ord.id desc")
                ->page($page)
                ->select();

        $this->assign("list", $orders);
        $this->display();
    }

    //查看订单详情
    public function view_showorder() {
        $Order = D('ViewpointOrder');

        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $order = $Order->get_order_by_id($_GET['id']);

            $order['total'] = $order['ticket_num'] * $order['price'];
            $order['earnest_money'] = $order['pay_money']; // 定金金额
            $order['remain_money'] = $order['total'] - $order['earnest_money'] -
                    ($order['coupon_value'] === null ? 0 : $order['coupon_value']) - $order['use_award'];

            $this->assign('order', $order);
            $this->display();
        }
    }

    //订单处理
    public function order_control() {
        $order = M("ViewpointOrder");
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

    //部分编辑
    public function ajax_save() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $names = $_POST['names'];
        $sort = $_POST['sort'];
        $Viewpoint = M('Viewpoint');
        $Viewpoint->create();
        $Viewpoint->id = $id;
        $Viewpoint->names = $names;
        $Viewpoint->sort = $sort;
        $Viewpoint->save();
        echo 1;
        exit;
    }

 
	public function del() {
	        $id = $_GET['id'];
	        $Line = M('Viewpoint');
	        $Line->where("id=$id")->delete();
	        $this->redirect("show_list");
	}
    //全部列表
    public function show_list() {
        $View = M("Viewpoint");
         if (!empty($_GET['title'])) {
            $where["names"] = array("like", "%{$_GET['title']}%");
            $this->assign("title", $_GET['title']);
        } else {
            $where = '1=1';
        }
        $count = $View->where($where)->count();
        $page = $this->pagebar($count);
         $list = $View->page($page)->where($where)
                        ->order('sort,id desc')
                        ->select(); 

        $this->assign('list', $list);
        $this->display();
    }

    //图片列表
    public function pic_list() {
        $ViewpointPic = M("ViewpointPic");
        $Viewpoint_id = $_GET["viewpoint_id"];
        $map["viewpoint_id"] = array("eq", $Viewpoint_id);
        $list = $ViewpointPic->where($map)->select();
        $this->assign('list', $list);
        $this->assign('viewpoint_id', $Viewpoint_id);
        $this->display();
    }

    //图片新增
 /*   public function pic_add() {
        if (!isset($_POST['save'])) {
            $Viewpoint_id = $_GET["viewpoint_id"];
            $Viewpoint = M("Viewpoint");
            $map["id"] = array("eq", $Viewpoint_id);
            $objViewpoint = $Viewpoint->where($map)->find();
            $this->assign('viewpoint_id', $Viewpoint_id);
            $this->assign('objViewpoint', $objViewpoint);
            $this->display();
        } else {
            $ViewpointPic = M('ViewpointPic');
            $ViewpointPic->create();
            $ViewpointPic->title = $_POST['names'];
            $ViewpointPic->viewpoint_id = $_POST['viewpoint_id'];

            $Viewpoint_id = $_POST["viewpoint_id"];
            $Viewpoint = M("Viewpoint");
            $map["viewpoint_id"] = array("eq", $Viewpoint_id);
            $map["istitlepage"] = array("eq", 1);
            if ($_POST['istitlepage'] == 1) {
                $objViewpoint = $ViewpointPic->where($map)->find();
                //echo $ViewpointPic->getLastSql();exit;
                if ($objViewpoint) {
                    $this->error("封面图片已存在");
                    exit;
                }
            }
            $ViewpointPic->istitlepage = $_POST['istitlepage'];
            $ViewpointPic->picpath = $_POST['pic_front'];
            $ViewpointPic->add();
            $this->success("保存成功");
        }
    }
*/
    //图片新增
    public function pic_add() {
        if (!isset($_POST['save'])) {
            $hotel_id = $_GET["viewpoint_id"];
            $Hotel = M("Viewpoint");
            $map["id"] = array("eq", $hotel_id);
            $objHotel = $Hotel->where($map)->find();
            $this->assign('viewpoint_id', $hotel_id);
            $this->assign('objViewpoint', $objHotel);
            $this->display();
        } else {
            $HotelPic = M('ViewpointPic');
            $HotelPic->create();
            $HotelPic->title = $_POST['names'];
            $HotelPic->hotel_id = $_POST['viewpoint_id'];
            $HotelPic->istitlepage = 0;
            $HotelPic->picpath = $_POST['pic_front'];
            $HotelPic->add();
            $this->success("添加成功");
        }
    }
    
    
    //图片编辑
    public function pic_edit() {
        if (!isset($_POST['save'])) {
            $id = $_GET['id'];
            $ViewpointPic = M("ViewpointPic");
            $map["id"] = array("eq", $id);
            $objViewpointPic = $ViewpointPic->where($map)->find();
            $this->assign('id', $id);
            $this->assign('objViewpointPic', $objViewpointPic);
            $this->display();
        } else {
            $id = $_POST['id'];
            $names = $_POST['names'];
            $ViewpointPic = M('ViewpointPic');
            $ViewpointPic->create();
            $ViewpointPic->id = $id;
            $ViewpointPic->title = $names;

            $map["id"] = array("eq", $id);
            $view_istitle = $ViewpointPic->where($map)->find();

            if ($view_istitle['istitlepage'] == 0) {

                $objViewpoint = $ViewpointPic->where("viewpoint_id = '" . $view_istitle["viewpoint_id"] . "' and istitlepage = 1")->find();
                //echo $ViewpointPic->getLastSql();exit;
                if ($objViewpoint) {
                    $this->error("封面图片已存在");
                    exit;
                }
            }

            $ViewpointPic->istitlepage = $_POST['istitlepage'];
            $ViewpointPic->save();
            $this->success("保存成功");
        }
    }

    //图片删除
    public function ajax_pic_del() {
        $id = $_GET['id'];
        $ViewpointPic = M('ViewpointPic');
        $picpath = $ViewpointPic->where("id=$id")->getField("picpath");

        $ViewpointPic->where("id=$id")->delete();

        @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $picpath);
        echo 1;
        exit;
    }

    //设置为封面
    public function ajax_cover() {
        $id = $_GET['id'];
        $ViewpointPic = M('ViewpointPic');
        $ViewpointPic->create();
        $ViewpointPic->istitlepage = 0;
        $map["istitlepage"] = array("eq", '1');
        $ViewpointPic->where($map)->save();

        $ViewpointPic->create();
        $ViewpointPic->id = $id;
        $ViewpointPic->istitlepage = 1;
        $ViewpointPic->save();
        echo 1;
        exit;
    }

    /**
     *   景点门票列表
     */
    public function tickets_list() {
        $Viewpoint = M("Viewpoint");
        $ViewpointTicket = M("ViewpointTicket");
        $viewpoint_id = $_GET['viewpoint_id'];  // 获取所属景点id
        $message = $_GET['message'];  // 获取返回消息
        $map['viewpoint_id'] = array('eq', $viewpoint_id);
        $count = $ViewpointTicket->count();
        $page = $this->pagebar($count);
        $ojbpoint = $Viewpoint->where('id=' . $viewpoint_id)->getField('names');
        $list = $ViewpointTicket->where($map)->order("sort,id desc")->page($page)->select();
        $this->assign('viewpoint_id', $viewpoint_id);
        $this->assign('names', $ojbpoint);
        $this->assign('list', $list);
        $this->assign('message', $message);
        $this->display();
    }

    /**
     *  添加门票 
     */
    public function tickets_add() {
        $ViewpointTicket = M("ViewpointTicket");
        $Viewpoint = M("Viewpoint");
        if (!isset($_POST['submit'])) {
            $viewpoint_id = $_GET['viewpoint_id'];  // 获取所属景点id
            $map['id'] = array('eq', $viewpoint_id);
            $names = $Viewpoint->where($map)->getField('names');
            $this->assign('viewpoint_id', $viewpoint_id);
            $this->assign('names', $names);
            $this->display();
        } else {
            $ViewpointTicket->create();
            $ViewpointTicket->addtime = strtotime(date('Y-m-d H:i:s'));
            $ViewpointTicket->add();
            $this->redirect('tickets_list', array('viewpoint_id' => $_POST['viewpoint_id'], 'message' => '添加成功！'));
        }
    }

    /**
     *  编辑门票 
     */
    public function tickets_edit() {
        $ViewpointTicket = M("ViewpointTicket");
        $Viewpoint = M("Viewpoint");
        if (!isset($_POST['submit'])) {
            $id = $_GET['id'];
            $map['id'] = array('eq', $id);
            $objpoint = $ViewpointTicket->where($map)->find();
            $maps['id'] = array('eq', $objpoint['viewpoint_id']);
            $names = $Viewpoint->where($maps)->getField('names');
            $this->assign('list', $objpoint);
            $this->assign('id', $id);
            $this->assign('viewpoint_id', $objpoint['viewpoint_id']);
            $this->assign('names', $names);
            $this->display();
        } else {
            $_POST["tatus"] = (isset($_POST["tatus"])) ? $_POST["tatus"] : 1;
            $ViewpointTicket->create();
            $ViewpointTicket->addtime = strtotime(date('Y-m-d H:i:s'));
            $ViewpointTicket->save();
            $this->redirect('tickets_list', array('viewpoint_id' => $_POST['viewpoint_id'], 'message' => '编辑成功！'));
        }
    }

    /**
     * 门票编辑（部分编辑）
     */
    public function ajax_tickets_save() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $ViewpointTicket = M("ViewpointTicket");
        $ViewpointTicket->create();
        $ViewpointTicket->id = $id;
        $ViewpointTicket->names = $_POST['names'];
        $ViewpointTicket->price = $_POST['price'];
        $ViewpointTicket->inner_price = $_POST['inner_price'];
        $ViewpointTicket->upon_price = $_POST['upon_price'];
        $ViewpointTicket->tatus = $_POST['tatus'];
        $ViewpointTicket->sort = $_POST['sort'];
        $ViewpointTicket->save();
        echo 1;
        exit;
    }

    /**
     *  删除门票 
     */
    public function ajax_tickets_del() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $ViewpointTicket = M("ViewpointTicket");
        $ViewpointTicket->where("id=$id")->delete();
        echo 1;
        exit;
    }

    //点击了列表下方的删除按钮之后
    public function deleteall() {
        if (isset($_POST['dosubmit'])) {
            $done = false;
            $view = M("Viewpoint");
            $view_pic = M("ViewpointPic");
            $count = $view->count();
            $id = $view->getField("id", true);
            for ($i = 0; $i < $count; $i++) {
                if ($_POST["items_" . $id[$i]]) {
                    $picpath = $view_pic->where("viewpoint_id=" . $id[$i])->getField("picpath");
                    $view->where("id=" . $id[$i])->delete();

                    @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $picpath);
                    $dt = new ViewpointDeleterModel($id[$i]); //关联表删除
                    $dt->deleteFromTable();
                    $done = true;
                }
            }
            if ($done)
                $this->success("删除成功！");
            else
                $this->error("请勾选至少1项。");
        }
        elseif (isset($_POST['dosubmit2'])) {
            $done = false;
            $view = M("ViewpointTicket");
            $count = $view->count();
            $id = $view->getField("id", true);
            for ($i = 0; $i < $count; $i++) {
                if ($_POST["items_" . $id[$i]]) {
                    $view->where("id=" . $id[$i])->delete();
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

    public function sort_list() {
        $View = M("Viewpoint");
        foreach ($_POST["sort"] as $key => $val) {
            $View->where("id={$key}")->setField("sort", $val);
        }
        $this->redirect("show_list");
    }

    public function tickets_sort_list() {
        $ViewpointTicket = M("ViewpointTicket");
        $viewpoint_id = $_GET['viewpoint_id'];  // 获取所属景点id
        foreach ($_POST["sort"] as $key => $val) {
            $ViewpointTicket->where("id={$key}")->setField("sort", $val);
        }
        $this->redirect("tickets_list", array("viewpoint_id" => $viewpoint_id));
    }

}

?>