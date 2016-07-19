<?php

class hotelforareaAction extends CommonAction {

	//Edit By YZeng
	public function lists() {
		$pages = ($_GET['p'] == null ? '1' : $_GET['p']);

		$count = M()->table(M("CityBelong")->getTableName() . " cityb")
				 ->join(M("Area")->getTableName() . " area ON cityb.cid = area.id")
				 ->where("cityb.types = 'HOTEL'")
				 ->count();
		$page = $this->pagebar($count);

		$area = M()->table(M("CityBelong")->getTableName() . " cityb")
				 ->join(M("Area")->getTableName() . " area ON cityb.cid = area.id")
				 ->where("cityb.types = 'HOTEL'")
				 ->field("cityb.cid as id, area.names, cityb.isshow, (SELECT COUNT(*) FROM `" .
						  M("Hotel")->getTableName() . "` hotel WHERE `city_id` = cityb.cid AND hotel.status = 0) as hotelcount")
				 ->page($page)
				 ->order("cityb.sort asc")
				 ->select();

		//每个城市类别中显示的最多酒店条数
		$list_count = conf("HOTEL_CHListCount");
		//每个城市类别中显示的最多城市条数
		$city_count = conf("HOTEL_CListCount");

		$this->assign('p', $pages);
		$this->assign("list", $area);
		$this->assign('list_count', $list_count);
		$this->assign('city_count', $city_count);
		$this->display();
	}

	//Edit By YZeng
	public function showornot() {
		if (isset($_POST["cid"])) {
			$cityb = M("CityBelong");
			if ($_POST["isshow"] == 1 && $cityb->where("isshow = 1 AND types = 'HOTEL'")->count() >= conf("HOTEL_CListCount")) {
				$this->ajaxReturn(false);
				exit;
			} else {
				$cityb->where("cid={$_POST["cid"]}")->save(array("isshow" => $_POST["isshow"]));
				$this->ajaxReturn(true);
				exit;
			}
		}
		$this->ajaxReturn(false);
		exit;
	}

	//Edit By YZeng
	public function set_ListCount() {
		if (isset($_POST["data"])) {
			$cityb = M("CityBelong");
			conf($_POST["type"], $_POST["data"]);
			$act_max = $cityb->where("isshow = 1 AND types = 'HOTEL'")->count();
			$max = conf("HOTEL_CListCount");
			if ($cityb->where("isshow = 1 AND types = 'HOTEL'")->count() > $max) {
				$cityb->where("isshow = 1 AND types = 'HOTEL'")
						 ->limit(($act_max - $max))
						 ->save(array("isshow" => 0));
				$this->ajaxReturn("reload");
			}
			$this->ajaxReturn(true);
		}
		$this->ajaxReturn(false);
		exit;
	}

}
