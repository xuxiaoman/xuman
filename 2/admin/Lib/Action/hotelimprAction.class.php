<?php

import("ORG.Util.Page");

class hotelimprAction extends CommonAction {

	public function show_list() {
		$impr_list = M('HotelImpr');
		$hotel = M('hotel');
		$user = M('User');
		$pages = ($_GET['p'] == null ? '1' : $_GET['p']);
		$count = $impr_list->table($impr_list->getTableName() . " impr")
				 ->join(M("HotelRoomType")->getTableName() . " room ON impr.room_id = room.id")
				 ->field("*, impr.hotel_id as hotel_id, impr.id as id, room.bonus_comm as room_bonus_comm, impr.status as impr_status")
				 ->count();
		$page = $this->pagebar($count);
		$imprs = $impr_list->table($impr_list->getTableName() . " impr")
				 ->join(M("HotelRoomType")->getTableName() . " room ON impr.room_id = room.id")
				 ->field("*, impr.hotel_id as hotel_id, impr.id as id, room.bonus_comm as room_bonus_comm, impr.status as impr_status")
				 ->page($page)
				 ->select();

		foreach ($imprs as $p => $i) {
			$hotel_name = $hotel->where("id=" . $i["hotel_id"])->getField("names");
			$user_name = $user->where("id=" . $i["user_id"])->getField("username");
			$imprs[$p]["hotel_name"] = $hotel_name;
			$imprs[$p]["user_name"] = $user_name;

			$average = (floatval($i["decoration"]) +
					 floatval($i["traffic"]) +
					 floatval($i["hygiene"]) +
					 floatval($i["prices"])) / 4;
			if ($average >= 80 && $average <= 100)
				$imprs[$p]["score"] = "好评";
			elseif ($average >= 65 && $average < 80)
				$imprs[$p]["score"] = "良评";
			elseif ($average >= 50 && $average < 65)
				$imprs[$p]["score"] = "中评";
			elseif ($average < 50)
				$imprs[$p]["score"] = "差评";
		}

		//获取配置
		$setting = conf("HOTEL_IListCount");

		$this->assign('p', $pages);
		$this->assign("list_count", $setting);
		$this->assign("list", $imprs);
		$this->display();
	}

	public function sort_list() {
		$impr_list = M('HotelImpr');
		$pages = ($_GET['p'] == null ? '1' : $_GET['p']);
		$list = $impr_list->page($pages . ',15')->order("sort")->select();

		/* 将新排序写入数据库 */
		$sort_list = $list;
		for ($i = 0; $i < count($list); $i++) {
			for ($k = count($list) - 2; $k >= $i; $k--) {
				if ($list[$k + 1]['id'] < $list[$k]['id']) {
					$tmp = $list[$k + 1];
					$list[$k + 1] = $list[$k];
					$list[$k] = $tmp;
				}
			}
		}foreach ($list as $k => $i) {
			$data[$k]["user_id"] = $list[$k]["user_id"] = $sort_list[$k]["user_id"];
			$data[$k]["hotel_id"] = $list[$k]["hotel_id"] = $sort_list[$k]["hotel_id"];
			$data[$k]["impr_type"] = $list[$k]["impr_type"] = $sort_list[$k]["impr_type"];
			$data[$k]["decoration"] = $list[$k]["decoration"] = $sort_list[$k]["decoration"];
			$data[$k]["traffic"] = $list[$k]["traffic"] = $sort_list[$k]["traffic"];
			$data[$k]["hygiene"] = $list[$k]["hygiene"] = $sort_list[$k]["hygiene"];
			$data[$k]["prices"] = $list[$k]["prices"] = $sort_list[$k]["prices"];
			$data[$k]["content"] = $list[$k]["content"] = $sort_list[$k]["content"];
			$data[$k]["publish_time"] = $list[$k]["publish_time"] = $sort_list[$k]["publish_time"];
			$data[$k]["sort"] = $list[$k]["sort"] = $sort_list[$k]["sort"];
			$data[$k]["status"] = $list[$k]["status"] = $sort_list[$k]["status"];
			$impr_list->where("id=" . $list[$k]["id"])->save($data[$k]);
		}

		$this->redirect("show_list", array("p" => $pages));
	}

	public function update() {
		$id = $_GET['id'];
		$this->assign('id', $id);
		$impr_list = M('HotelImpr');

		if (!isset($_POST['save'])) {
			$hotel = M('hotel');
			$impr_type = M('CommImpr');
			$impr = $impr_list->where("id=" . $id)->find();
			$impr_types = explode(",", $impr['impr_type']);
			$hotel_name = $hotel->where("id=" . $impr["hotel_id"])->getField("names");
			$impr_list = $impr_type->where("types='HOTEL'")->select();
			$impr['hotel_name'] = $hotel_name;

			$this->assign("hotel", $impr);
			$this->assign("imprs", $impr_list);
			$this->assign("impr_type", $impr_types);

			$this->display();
		} else {
			$data = $impr_list->create();
			$data['impr_type'] = join(",", $_POST['impr_type']);
			$data['sort'] = ($data['sort'] == "") ? 0 : $data['sort'];
			$data['publish_time'] = date("Y-m-d H:i:s");
			$impr_list->where("id=" . $id)->save($data);
			$this->success("修改成功！");
		}
	}

	//删除    
	public function ajax_del() {
		header('Content-Type:text/html;charset=utf-8');
		$id = $_GET['id'];
		$impr_list = M('HotelImpr');
		$impr_list->where("id=$id")->delete();
		echo 1;
		exit;
	}

	//点击了列表下方的删除按钮之后
	public function deleteall() {
		if (isset($_POST['dosubmit'])) {
			$done = false;
			$Hotel = M("HotelImpr");
			$count = $Hotel->count();
			$id = $Hotel->getField("id", true);
			for ($i = 0; $i < $count; $i++) {
				if ($_POST["items_" . $id[$i]]) {
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
			$this->redirect("show_list");
		}
	}

	//酒店点评 - 设置点评奖金
	public function award() {
		$id = $_GET['id'];
		$imprs = M('HotelImpr');
		$imprinfo = $imprs->where("id=$id")->getField("id, room_id, bonus_comm");
		foreach ($imprinfo as $info)
			$imprinfo = $info;

		$bonus_comm = M("HotelRoomType")->where("id={$info['room_id']}")->getField("bonus_comm");
		$imprinfo["bonus_comm"] = $bonus_comm;

		$this->assign("info", $imprinfo);
		$this->display();
	}

	//酒店点评 - 完成审核
	public function check() {
		$id = $_POST['impr_id'];
		$impr_list = M('HotelImpr');
		$impr_list->where("id=$id")->save(array('status' => 2, 'bonus_comm' => $_POST['bonus_comm']));

		// 改订单状态为“已结束”
		$order = $impr_list->where("id=$id")->find();
		M("HotelOrder")->where("id={$order['order_id']}")->save(array("status" => 5));
		// 开始发奖金
		$money = $_POST['bonus_comm'];
		M("User")->where("id={$order["user_id"]}")->setInc("award", $money);

		$this->success("审核成功", "show_list");
	}

	//Edit By YZeng
	public function set_ListCount() {
		if (isset($_POST["data"])) {
			$this->ajaxReturn(conf("HOTEL_IListCount", $_POST["data"]));
		}
		exit;
	}

}
