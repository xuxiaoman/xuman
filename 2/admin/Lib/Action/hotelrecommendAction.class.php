<?php

class hotelrecommendAction extends CommonAction {

	//Edit By YZeng
	public function lists() {
		$rec = M("Recommend");
		$hotel = M("Hotel");
		if (isset($_GET["title"])) {
			$pages = ($_GET['p'] == null ? '1' : $_GET['p']);
			$count = $rec->table($hotel->getTableName() . " hotel")
					 ->join($rec->getTableName() . " recom On hotel.id = recom.obj_id")
					 ->where("recom.type='HOTEL' AND {$_GET["catid"]}='{$_GET["title"]}' AND hotel.status=0")
					 ->count();

			//推荐列表
			$page = $this->pagebar($count);
			$recom_list = $rec->table($hotel->getTableName() . " hotel")
					 ->join($rec->getTableName() . " recom On hotel.id = recom.obj_id")
					 ->where("recom.type='HOTEL' AND {$_GET["catid"]} like '%{$_GET["title"]}%' AND hotel.status=0")
					 ->page($page)
					 ->order("{$_GET['order']} {$_GET['sort']}")
					 ->select();
		} else {
			$pages = ($_GET['p'] == null ? '1' : $_GET['p']);
			$count = $rec->table($hotel->getTableName() . " hotel")
					 ->join($rec->getTableName() . " recom On hotel.id = recom.obj_id")
					 ->where("recom.type='HOTEL' AND hotel.status=0")
					 ->count();

			//推荐列表
			$page = $this->pagebar($count);
			$recom_list = $rec->table($hotel->getTableName() . " hotel")
					 ->join($rec->getTableName() . " recom On hotel.id = recom.obj_id")
					 ->where("recom.type='HOTEL' AND hotel.status=0")
					 ->page($page)
					 ->order("recom.sort asc")
					 ->select();
		}

		//获取配置
		$setting = conf("HOTEL_RListCount");

		$this->assign('p', $pages);
		$this->assign("list_count", $setting);
		$this->assign("list", $recom_list);
		$this->display();
	}

	//Edit By YZeng
	public function add() {
		$rec = M("Recommend");
		if (isset($_POST["save"])) {
			$_POST["pic"] = $_POST["pic_front"];
			$_POST["type"] = "HOTEL";
			$_POST["add_time"] = time();

			$rec->create();
			$rec->add();
			$this->success("添加成功", "lists");
		} else {
			$hotel = M("Hotel");
			$hotelinfo = M()->table($hotel->getTableName() . " hotel")
					 ->join($rec->getTableName() . " recom ON hotel.id = recom.obj_id")
					 ->where("hotel.id NOT IN(SELECT obj_id FROM " . $rec->getTableName() . " WHERE `type` = 'HOTEL') AND hotel.status=0")
					 ->field("hotel.names, hotel.id as hotel_id")
					 ->select();

			$this->assign("hotelinfo", $hotelinfo);
			$this->display();
		}
	}

	//Edit By YZeng
	public function ajax_save() {
		$rec = M("Recommend");
		$rec->where("id={$_GET['id']}")->save($_POST);
		$this->ajaxReturn(1);
	}

	//Edit By YZeng
	public function edit() {
		$id = ($_GET["id"]) ? $_GET["id"] : 0;
		$this->assign("id", $id);

		$hotel = M("Hotel");
		$rec = M("Recommend");

		if (!isset($_POST["save"])) {
			//当前酒店的信息
			$rec_info = M()->table($hotel->getTableName() . " hotel")
					 ->join($rec->getTableName() . " rec ON hotel.id = rec.obj_id")
					 ->where("rec.id=$id AND hotel.status=0")
					 ->find();

			//未加入推荐的酒店的信息
			$hotelinfo = $hotel->table($hotel->getTableName() . " hotel")
					 ->join($rec->getTableName() . " recom ON hotel.id = recom.obj_id")
					 ->where("hotel.id NOT IN(SELECT obj_id FROM " . $rec->getTableName() . ") AND hotel.status=0")
					 ->field("hotel.names, hotel.id as hotel_id")
					 ->select();

			$this->assign("hotels", $hotelinfo);
			$this->assign("info", $rec_info);
			$this->display();
		} else {
			$_POST["pic"] = $_POST["pic_front"];
			$_POST["type"] = "HOTEL";
			$_POST["add_time"] = time();
			//如果修改时图片发生改变，则应删除原图片，使用新图
			if ($_POST["pic_front2"] != $_POST["pic_front"]) {
				@unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $_POST["pic_front2"]);
			}
			$data = $rec->create();
			$rec->where("id=$id")->save($data);
			$this->success("修改成功");
		}
	}

	//Edit By YZeng
	public function ajax_del() {
		$rec = M("Recommend");
		$rec->where("id={$_GET['id']}")->delete();
		//删除相应的图片
		@unlink($_SERVER['DOCUMENT_ROOT'] . $_POST["picurl"]);
		$this->ajaxReturn(1);
	}

	//Edit By YZeng
	public function deleteall() {
		if (isset($_POST['dosubmit'])) {
			$done = false;
			$rec = M("Recommend");

			if (isset($_POST["items"])) {
				foreach ($_POST["items"] as $i => $r) {
					$rec->where("id=$r")->delete();
					//删除相应的图片
					@unlink($_SERVER['DOCUMENT_ROOT'] . $_POST["pic_url"]["$r"]);
					$done = true;
				}
			}

			if ($done)
				$this->success("删除成功！");
			else
				$this->error("请勾选至少1项。");
		}
		else {
			$this->redirect("lists");
		}
	}

	//Edit By YZeng
	public function set_ListCount() {
		if (isset($_POST["data"])) {
			$this->ajaxReturn(conf("HOTEL_RListCount", $_POST["data"]));
		}
		exit;
	}

	//Edit By YZeng
	public function sort_list() {
		$rec = M("Recommend");
		foreach ($_POST["sort"] as $key => $val) {
			$rec->where("id={$key}")->setField("sort", $val);
		}
		$this->redirect("lists");
	}

}
