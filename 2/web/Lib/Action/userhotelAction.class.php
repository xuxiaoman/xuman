<?php

/**
 * Description of hotelAction
 *
 * @author Gemini
 */
class userhotelAction extends usercommonAction {

	public function _initialize() {
		parent::_initialize();
		$city_t = M("CityBelong")->getTableName() . " city";
		$area_t = M("Area")->getTableName() . " area";
		$hotel_city = M()->table($city_t)->join($area_t . " on city.cid=area.id")->where("city.types='HOTEL'")->select();
		$this->assign("hotel_city", $hotel_city);
	}

	public function index() {
		$this->show_hoteltype();
		$this->show_chains(); //品牌连锁
		$this->display();
	}

	public function lists() {
		$this->show_area(); //地区
		$this->show_hoteltype(); //酒店类型
		$this->show_chains(); //品牌连锁
		$this->show_special_service(); //特色服务（酒店设施）

		$this->assign("URLstr", json_encode($_GET));
		$map = array();
		$map["city_id"] = array('eq', $_GET['CityName']);
		if ($_GET['hotelName']) { //酒店名称
			$map['names'] = array('like', '%' . $_GET['hotelName'] . '%');
		}
		if ($_GET['area']) { //所属地区
			$map['area_id'] = array('eq', $_GET['area']);
		}
		if ($_GET['hotelStar']) { //酒店类型
			$map["hotel_type_id"] = array('eq', $_GET['hotelStar']);
		}
		if ($_GET['hotelchain']) {//品牌连锁
			$map["hotel_chain_id"] = array('eq', $_GET['hotelchain']);
		}
		if ($_GET['service']) {//酒店设施
			$service = explode(',', $_GET['service']);
		}
		if ($_GET['Hprice']) { //价格范围
			switch ($_GET['Hprice']) {
				case "lt200": $room_map["price_retail"] = array("lt", "200");
					break;
				case "200to300":$room_map["price_retail"] = array(array("egt", "200"), array('elt', '300'));
					break;
				case "300to500":$room_map["price_retail"] = array(array("egt", "300"), array('elt', '500'));
					break;
				case "gt500":$room_map["price_retail"] = array('lt', '500');
					break;
			}
		}
		if ($_GET['checkIn-time']) {//入住时间
		}
		if ($_GET['checkOut-time']) {//离开时间
		}
		$hotel = M("hotel");
		$hotel_room_type = M("hotel_room_type");
		$order = isset($_GET['order']) ? $_GET['order'] : 'status desc,sort';
		$map["status"] = array('neq', 2);
		$hotel_item = $hotel->where($map)->order($order)->select();
		if ($hotel_item) {
			$hotel_list = array();
			foreach ($hotel_item as $item) {
				if ($service) { //搜索是否符合酒店设施的数据 
					$service_item = explode(',', $hotel_item['special_service']);
					foreach ($service as $sv) {
						if (!in_array($sv, $service_item)) {
							$item = false;
							break;
						}
					}
				}
				if ($item) {
					$hotel_list[] = array(
						 'hotel' => $item,
						 'room' => $this->get_room($hotel_room_type, $item['id'], $room_map),
						 'pic' => $this->read_pic($item['id'])
					);
				}
			}
		}
		$this->assign('hotel_list', $hotel_list);
		$this->display();
	}

	/**
	 * 读取房型(未完成)
	 * @param type $hotel_room_type
	 * @param type $item_id
	 * @param type $intime
	 * @param type $outtime
	 * @param type $Hprice
	 * @return type
	 */
	public function get_room($hotel_room_type, $item_id, $room_map) {
		$map = array(
			 'hotel_id' => array('eq', $item_id)
		);
		if (isset($room_map['price_retail'])) {
			$map["price_retail"] = $room_map['price_retail'];
		}
		$result_item = $hotel_room_type->where($map)->select();
		$result = array();
		if ($result_item) {
			foreach ($result_item as $i) {
				$begin = strtotime($i["date_limit_begin"]);
				$end = strtotime($i["date_limit_end"]);
				//dump(strtotime($i["date_limit_begin"]));
			}
		}
		return $result_item;
	}

	public function read_pic($hotel_id) {
		$pic = M('HotelPic');
		return $pic->where('hotel_id=' . $hotel_id)->order('istitlepage desc')->find();
	}

	/**
	 * 读取酒店类型
	 */
	public function show_hoteltype() {
		$hoteltype = M("HotelType");
		$this->assign("hoteltype", $hoteltype->where("status=1")->order("sort")->select());
	}

	/**
	 * 读取地区列表
	 */
	public function show_area() {
		$city_t = M("CityBelong")->getTableName() . " city";
		$area_t = M("Area")->getTableName() . " area";
		$select_city = M()->table($city_t)->join($area_t . " on city.cid=area.id")
				 ->where("city.types='HOTEL' and city.cid=" . intval($_GET['CityName']))
				 ->find();
		if (!$select_city) {
			$select_city = M()->table($city_t)->join($area_t . " on city.cid=area.id")
					 ->where("city.types='HOTEL'")
					 ->order("city.isdefault desc, sort")
					 ->find();
		}
		if ($select_city) {
			$this->assign("arealist", M("Area")->where("pid=" . $select_city['cid'])->select());
		}
	}

	/**
	 * 读取品牌连锁
	 */
	public function show_chains() {
		$chains = M("hotel_chain");
		$this->assign("hotelchain", $chains->where("status=1")->order("sort")->select());
	}

	/**
	 * 酒店设施（特色服务）
	 */
	public function show_special_service() {
		$special_service = M("SpecialService");
		$result = $special_service->where("status=1")->order("id")->select();
		$this->assign("specialservice", $result);
	}

	public function hotel_impr() {
		C("TOKEN_ON", false);
		if (!isset($_POST['submit'])) {
			$cimpr = M("CommImpr");
			$cimprs = $cimpr->where("types='HOTEL'")->select();
			$colors = array("blue", "green", "pink", "orange", "red", "brown", "purple", "gray");
			$last_color = null;
			foreach ($cimprs as $k => $is) {
				$tmp_color = $colors[rand(0, 7)];
				while ($last_color == $tmp_color)
					$tmp_color = $colors[rand(0, 7)];
				$cimprs[$k]["color"] = $tmp_color;
				$last_color = $tmp_color;
			}

			//查询订单信息
			$info = M()->table(M("HotelRoomType")->getTableName() . " room")
					 ->join(M("HotelOrder")->getTableName() . " ord ON room.id = ord.room_id")
					 ->join("RIGHT JOIN " . M("Hotel")->getTableName() . " hotel ON room.hotel_id = hotel.id")
					 ->where("ord.order_id='{$_GET["order"]}'")
					 ->field("ord.order_id as order_id,"
							  . "hotel.names as hotel_name,"
							  . "hotel.id as hotel_id,"
							  . "room.names as room_name,"
							  . "room.id as room_id,"
							  . "ord.id as id")
					 ->find();

			$this->assign('imprs', $cimprs);
			$this->assign('info', $info);
			$this->display();
		} else {
			$himpr = M("HotelImpr");
			$_POST["user_id"] = $_SESSION["user_id"];
			$_POST["impr_type"] = join($_POST["impr_type"], ",");
			$_POST["publish_time"] = time();
			$_POST["sort"] = $_POST["status"] = 1;
			$data = $himpr->create();
			if ($himpr->where("order_id={$data["order_id"]}")->count() == 0) {
				$himpr->add();
				// 将订单设为“已点评”
				M("HotelOrder")->where("id={$data["order_id"]}")->save(array("status" => 4));
				$this->success("点评成功！请等待审核。", U("hotel_review", array("status" => 1)));
			} else {
				$this->error("点评失败！订单不能重复点评！");
			}
		}
	}

	/**
	 * 酒店点评列表
	 */
	public function hotel_review() {
		$order = M("HotelOrder");
		$hotel = M("Hotel");
		$room = M("HotelRoomType");
		$impr = M("HotelImpr");
		$commimpr = M("CommImpr");
		$status = ($_GET["status"] == 1) ? 1 : 2;
		if (!isset($_GET['order_id'])) {
			$count = M()->table($order->getTableName() . " ord")
					 ->join($room->getTableName() . " room ON room.id = ord.room_id")
					 ->join($hotel->getTableName() . " hotel ON room.hotel_id = hotel.id")
					 ->join("RIGHT JOIN " . $impr->getTableName() . " impr ON impr.order_id = ord.id")
					 ->where("ord.user_id={$_SESSION["user_id"]} AND impr.status=$status")
					 ->field("ord.order_id as orderid,"
							  . "hotel.names as hotel_name,"
							  . "room.names as room_name,"
							  . "impr.*")
					 ->count();
			$page = $this->pagebar($count);
			$imprs = M()->table($order->getTableName() . " ord")
					 ->join($room->getTableName() . " room ON room.id = ord.room_id")
					 ->join($hotel->getTableName() . " hotel ON room.hotel_id = hotel.id")
					 ->join("RIGHT JOIN " . $impr->getTableName() . " impr ON impr.order_id = ord.id")
					 ->where("ord.user_id={$_SESSION["user_id"]} AND impr.status=$status")
					 ->field("ord.order_id as orderid,"
							  . "hotel.names as hotel_name,"
							  . "room.names as room_name,"
							  . "impr.*")
					 ->page($page)
					 ->select();
			foreach ($imprs as $key => $i) {
				$str = "";
				$impr_name = $commimpr->where("id=" . str_replace(",", " OR id=", $i["impr_type"]))->field("names")->select();
				foreach ($impr_name as $n)
					$str .= $n["names"] . "&nbsp;&nbsp;&nbsp;";
				$imprs[$key]["imprs"] = $str;

				$imprs[$key]["decoration"] = $this->point_to_str($imprs[$key]["decoration"]);
				$imprs[$key]["traffic"] = $this->point_to_str($imprs[$key]["traffic"]);
				$imprs[$key]["hygiene"] = $this->point_to_str($imprs[$key]["hygiene"]);
				$imprs[$key]["prices"] = $this->point_to_str($imprs[$key]["prices"]);
			}
		} else {
			$imprs = M()->table($order->getTableName() . " ord")
					 ->join($room->getTableName() . " room ON room.id = ord.room_id")
					 ->join($hotel->getTableName() . " hotel ON room.hotel_id = hotel.id")
					 ->join("RIGHT JOIN " . $impr->getTableName() . " impr ON impr.order_id = ord.id")
					 ->where("ord.order_id='{$_GET['order_id']}'")
					 ->field("ord.order_id as orderid,"
							  . "hotel.names as hotel_name,"
							  . "room.names as room_name,"
							  . "impr.*")
					 ->select();
			foreach ($imprs as $key => $i) {
				$str = "";
				$impr_name = $commimpr->where("id=" . str_replace(",", " OR id=", $i["impr_type"]))->field("names")->select();
				foreach ($impr_name as $n)
					$str .= $n["names"] . "&nbsp;&nbsp;&nbsp;";
				$imprs[$key]["imprs"] = $str;

				$imprs[$key]["decoration"] = $this->point_to_str($imprs[$key]["decoration"]);
				$imprs[$key]["traffic"] = $this->point_to_str($imprs[$key]["traffic"]);
				$imprs[$key]["hygiene"] = $this->point_to_str($imprs[$key]["hygiene"]);
				$imprs[$key]["prices"] = $this->point_to_str($imprs[$key]["prices"]);
			}
			$status = $imprs[0]['status'];
		}

		$this->assign("status", $status);
		$this->assign("imprs", $imprs);
		$this->display();
	}

	/**
	 * 分数转换成文字
	 */
	private function point_to_str($point) {
		switch ($point) {
			case 100: return "非常好";
			case 80: return "很好";
			case 60: return "一般";
			case 40: return "差";
			case 20: return "非常差";
			default: return "非常好";
		}
	}

	public function hotel_userorder() {
		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
		//以订单id号查询订单，储存为数组 $order
		$user_order = M("HotelOrder");
		$order = M()->table($user_order->getTableName() . " ord")
				 ->join(M("HotelRoomType")->getTableName() . " room ON ord.room_id = room.id")
				 ->where("ord.user_id={$_SESSION['user_id']} AND ord.order_id='$id'")
				 ->field("ord.*, room.hotel_id as hotel_id")
				 ->find();
		//查询入住人信息且合并数据到 $order
		$order["live_peoples"] = M("OrderUserinfo")->where("order_id={$order['id']} AND type='HOTEL'")->select();
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

	// 酒店收藏
	public function collect() {
		$hotel_coll = M("HotelCollection");
		if ($hotel_coll->where("hotel_id={$_GET['hotel']} AND user_id={$_SESSION['user_id']}")->count() > 0) {
			$hotel_coll->where("hotel_id={$_GET['hotel']} AND user_id={$_SESSION['user_id']}")
					 ->save(array("status" => 1, "create_time" => date("Y-m-d H:i:s")));
		} else {
			$data["hotel_id"] = $_GET['hotel'];
			$data["user_id"] = $_SESSION['user_id'];
			$data["create_time"] = date("Y-m-d H:i:s");
			$data["status"] = 1;
			$hotel_coll->add($data);
		}
		$this->redirect("userhotel/hotel_coll");
	}

	public function hotel() {
		$status = (isset($_GET['status'])) ? $_GET['status'] : null;
		$st = (isset($status)) ? " AND status=" . $status : null;
		$this->assign("status", $status);
		//以用户id号和订单状态查询订单，储存为数组 $orders
		$order = M("HotelOrder");
		$count = $order->where("user_id={$_SESSION['user_id']}" . $st)->count();
		$page = $this->pagebar($count);
		$orders = $order->where("user_id={$_SESSION['user_id']}" . $st)->page($page)->select();
		//历遍 $orders ，查询订单中的酒店和房间信息且合并数据到 $orders 的每条子成员
		foreach ($orders as $key => $ord) {
			$info = M("");
			$other_info = $info->table(M("HotelRoomType")->getTableName() . " room")
					 ->join(M("Hotel")->getTableName() . " hotel ON room.hotel_id=hotel.id")
					 ->where("room.id={$ord['room_id']}")
					 ->getField("room.bonus_comm,room.names as roomname,hotel.names as hotelname,hotel.id as hotelid");
			foreach ($other_info as $o)
				$order = array_merge($o, $ord);
			$orders[$key] = array_merge($order, $orders[$key]);
		}

		$this->assign("orders", $orders);
		$this->display();
	}

	public function hotel_coll() {
		$status = (isset($_GET["status"])) ? $_GET["status"] : 1;

		$count = M()->table(M("Hotel")->getTableName() . " hotel")
				 ->join(M("HotelCollection")->getTableName() . " coll ON coll.hotel_id = hotel.id")
				 ->where("coll.user_id={$_SESSION['user_id']} AND coll.status = $status")
				 ->field("hotel.id as hotel_id, hotel.names, coll.create_time, coll.status")
				 ->order("coll.create_time desc")
				 ->count();
		$page = $this->pagebar($count);
		$data = M()->table(M("Hotel")->getTableName() . " hotel")
				 ->join(M("HotelCollection")->getTableName() . " coll ON coll.hotel_id = hotel.id")
				 ->where("coll.user_id={$_SESSION['user_id']} AND coll.status = $status")
				 ->field("hotel.id as hotel_id, hotel.names, coll.create_time, coll.status")
				 ->order("coll.create_time desc")
				 ->page($page)
				 ->select();

		$this->assign("status", $status);
		$this->assign("data", $data);
		$this->display();
	}

	public function hotel_questions() {
		$que = M();
		$count = $que->table(M("Hotel")->getTableName() . " hotel")
				 ->join(M("HotelQue")->getTableName() . " que ON que.hotel_id=hotel.id")
				 ->where("que.user_id={$_SESSION['user_id']}")
				 ->order("que.answer_time desc")
				 ->count();
		$page = $this->pagebar($count);
		$ques = $que->table(M("Hotel")->getTableName() . " hotel")
				 ->join(M("HotelQue")->getTableName() . " que ON que.hotel_id=hotel.id")
				 ->where("que.user_id={$_SESSION['user_id']}")
				 ->order("que.answer_time desc")
				 ->page($page)
				 ->select();
		$this->assign("ques", $ques);
		$this->display();
	}

	public function order_control() {
		$order = M("HotelOrder");
		switch ($_GET["status"]) {
			case 4: //状态4：将跳转至酒店点评
				redirect(U("hotel_impr", array("order" => $_GET["id"])));
				break;
			case 6: //状态6：将申请撤单
				$status = $order->where("order_id='{$_GET["id"]}'")->getField("status");
				$do = 0;
				if ($status < 1)
					$do = 6;
				else
					$do = 7;
				$order->where("order_id='{$_GET["id"]}'")->save(array("front_status" => $status, "status" => $do));
				redirect(U("hotel", array("status" => $do)));
				break;
			case "auto": //状态自动：将还原订单
				$status = $order->where("order_id='{$_GET["id"]}'")->field("id, front_status ,status")->find();
				if ($status["status"] == 7) {
					$order->where("order_id='{$_GET["id"]}'")->save(array("status" => $status["front_status"]));
					redirect(U("hotel", array("status" => $status["front_status"])));
				} else {
					redirect(U("hotel", array("status" => $status["status"])));
				}
				break;
		}
	}

}
