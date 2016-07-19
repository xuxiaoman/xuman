<?php
import("ORG.Util.Page");
import("ORG.Net.UploadFile");
import("@.ORG.file");

class hotelroomtypeAction extends CommonAction {

    //添加
    public function add() {
        if (!isset($_POST['save'])) {
            $Hotel = M('Hotel');
            $hotel_id = $_GET['hotel_id'];
            $objHotel = $Hotel->where("id=" . $hotel_id)->find();
            $this->assign('hotel_id', $hotel_id);
            $this->assign('objHotel', $objHotel);
            $this->display();
        } else {
            $hotel_id = $_POST['hotel_id'];
            $HotelRoomType = M('HotelRoomType');
            $HotelRoomType->create();
            $HotelRoomType->hotel_id = $_POST['hotel_id'];
            $HotelRoomType->names = $_POST['names'];
            $HotelRoomType->bed_type = $_POST['bed_type'];
            $HotelRoomType->deal_type = $_POST['deal_type'];
            $HotelRoomType->front_money = $_POST['front_money'];
            $HotelRoomType->bonus_comm = $_POST['bonus_comm'];
            $HotelRoomType->price_retail = $_POST['price_retail'];
            $HotelRoomType->price_prefer = $_POST['price_prefer'];
            $HotelRoomType->price_special = 0; /* $_POST['price_special'];*/
            $HotelRoomType->price_bonus_part = $_POST['price_bonus_part'];
            $HotelRoomType->date_limit_begin = strtotime($_POST['date_limit_begin']);
            $HotelRoomType->date_limit_end = strtotime($_POST['date_limit_end']);
            $HotelRoomType->price_5 = $_POST['price_5'];
            $HotelRoomType->price_6 = $_POST['price_6'];
            $HotelRoomType->price_7 = $_POST['price_7'];
            $HotelRoomType->breakfast = $_POST['breakfast'];
            $HotelRoomType->broadband = $_POST['broadband'];
            $HotelRoomType->area = $_POST['area'];
            $HotelRoomType->floor = $_POST['floor'];
            $HotelRoomType->window = $_POST['window'];
            $HotelRoomType->bathroom = $_POST['bathroom'];
            $HotelRoomType->extra_bed_price = $_POST['extra_bed_price'];
            $HotelRoomType->status = $_POST['status'];
            $HotelRoomType->sort = $_POST['sort'];
            $HotelRoomType->logopath = $_POST['pic_front'];
            $HotelRoomType->room_count = $_POST['room_count'];

            $HotelRoomType->add();
            $this->success("添加成功", "./show_list/?hotel_id=" . $hotel_id);
        }
    }

    //编辑
    public function edit() {
        if (!isset($_POST['save'])) {
            $Hotel = M('Hotel');
            $HotelRoom = M('HotelRoomType');
            $hotel_id = $_GET['hotel_id'];
            $id = $_GET['id'];
            $objHotel = $Hotel->where("id=" . $hotel_id)->find();
            $room = $HotelRoom->where("id=" . $id)->find();
            $this->assign('hotel_id', $hotel_id);
            $this->assign('objHotel', $objHotel);
            $this->assign('room', $room);
            $this->display();
        } else {
            $hotel_id = $_POST['hotel_id'];
            $HotelRoomType = M('HotelRoomType');
            $data["hotel_id"] = $_POST['hotel_id'];
            $data["names"] = $_POST['names'];
            $data["bed_type"] = $_POST['bed_type'];
            $data["deal_type"] = $_POST['deal_type'];
            $data["front_money"] = $_POST['front_money'];
            $data["bonus_comm"] = $_POST['bonus_comm'];
            $data["price_retail"] = $_POST['price_retail'];
            $data["price_prefer"] = $_POST['price_prefer'];
            $data["price_special"] = 0; /* $_POST['price_special'];*/
            $data["price_bonus_part"] = $_POST['price_bonus_part'];
            $data["date_limit_begin"] = strtotime($_POST['date_limit_begin']);
            $data["date_limit_end"] = strtotime($_POST['date_limit_end']);
            $data["price_5"] = $_POST['price_5'];
            $data["price_6"] = $_POST['price_6'];
            $data["price_7"] = $_POST['price_7'];
            $data["breakfast"] = $_POST['breakfast'];
            $data["broadband"] = $_POST['broadband'];
            $data["area"] = $_POST['area'];
            $data["floor"] = $_POST['floor'];
            $data["window"] = $_POST['window'];
            $data["bathroom"] = $_POST['bathroom'];
				$data['room_count'] = $_POST['room_count'];
            $data["extra_bed_price"] = $_POST['extra_bed_price'];
            $data["status"] = $_POST['status'];
            $data["sort"] = $_POST['sort'];
            if($_POST['pic_front']!="") {
                $data["logopath"] = $_POST['pic_front'];
                $logopath = $HotelRoomType->where("id=".$_POST['id'])->getField("logopath");
                @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $logopath);
            }

            $HotelRoomType->where("id=".$_POST['id'])->save($data);
            $this->success("编辑成功", "./show_list/?hotel_id=" . $hotel_id);
        }
    }

    //编辑
    public function ajax_save() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $names = $_POST['names'];
        $sort = $_POST['sort'];
        $HotelRoomType = M('HotelRoomType');
        $HotelRoomType->create();
        $HotelRoomType->id = $id;
        $HotelRoomType->names = $names;
        $HotelRoomType->sort = $sort;
        $HotelRoomType->save();
        echo 1;
        exit;
    }

    //删除    
    public function ajax_del() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $HotelRoomType = M('HotelRoomType');
        $logopath = $HotelRoomType->where("id=$id")->getField("logopath");
        $HotelRoomType->where("id=" . $id)->delete();

        @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $logopath);
        echo 1;
        exit;
    }

    //全部列表
    public function show_list() {
        $HotelRoomType = M("HotelRoomType");
        $hotel_id = $_GET['hotel_id'];
        $map['hotel_id'] = array("eq", $hotel_id);
        $count = $HotelRoomType->where($map)->count();
        $page = $this->pagebar($count);
        $list = $HotelRoomType->where($map)->page($page)->order("sort,id desc")->select();


        $Hotel = M('Hotel');
        $map = "id=" . $hotel_id;
        $objHotel = $Hotel->where($map)->find();
        $this->assign('objHotel', $objHotel);
        $this->assign('hotel_id', $hotel_id);
        $this->assign('list', $list);
        $this->display();
    }

    //点击了列表下方的删除按钮之后
    public function deleteall() {
        $hotel_id = $_GET['hotel_id'];
        if (isset($_POST['dosubmit'])) {
            $done = false;
            $Hotel = M("HotelRoomType");
            $count = $Hotel->count();
            $id = $Hotel->getField("id", true);
            for ($i = 0; $i < $count; $i++) {
                if ($_POST["items_" . $id[$i]]) {
                    $logopath = $Hotel->where("id=$id[$i]")->getField("logopath");

                    @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $logopath);
                    $Hotel->where("id=" . $id[$i])->delete();
                    $done = true;
                }
            }
            if ($done)
                $this->success("删除成功！");
            else
                $this->error("请勾选至少1项。");
        }
        else {
            $this->redirect("show_list", "hotel_id=" . $hotel_id);
        }
    }

    public function sort_list() {
        $HotelRoomType = M("HotelRoomType");
        $hotel_id = $_GET['hotel_id'];
        foreach ($_POST["sort"] as $key => $val) {
            $HotelRoomType->where("id={$key}")->setField("sort", $val);
        }
        $this->redirect("show_list", "hotel_id=" . $hotel_id);
    }

    public function date_check() {
        $bt = strtotime($_POST['date_begin']);
        $et = strtotime($_POST['date_end']);
        if ($bt > $et)
            echo 0;
        else
            echo 1;
        exit;
    }

}
?>