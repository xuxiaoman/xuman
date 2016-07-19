<?php

class hotelAction extends CommonAction {

	private $cookies = array();
	private $last_header;
	private $http_status;

	public function __construct() {
		$authentic = array("hotel_order");
		in_array(ACTION_NAME, $authentic) ? $this->authentic = 1 : $this->authentic = 0;
		parent::__construct();
		C("TOKEN_ON", true);
	}

	//酒店频道页
	public function index() {
		//目的城市
		$target_city = $this->target_city();
		$cid = $this->target_defalut($target_city);
		$belog_id = $target_city[$cid]["id"];
		$search["cid"] = $belog_id;
		$search["city_info"] = $target_city[$cid];
		$now_date = date("Y-m-d");
		$next_date = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j") + 1, date("Y")));
		$search["start_date"] = $now_date;
		$search["end_date"] = $next_date;
		//
		//
		$hotel = M("Hotel");

		$rooms = M("HotelRoomType");
		$model = M();
		$currentCity = $this->tVar["currentCity"]["id"];
		//获取配置
		$setting = conf("HOTEL_RListCount");
		//
		//热门推荐
		$hot_hotels = $this->get_least_price($model->table($hotel->getTableName() . " hotel")
						  ->join(M("HotelImpr")->getTableName() . " impr ON hotel.id = impr.hotel_id")
						  ->where("hotel.status = 4")
						  ->field("hotel.*, SUM(impr.points) as total_points, COUNT(impr.points) as sum_people")
						  ->order("field(hotel.city_id, $currentCity) desc ,hotel.sort asc")
						  ->group("hotel.id")
						  ->limit("0,$setting")
						  ->select());
		//特价推荐
		$price_hotels = $this->get_least_price($hotel->where("status = 3")
						  ->order("field(city_id, $currentCity) desc ,sort asc")
						  ->limit("0,$setting")
						  ->select());
		//点评模块

		$imprs = M("HotelImpr");
		//获取配置
		$setting = conf("HOTEL_IListCount");
		//点评列表
		$impr = $model->table($imprs->getTableName() . " impr")
				 ->join($hotel->getTableName() . " hotel ON hotel.id = impr.hotel_id")
				 ->join("RIGHT JOIN " . M("User")->getTableName() . " user ON impr.user_id = user.id")
				 ->where("impr.status=2 AND hotel.status <> 2")
				 ->field("hotel.id as hotelid, user.username, hotel.names, impr.*")
				 ->order("impr.id desc")
				 ->limit("0,$setting")
				 ->select();
		//
		//热门图片展播
		$this->hot_pic_list(6);

		$this->assign("search", $search);
		$this->assign("price_hotels", $price_hotels);
		$this->assign("hot_hotels", $hot_hotels);
		$this->assign("impr", $impr);
		$this->display();
		unset($hotelinfo);
	}

	/**
	 * 向酒店列表的二维数组中每一条末尾插入一个表示酒店最低价的数组元素，并返回新数组
	 */
	private function get_least_price($hotels) {
		$rooms = M("HotelRoomType");
		foreach ($hotels as $key => $i) {
			//酒店最低价
			$price = $rooms->where("hotel_id={$i['id']} AND (`price_prefer` IN (SELECT MIN(price_prefer) FROM `" .
							  $rooms->getTableName() . "` WHERE hotel_id={$i['id']}))")
					 ->find();
			$hotels[$key]["price"] = $price["price_prefer"];
		}
		return $hotels;
	}

	//热门图片展播
	private function hot_pic_list($pages) {
		$start_city = $this->tVar["currentCity"]["id"];
		$hot_hotel = M('Hotel')->field("id")->order("city_id='$start_city',hits desc")->limit($pages)->select();
		$this->assign("hotel_img_id", $hot_hotel);
	}

	public function lists() {
		$target_city = $this->target_city();
		$cid = $_GET["cid"];
		if (!isset($target_city[$cid])) {
			$cid = $this->target_defalut($target_city);
		}
		$hotel = M("hotel");
		$hotel_areabelong = M("hotel_areabelong");
		$hotel_citybelong = M("hotel_citybelong");
		$hotel_type = M("hotel_type");
		$hotel_chain = M("hotel_chain");
		$Paycart = M('Paycart');
		$Special_service = M('Special_service');
		$Service_item = M('Service_item');
		$Service_dinner = M('Service_dinner');
		$Recreation = M('Recreation');
		$Room_facilitie = M('Room_facilitie');


		$belog_id = $target_city[$cid]["id"];
		$search["cid"] = $belog_id;
		$search["city_info"] = $target_city[$cid];
		preg_match("/^(\d*)\-(\d*)$/", trim($_GET["pos"]), $pos);
		//酒店位置
		$assign['position'][1] = $hotel_citybelong->where("city_id=$belog_id and area_type=1")->getField("id,area_name");
		$assign['position'][2] = $hotel_citybelong->where("city_id=$belog_id and area_type=2")->getField("id,area_name");
		$assign['position'][3] = $hotel_citybelong->where("city_id=$belog_id and area_type=3")->getField("id,area_name");
		$assign['position'][4] = $hotel_citybelong->where("city_id=$belog_id and area_type=4")->getField("id,area_name");
		$assign['position'][5] = $hotel_citybelong->where("city_id=$belog_id and area_type=5")->getField("id,area_name");
		$assign['position'][6] = $hotel_citybelong->where("city_id=$belog_id and area_type=6")->getField("id,area_name");
		$assign['position'] = array_filter($assign['position']);

		if (!isset($assign['position'][$pos[1]])) {
			$pos[1] = key($assign['position']);
		}
		$positions = $assign['position'][$pos[1]];
		$pos[2] = !$positions[$pos[2]] ? key($positions) : $pos[2];
		$search["pos"] = $pos[1] . "-" . $pos[2];
		$pos_name = array(1 => "business_circle", 2 => "canton", 3 => "subway_lines", 4 => "station", 5 => "sightseeing_spots", 6 => "college");
		$pos_name_cn = array(1 => "商圈", 2 => "行政区", 3 => "地铁", 4 => "机场/车站", 5 => "景点", 6 => "大学");
		$where["hotel.city_id"] = $cid;
		$where["area." . $pos_name[$pos[1]]] = $pos[2];

		//价格区间        
		$assign['price'] = array("_200" => "200元以下", "200_300" => "200-300元", "300_500" => "300-500元", "500_1000" => "500-1000元", "1000_" => "1000元以上");
		if (is_numeric($_GET['min_price']) && is_numeric($_GET['max_price'])) {
			$_GET['price'] = $_GET['min_price'] . "_" . $_GET['max_price'];
			$assign['min_price'] = $_GET['min_price'];
			$assign['max_price'] = $_GET['max_price'];
			unset($_GET['min_price'], $_GET['max_price']);
		} elseif (is_numeric($_GET['min_price'])) {
			$_GET['price'] = $_GET['min_price'] . "_";
			$assign['min_price'] = $_GET['min_price'];
			unset($_GET['min_price'], $_GET['min_price']);
		} elseif (is_numeric($_GET['min_price'])) {
			$_GET['price'] = "_" . $_GET['max_price'];
			$assign['max_price'] = $_GET['max_price'];
			unset($_GET['min_price'], $_GET['max_price']);
		}
		if (preg_match("/^\_([1-9]\d{2,})$/", $_GET['price'], $mat)) {
			$where2 = "room.price_prefer <={$mat[1]} or room.price_5 <={$mat[1]} or room.price_6 <={$mat[1]} or room.price_7 <={$mat[1]}";
		} elseif (preg_match("/^([1-9]\d{2,})\_([1-9]\d{2,})$/", $_GET['price'], $mat)) {
			$where2 = "room.price_prefer between {$mat[1]} and {$mat[2]}) or (room.price_5 between {$mat[1]} and {$mat[2]}) or (room.price_6 between {$mat[1]} and {$mat[2]}) or (room.price_7 between {$mat[1]} and {$mat[2]})";
		} elseif (preg_match("/^([1-9]\d{2,})\_$/", $_GET['price'], $mat)) {
			$where2 = "room.price_prefer >={$mat[1]} or room.price_5 >={$mat[1]} or room.price_6 >={$mat[1]} or room.price_7 >={$mat[1]}";
		}
		if ($where2)
			$where["_string"] = $where2;
		$search["price"] = $_GET['price'];



		//酒店级别
		$assign['level'] = $hotel_type->where("status=1")->order("sort")->getField("id,names");
		$level_type_get = explode(",", $_GET["level"]);
		$level_type_get = array_intersect(array_keys($assign['level']), $level_type_get);
		if ($level_type_get) {
			$where["hotel.hotel_type_id"] = array("in", $level_type_get);
			$search["level"] = $level_type_get;
		}
		/*
		  //品牌连锁
		  $assign['chain'] = $hotel_chain->where("status=1")->order("sort")->getField("id,names");
		  $chain_get = explode(",", $_GET["chain"]);
		  $chain_get = array_intersect(array_keys($assign['chain']), $chain_get);
		  if ($chain_get) {
		  $where["hotel.hotel_chain_id"] = array("in", $chain_get);
		  $search["chain"] = $chain_get;
		  }

		  //支持卡类
		  $assign['cart'] = $Paycart->where("status=1")->getField("id,names");
		  $cart_get = explode(",", $_GET["cart"]);
		  $cart_get = array_intersect(array_keys($assign['cart']), $cart_get);
		  if ($cart_get) {
		  $where["hotel.paycart"] = array("in", $cart_get);
		  $search["cart"] = $cart_get;
		  }
		  //特殊服务列表
		  $assign['spe'] = $Special_service->where("status=1")->getField("id,names");
		  $spe_get = explode(",", $_GET["spe"]);
		  $spe_get = array_intersect(array_keys($assign['spe']), $spe_get);
		  if ($spe_get) {
		  $where["hotel.special_service"] = array("in", $spe_get);
		  $search["spe"] = $spe_get;
		  }
		  //服务项目列表
		  $assign['item'] = $Service_item->where("status=1")->getField("id,names");
		  $item_get = explode(",", $_GET["item"]);
		  $item_get = array_intersect(array_keys($assign['item']), $item_get);
		  if ($item_get) {
		  $where["hotel.service_item"] = array("in", $item_get);
		  $search["item"] = $item_get;
		  }
		  //餐饮服务列表
		  $assign['dinner'] = $Service_dinner->where("status=1")->getField("id,names");
		  $dinner_get = explode(",", $_GET["dinner"]);
		  $dinner_get = array_intersect(array_keys($assign['dinner']), $dinner_get);
		  if ($dinner_get) {
		  $where["hotel.service_dinner"] = array("in", $dinner_get);
		  $search["dinner"] = $dinner_get;
		  }
		  //休闲娱乐列表
		  $assign['rec'] = $Recreation->where("status=1")->getField("id,names");
		  $rec_get = explode(",", $_GET["rec"]);
		  $rec_get = array_intersect(array_keys($assign['rec']), $rec_get);
		  if ($rec_get) {
		  $where["hotel.recreation"] = array("in", $rec_get);
		  $search["rec"] = $rec_get;
		  }
		  //房间设施列表
		  $assign['room'] = $Room_facilitie->where("status=1")->getField("id,names");
		  $room_get = explode(",", $_GET["room"]);
		  $room_get = array_intersect(array_keys($assign['room']), $room_get);
		  if ($room_get) {
		  $where["hotel.room_facilitie"] = array("in", $room_get);
		  $search["room"] = $room_get;
		  }
		 * 
		 */

		$label_names = array(
			 "price" => "价格范围",
			 'position' => "酒店位置",
			 'level' => "酒店级别",
				 /* 'chain' => "品牌连锁",
				   'cart' => "支持卡类",
				   'spe' => "特色服务",
				   'item' => "休闲娱乐",
				   'dinner' => "餐饮服务",
				   'rec' => "休闲娱乐",
				   'room' => "房间设施" */
		);

		$now_date = date("Y-m-d");
		if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $_GET["start_date"]) && strtotime($_GET["start_date"]) >= strtotime($now_date)) {
			$search["start_date"] = $_GET["start_date"];
			$where["room.date_limit_begin"] = array("elt", strtotime($now_date));
		} else {
			$search["start_date"] = $now_date;
			$where["room.date_limit_begin"] = array("elt", strtotime($now_date));
		}
		$next_date = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j") + 1, date("Y")));
		if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $_GET["end_date"]) && strtotime($_GET["end_date"]) >= strtotime($next_date)) {
			$search["end_date"] = $_GET["end_date"];
			$where["room.date_limit_end"] = array("egt", strtotime($_GET["end_date"]));
		} else {
			$search["end_date"] = $next_date;
			$where["room.date_limit_end"] = array("egt", strtotime($next_date));
		}


		if (isset($_GET["hotel_name"])) {
			$url_lists['base_url'] .="&hotel_name={$_GET["hotel_name"]}";
			$where["hotel.names"] = array("like" => "%{$_GET["hotel_name"]}%");
			$search["hotel_name"] = $_GET["hotel_name"];
		}
		$url_args = array();
		if (isset($_GET["sort"])) {
			$this->assign('lists_sort', $_GET["sort"]);
			$url_args["sort"] = $_GET["sort"];
			switch ($_GET["sort"]) {
				case "price_desc":
					$order = "room.price_prefer desc";
					break;
				case "price_asc":
					$order = "room.price_prefer asc";
					break;
				case "hits_desc":
					$order = "hotel.hits desc";
					break;
				default:
					$order = "hotel.names";
			}
		}
		$count = $hotel->table($hotel->getTableName() . " hotel")
						  ->join(M("hotel_room_type")->getTableName() . " room on hotel.id=room.hotel_id")
						  ->join(M("hotel_areabelong")->getTableName() . " area on hotel.id=area.hotel_id")
						  ->where($where)->group("room.id")
						  ->field("count(room.id) as tmp_count")->select();
		$rcount = 0;
		foreach ($count as $tcount) {
			$rcount+= $tcount["tmp_count"];
		}
		$page = $this->pagebar($rcount);
		$lists = $hotel->table($hotel->getTableName() . " hotel")
						  ->join(M("hotel_room_type")->getTableName() . " room on hotel.id=room.hotel_id")
						  ->join(M("hotel_areabelong")->getTableName() . " area on hotel.id=area.hotel_id")
						  ->field("hotel.*,room.*,room.id as room_id,hotel.names as hotel_names,area.canton")
						  ->page($page)
						  ->where($where)->order($order)->group("room.id")->select();
		$url_args = array_merge($search, $url_args);
		unset($url_args["cid"], $url_args["city_info"]);
		$url_lists['base_url'] = $url_lists['posittion'] = $url_lists['price'] = $url_lists['sort'] = U("lists") . "?cid=" . $search["city_info"]["cid"];
		foreach ($url_args as $key => $val) {
			if (empty($val))
				continue;
			if ($key == "pos") {
				$url_lists['price'].="&$key=$val";
				$url_lists['base_url'].="&$key=$val";
				$url_lists['sort'].="&$key=$val";
			} elseif ($key == "price") {
				$url_lists['posittion'].="&$key=$val";
				$url_lists['base_url'].="&$key=$val";
				$url_lists['sort'].="&$key=$val";
			} elseif ($key == "sort") {
				$url_lists['price'].="&$key=$val";
				$url_lists['posittion'].="&$key=$val";
				$url_lists['base_url'].="&$key=$val";
			} elseif (in_array($key, array("start_date", "end_date", "hotel_name"))) {
				$url_lists['price'].="&$key=$val";
				$url_lists['posittion'].="&$key=$val";
				$url_lists['base_url'].="&$key=$val";
				$url_lists['sort'].="&$key=$val";
			} else {
				$url_lists['price'].="&$key=" . (is_array($val) ? join(",", $val) : $val);
				$url_lists['posittion'].="&$key=" . (is_array($val) ? join(",", $val) : $val);
				$url_lists['sort'].="&$key=" . (is_array($val) ? join(",", $val) : $val);
			}
		}
		$this->function_lists();

		$this->assign("url_lists", $url_lists);
		$this->assign("search_lists", $assign);
		$this->assign("search", $search);
		$this->assign("label_names", $label_names);
		$this->assign("label_names", $label_names);
		$this->assign("pos_index", $pos);
		$this->assign("pos_name_cn", $pos_name_cn);
		$this->assign("hotel_lists", $lists);
		$this->display();
	}

	public function hotel_detailed() {
		$hotel_id = (isset($_GET["id"])) ? $_GET["id"] : 0;
		$hotel_id = (isset($_GET["hotel"])) ? $_GET["hotel"] : $hotel_id;
		$hotels = M("Hotel");
		$hotel = $hotels->where("id=$hotel_id")->find();
		//
		//图片展播
		$hotel_pic = M("HotelPic")->where("hotel_id=$hotel_id")->field("picpath")->select();

		if ($hotel['position'] != NULL) {
			$position = explode(",", $hotel['position']);
			$hotel['location_x'] = $position[0];
			$hotel['location_y'] = $position[1];
		}
		//查询特色服务项目
		$items = explode(",", $hotel["special_service"]);
		foreach ($items as $i) {
			$map .= " OR id=$i";
		}
		$service = M('Special_service');
		$services = join("、", $service->where("id=0" . $map)->getField("names", true));
		$services = ($services != "") ? $services : "无";
		unset($map);

		//查询餐饮服务项目
		$items = explode(",", $hotel["service_dinner"]);
		foreach ($items as $i) {
			$map .= " OR id=$i";
		}
		$dinner = M('Service_dinner');
		$dinners = join("、", $dinner->where("id=0" . $map)->getField("names", true));
		$dinners = ($dinners != "") ? $dinners : "无";
		unset($map);

		//查询休闲娱乐项目
		$items = explode(",", $hotel["recreation"]);
		foreach ($items as $i) {
			$map .= " OR id=$i";
		}
		$Recreation = M('Recreation');
		$Recreations = join("、", $Recreation->where("id=0" . $map)->getField("names", true));
		$Recreations = ($Recreations != "") ? $Recreations : "无";
		unset($map);

		//查询服务项目
		$items = explode(",", $hotel["service_item"]);
		foreach ($items as $i) {
			$map .= " OR id=$i";
		}
		$Service = M('Service_item');
		$Services = join("、", $Service->where("id=0" . $map)->getField("names", true));
		$Services = ($Services != "") ? $Services : "无";
		unset($map);

		//查询酒店的房间信息
		$begin = (isset($_POST["begin_time"])) ? strtotime($_POST["begin_time"]) : time();
		$leave = (isset($_POST["begin_end"])) ? strtotime($_POST["begin_end"]) : strtotime("+1 day");
		$room = M("HotelRoomType");
		$order = M('HotelOrder');
		$rooms = M()->query("SELECT room.*, odate.come_date, odate.leave_date, room.id as room_id, SUM(odate.room_count) as order_roomcount FROM " .
				 $room->getTableName() . " room LEFT JOIN ( SELECT * FROM `" . $order->getTableName() . "` ord WHERE" .
				 " ( ord.come_date <= $begin AND ord.leave_date >= $leave )) odate ON room.id = odate.room_id  WHERE" .
				 " (room.hotel_id = $hotel_id) GROUP BY room.id");

		//按时间筛选房间
		foreach ($rooms as $key => $i) {
			$rooms[$key]["come_date"] = (empty($i["come_date"])) ? 0 : $i["come_date"];
			$rooms[$key]["leave_date"] = (empty($i["leave_date"])) ? 0 : $i["leave_date"];
			$rooms[$key]["order_roomcount"] = (empty($i["order_roomcount"])) ? 0 : $i["order_roomcount"];
			if (!($i["date_limit_begin"] <= $begin && $i["date_limit_end"] >= $leave)) {
				unset($rooms[$key]);
			}
		}

		//查询该用户是否已经收藏该酒店
		$is_collected = M("HotelCollection")->where("user_id={$_SESSION['user_id']} AND hotel_id = $hotel_id")->getField("status");
		$is_collected = ($is_collected != null) ? $is_collected : 0;

		//酒店咨询
		$que = M("HotelQue");
		$user = M("User");
		$ques = M()->table($que->getTableName() . " que")
				 ->join($user->getTableName() . " user ON user.id = que.user_id")
				 ->where("que.hotel_id = $hotel_id")
				 ->field("*, que.status as que_status")
				 ->select();

		//房型信息
		$room = M("HotelRoomType");
		$map["hotel_id"] = array("eq", $hotel_id);
		$list = $room->where($map)->select();
		foreach ($list as $key => $i) {
			$list[$key]["nicePrice"] = min(array($i["price_retail"], $i["price_prefer"], $i["price_5"], $i["price_6"], $i["price_7"]));
		}

		// 判断用户是否已经登录
		if (isset($_SESSION["user_id"]) && M("User")->where("id={$_SESSION["user_id"]}")->count() > 0) {
			$is_logined = true;
		} else {
			$is_logined = false;
		}

		//用户点评
		$impr = M("HotelImpr");
		$imprs = M()->table($impr->getTableName() . " impr")
				 ->join(M('User')->getTableName() . " user ON impr.user_id = user.id")
				 ->where("impr.hotel_id = $hotel_id AND impr.status = 2")
				 ->select();
		$points_sum = $impr->where("hotel_id = $hotel_id")->sum("points");
		$point["point"] = round($points_sum / count($imprs));
		$point["users"] = count($imprs);
		//统计设施装潢
		$impr_counts["decoration"] = round(($impr->where("hotel_id = $hotel_id")->sum("decoration")) / count($imprs));
		//统计交通位置
		$impr_counts["traffic"] = round(($impr->where("hotel_id = $hotel_id")->sum("traffic")) / count($imprs));
		//统计卫生情况
		$impr_counts["hygiene"] = round(($impr->where("hotel_id = $hotel_id")->sum("hygiene")) / count($imprs));
		//统计性价比
		$impr_counts["prices"] = round(($impr->where("hotel_id = $hotel_id")->sum("prices")) / count($imprs));
		//获取点评印象
		$cimpr = M("CommImpr");
		foreach ($imprs as $key => $i) {
			$cimpra = explode(",", $i["impr_type"]);
			$cimprstr = join(" OR id=", $cimpra);
			$cimprs = $cimpr->where("id=" . $cimprstr)->field("names")->select();
			$colors = array("blue", "green", "pink", "orange", "red", "brown", "purple", "gray");
			$last_color = null;
			foreach ($cimprs as $k => $is) {
				$tmp_color = $colors[rand(0, 7)];
				while ($last_color == $tmp_color)
					$tmp_color = $colors[rand(0, 7)];
				$cimprs[$k]["color"] = $tmp_color;
				$last_color = $tmp_color;
			}
			$imprs[$key]["cimprs"] = $cimprs;
		}

		//增加该酒店的点击数
		$hotels->where("id=$hotel_id")->setInc("hits", 1);

		$this->assign('is_logined', $is_logined);
		$this->assign("hotel_img", $hotel_pic);
		$this->assign('piclist', $list);
		$this->assign("hotel_id", $hotel_id);
		$this->assign("hotelinfo", $hotel);
		$this->assign("services", $services);
		$this->assign("dinners", $dinners);
		$this->assign("Recreations", $Recreations);
		$this->assign("Services", $Services);
		$this->assign("rooms", $rooms);
		$this->assign("ques", $ques);
		$this->assign("during_time", array("begin" => $begin, "leave" => $leave));
		$this->assign("point", $point);
		$this->assign("impr_counts", $impr_counts);
		$this->assign("imprs", $imprs);
		$this->assign("is_collected", $is_collected);
		$this->display();
	}

	//添加、取消酒店收藏
	public function ajax_collect() {
		$hotel_coll = M("HotelCollection");
		if ($hotel_coll->where("hotel_id={$_POST['hotel']} AND user_id={$_SESSION['user_id']}")->count() > 0) {
			if ($hotel_coll->where("hotel_id={$_POST['hotel']} AND user_id={$_SESSION['user_id']}")
							  ->save(array("status" => $_POST["status"], "create_time" => date("Y-m-d H:i:s")))) {
				$this->ajaxReturn(true);
				exit;
			}
		} else {
			$data["hotel_id"] = $_POST['hotel'];
			$data["user_id"] = $_SESSION['user_id'];
			$data["create_time"] = date("Y-m-d H:i:s");
			$data["status"] = $_POST['status'];
			if ($hotel_coll->add($data)) {
				$this->ajaxReturn(true);
				exit;
			}
		}
		$this->ajaxReturn(false);
		exit;
	}

	//酒店详细页：用户提问
	public function hotel_quePublish() {
		if (md5($_POST["verify"]) == session("verify")) {
			$_POST["hotel_id"] = $_GET["hotel"];
			$_POST["user_id"] = session("user_id");
			$_POST["publish_time"] = time();
			$_POST["sort"] = 0;
			$que = M("HotelQue");
			$que->create();
			$que->add();
			$this->success("发表成功！");
		} else {
			$this->error("验证码错误，请重新输入");
		}
	}

	protected function function_lists() {
		if (!function_exists("get_area_belong")) {

			function get_area_belong($canton_id, $url) {
				$area_name = M("hotel_citybelong")->where("id=$canton_id")->getField("area_name");
				return $area_name ? " (<a href=\"$url&pos=2-$canton_id\">$area_name</a>) " : "";
			}

		}
		if (!function_exists("get_impr_count")) {

			function get_impr_count($hotel_id) {
				$impr_count = M("hotel_impr")->where("hotel_id=$hotel_id")->count();
				return $impr_count;
			}

		}
		if (!function_exists("get_impr_point")) {

			function get_impr_point($hotel_id) {
				$impr_avg = M("hotel_impr")->where("hotel_id=$hotel_id")->avg("point");
				return $impr_avg;
			}

		}
	}

	public function get_hotel_city() {
		$city_belong = M("city_belong")->getTableName() . " belong";
		$area = M("area")->getTableName() . " area";
		$target_city = M()->table($city_belong)
				 ->join($area . " on area.id=belong.cid")
				 ->where("belong.types ='HOTEL'")
				 ->order("area.pid,belong.sort")
				 ->getField("belong.cid,belong.id,area.pid,area.names,area.names_en");
		$this->ajaxReturn($target_city);
	}

	protected function target_city() {
		$city_belong = M("city_belong")->getTableName() . " belong";
		$area = M("area")->getTableName() . " area";
		$target_city = M()->table($city_belong)
				 ->join($area . " on area.id=belong.cid")
				 ->where("belong.types ='HOTEL'")
				 ->order("area.pid,belong.sort")
				 ->getField("belong.cid,belong.id,area.pid,area.names,area.names_en");
		$this->assign("target_city", $target_city);
		return $target_city;
	}

	protected function target_defalut($target_city_lists) {
		$city_belong = M("city_belong");
		$default_city = $city_belong->where("types='HOTEL' and isdefalut=1")->getField("cid");
		if ($default_city) {
			return $default_city;
		}
		$ip = get_client_ip();
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip;
		$json = $this->getHttp($url);
		$address = json_decode($json, true);
		$ipcity = strtolower(Pinyin($address["city"]));
		foreach ($target_city_lists as $key => $val) {
			if (strtolower($val["names_en"]) == $ipcity) {
				$target_id = $key;
				break;
			}
		}
		return $target_id ? $target_id : key($target_city_lists);
	}

	/**
	 * 酒店订单（下单）
	 */
	public function hotel_order() {
		$hotel_id = (isset($_GET["hotel"])) ? $_GET["hotel"] : 0;
		$room_id = (isset($_GET["room"])) ? $_GET["room"] : 0;
		$this->assign("hotel_id", $hotel_id);
		$this->assign("room_id", $room_id);

		// 获取酒店和房间信息
		$info = M();
		$hotelInfo = $info->table(M("Hotel")->getTableName() . " hotel")
				 ->join(M("HotelRoomType")->getTableName() . " room On hotel.id=room.hotel_id")
				 ->where("hotel.id=$hotel_id AND room.id=$room_id")
				 ->field("*,hotel.names as hotelname")
				 ->find();
		$this->assign("info", $hotelInfo); // 输出模板变量：酒店和房间的基本信息
		// 如果房间的日期限制跨过了今天，则一律以今天为起点
		if ($hotelInfo["date_limit_begin"] - strtotime(date("Y-m-d")) < 0)
			$hotelInfo["date_limit_begin"] = strtotime(date("Y-m-d"));
		// 计算房间日期限制的时间跨度（单位：秒）
		$during = $hotelInfo["date_limit_end"] - $hotelInfo["date_limit_begin"];
		$select_days = null; // 模板中 入住日期 和 离店日期 下拉菜单的 option 字符串
		$emptyRooms = array(); // 指定时间跨度中（每天）该房间信息的二维数组（价格、剩余空房几间等）
		// 按时间跨度查询订单
		$od = D("HotelOrder");
		for ($i = 0; $i < $during / 86400 + 1; $i++) {
			$select_days .= "<option value='" . ($hotelInfo["date_limit_begin"] + $i * 86400) . "'>" . date("Y年m月d日", ($hotelInfo["date_limit_begin"] + $i * 86400)) . "</option>";
			$emptyRooms[$i]["date"] = date("m月d日", ($hotelInfo["date_limit_begin"] + $i * 86400));
			$emptyRooms[$i]["time"] = ($hotelInfo["date_limit_begin"] + $i * 86400);
			$emptyRooms[$i]["weekday"] = date("w", ($hotelInfo["date_limit_begin"] + $i * 86400));
			// 获得该房型某天剩余的空房数
			$emptyRooms[$i]["room_count"] = ($od->getRoomCountOfOneDay(($hotelInfo["date_limit_begin"] + $i * 86400), $room_id));
			// 计算周末价
			switch ($emptyRooms[$i]["weekday"]) {
				case "0":$emptyRooms[$i]["price"] = $hotelInfo['price_7'];
					break;
				case "6":$emptyRooms[$i]["price"] = $hotelInfo['price_6'];
					break;
				case "5":$emptyRooms[$i]["price"] = $hotelInfo['price_5'];
					break;
				default :$emptyRooms[$i]["price"] = $hotelInfo['price_prefer'];
					break;
			}
			$emptyRooms[$i]["price"] = ($emptyRooms[$i]["price"] == 0) ? $hotelInfo['price_prefer'] : $emptyRooms[$i]["price"];
		}
		// 如果时间跨度超过8天，则截取前8天的数据
		$emptyRoom = count($emptyRooms);
		if ($emptyRoom > 8)
			for ($i = 8; $i < $emptyRoom; $i++)
				unset($emptyRooms[$i]);
		// 在时间跨度范围内，计算订单最多能订多少间房
		$count = 100000000;
		for ($i = 1; $i <= count($emptyRoom); $i++) {
			if ($emptyRooms[$i]["room_count"] < $count)
				$count = $emptyRooms[$i]["room_count"];
		}
		// 生成选择订房数量的下拉菜单的 option （1～最多能订的间房数）
		for ($i = 1; $i <= $count; $i++)
			$ROOM .= "<option value='$i'>$i</option>";
		$this->assign("ROOM", $ROOM); // 输出模板变量：选择订房数量的下拉菜单
		$this->assign("days", $select_days); // 输出模板变量：入住日期和离店日期的下拉菜单
		$this->assign("emptyRooms", $emptyRooms); // 输出模板变量：指定时间跨度中（每天）房间信息的二维数组（价格、剩余空房几间等）
		// 获得用户的总奖金并计算该订单最多允许使用多少奖金
		$award = M("User")->where("id={$_SESSION['user_id']}")->getField("award");
		$can_useAward = ($award - $hotelInfo["price_bonus_part"] > 0) ? $hotelInfo["price_bonus_part"] : $award;
		$this->assign("award", array($award, $can_useAward)); // 输出模板变量：用户的总奖金和该订单最多允许使用的奖金
		$this->assign("date_length", array("begin" => $hotelInfo["date_limit_begin"], "end" => $hotelInfo["date_limit_end"]));
		$this->display();
	}

	public function look_serial() {
		$num = $_GET["num"];
		$cash = M("CashCoupon");
		$data["look"] = $cash->where("serial_num='$num'")->find();
		$count = $cash->where("use_userid={$_SESSION['user_id']}")->count();
		$page = $this->pagebar($count);
		$data["mine"] = $cash->where("use_userid={$_SESSION['user_id']}")->page($page)->order("use_time desc")->select();
		$data["look"]['serial_num'] = $num;
		$this->assign("serial_info", $data);
		C("LAYOUT_ON", false);
		$this->display();
	}

	public function ajax_getEmptyRooms() {
		$hotel_id = (isset($_GET["hotel"])) ? $_GET["hotel"] : 0;
		$room_id = (isset($_GET["room"])) ? $_GET["room"] : 0;
		$hotelInfo = M("HotelRoomType")->where("hotel_id=$hotel_id AND id=$room_id")->find();
		$during = $_GET["date_limit_end"] - $_GET["date_limit_begin"];
		$emptyRooms = array();
		$od = D("HotelOrder");
		for ($i = 0; $i < $during / 86400 /* + 1 */; $i++) {
			$emptyRooms[$i]["date"] = date("m月d日", ($_GET["date_limit_begin"] + $i * 86400));
			$emptyRooms[$i]["time"] = ($_GET["date_limit_begin"] + $i * 86400);
			$emptyRooms[$i]["weekday"] = date("w", ($_GET["date_limit_begin"] + $i * 86400));
			$emptyRooms[$i]["room_count"] = ($od->getRoomCountOfOneDay(($_GET["date_limit_begin"] + $i * 86400), $room_id));
			switch ($emptyRooms[$i]["weekday"]) {
				case "0":$emptyRooms[$i]["price"] = $hotelInfo['price_7'];
					break;
				case "6":$emptyRooms[$i]["price"] = $hotelInfo['price_6'];
					break;
				case "5":$emptyRooms[$i]["price"] = $hotelInfo['price_5'];
					break;
				default :$emptyRooms[$i]["price"] = $hotelInfo['price_prefer'];
					break;
			}
			$emptyRooms[$i]["price"] = ($emptyRooms[$i]["price"] == 0) ? $hotelInfo['price_prefer'] : $emptyRooms[$i]["price"];
		}
		$count = 100000000;
		foreach ($emptyRooms as $ers) {
			if ($ers["room_count"] < $count)
				$count = $ers["room_count"];
		}
		for ($i = 1; $i <= $count; $i++)
			$ROOM .= "<option value='$i'>$i</option>";
		$this->ajaxReturn(array("emptyRooms" => $emptyRooms, "ROOM" => $ROOM));
		exit;
	}

	public function do_order() {
		$order = M("HotelOrder");
		$_POST["come_date"] = strtotime($_POST["come_date"]);
		$_POST["leave_date"] = strtotime($_POST["leave_date"]);
		$data = $order->create();
		if($data){
		$data["order_id"] = "H" . date("ymdHi") . rand(10000, 99999);
		$data["user_id"] = $_SESSION["user_id"];
		$data["room_id"] = $_GET["room"];
		if (isset($_POST["serial_num"]) && $_POST["serial_num"] != "") {
			$serial = M("CashCoupon")->where("serial_num='{$_POST["serial_num"]}' AND status=0 AND expire_time>=" . time())->find();
			if (!$serial) {
				$this->error("代金券编号无效");
				exit;
			} else {
				$data["serial_num"] = $_POST["serial_num"];
				$data["serial_money"] = $serial["coupon_value"];
				$this->use_serial($_POST["serial_num"], $_SESSION["user_id"]);
			}
		}
		$this->use_award($data["bonus_money"], $data["user_id"]);

		$data["status"] = 0;
		$data["add_time"] = time();
		$order_id = $order->add($data);
		$userInf = M("OrderUserinfo");
		$info_id = array();
		for ($i = 0; $i < count($_POST["names"]); $i++) {
			$userData["order_id"] = $order_id;
			$userData["names"] = $_POST["names"][$i];
			$userData["credentials"] = $_POST["credentials"][$i];
			$userData["content"] = $_POST["content"][$i];
			$userData["type"] = "HOTEL";
			$info_id[$i] = $userInf->add($userData);
		}

		//统计订单数据并展示
		$hotelinf = M()->table(M("Hotel")->getTableName() . " hotel")
				 ->join(M("HotelRoomType")->getTableName() . " room ON hotel.id = room.hotel_id")
				 ->where("room.id = {$data['room_id']}")
				 ->getField("hotel.id as hotelid, hotel.names as hotel_name, room.names as room_name, hotel.address");
		sort($hotelinf);
		$data = array_merge($data, $hotelinf[0]);
		$data["must_pay"] = $_POST["must_pay"];
		$order->where("id=$order_id")->save(array("total" => $_POST["must_pay"]));

		$this->assign("orderinfo", $data);
		$this->display("hotel_order_untreated");
		}else{
			$this->error("请不要重复提交订单！");
		}
	}

	//订单付款页面
	public function hotel_pay() {
		//统计订单数据并展示
		$data = M()->table(M("HotelOrder")->getTableName() . " ord")
				 ->join(M("User")->getTableName() . " user ON user.id = ord.user_id")
				 ->where("ord.order_id='{$_GET["id"]}'")
				 ->field("*, user.username as user_name, user.amount as user_amount")
				 ->find();
		$hotelinf = M()->table(M("Hotel")->getTableName() . " hotel")
				 ->join(M("HotelRoomType")->getTableName() . " room ON hotel.id = room.hotel_id")
				 ->where("room.id = {$data['room_id']}")
				 ->getField("hotel.id as hotelid, hotel.names as hotel_name, room.names as room_name, hotel.address, room.front_money");
		sort($hotelinf);
		$data = array_merge($data, $hotelinf[0]);
		$data["front_percent"] = $data["front_money"];
		$data["front_money"] = $data["front_money"] * $data["total"] / 100;

		$this->assign("orderinfo", $data);
		$this->assign("id", $_GET["id"]);
		$this->display("hotel_order_treated");
	}

	public function banks() {
		$payment = new paymentapiAction();
		$banks = $payment->get_banks($_POST["api_name"]);
		header("Content-type: application/json");
		echo json_encode($banks);
	}

	//验证支付密码
	public function ajax_check_PayPassword() {
		$pay_password = M()->table(M("HotelOrder")->getTableName() . " ord")
				 ->join(M("User")->getTableName() . " user ON user.id = ord.user_id")
				 ->where("ord.order_id='{$_GET["id"]}'")
				 ->getField("pay_password");
		$pass = md5($_POST["pass"]);
		if ($pass == $pay_password) {
			$data["result"] = true;
			$data["check"] = $pass;
		} else {
			$data["result"] = false;
		}
		$this->ajaxReturn($data);
		exit;
	}

	public function pay() {
		if (isset($_POST["handle"])) {
			$user = M("User");
			$Order = M("HotelOrder");

			$Order->where("order_id='{$_GET["id"]}'")->save(array("pay_status" => $_POST["pay_status"]));
			$order = $Order->where("order_id='{$_GET["id"]}'")->find();
			//处理余额支付
			$money = 0;
			if (isset($_POST["use_amount"]) && $_POST["pass"] != null && $_POST["pass"] != "") {
				//再次验证支付密码
				$userinfo = $user->where("id={$order['user_id']}")->find();
				if ($userinfo["pay_password"] == $_POST["pass"]) {
					//验证通过，扣除用户的金额并作付款操作
					$money = min(array($userinfo["amount"], (($_POST["pay_status"] == 0) ? $_POST["payfront"] : $order["all_money"])));
					$user->where("id={$order['user_id']}")->setDec("amount", $money);
					$Order->where("order_id='{$_GET["id"]}'")->setInc("pay_money", $money);
				}
			}
			//检查用余额支付的方式是否已经足以把订单支付完成并填写bill表，修改订单状态
			$order = $Order->where("order_id='{$_GET["id"]}'")->find();
			if ($this->check_order_And_create_bill($order["id"], (date("YmdHis", time()) . rand(100, 999)), $money, "ODYE")) {
				//足以支付，更改订单的状态为“已支付”，并提示支付成功
				if ($Order->where("order_id='{$_GET["id"]}'")->save(array("status" => 2)))
					$this->assign("res", 1);
				else
					$this->assign("res", 2);
				$info["order_id"] = $order["order_id"];
				$info["money"] = round($money, 2);
				$info["username"] = $user->where("id={$order['user_id']}")->getField("username");
				$info["bank"] = "账户余额支付";

				$this->assign("info", $info);
				$this->display("hotel_success");
			} else {
				//不足以支付或未使用账户余额支付。生成流水订单
				$rechange = M("UserRecharge");
				$data['user_id'] = $order["user_id"];
				$data['order_id'] = $order["id"];
				$data['code_id'] = date("YmdHis", time()) . rand(100, 999);
				$data['money'] = abs((($_POST["pay_status"] == 0) ? $_POST["payfront"] : $order["all_money"]) - $order["pay_money"]);
				$data['api_name'] = $_POST["payType"];
				$data['bank_name'] = $_POST[$_POST["typeName"]];
				$data['browser'] = $_SERVER["HTTP_USER_AGENT"];
				$data['start_time'] = time();
				$data['status'] = 0;
				$rechange->create($data);
				$rechange->add();

				//获得银行资料
				$hotel_order = D("HotelOrder");
				$bank = $hotel_order->payment_get_A_bank($data['bank_name']);
				$data['api'] = $bank["payment_name"];
				$data['bank'] = array('name' => $bank["bank_name"], 'icon' => __HOST__ . __ROOT__ . "/" . $bank["pic"]);

				//远端数据
				$data["MerchantID"] = C("MerchantID"); //商户号
				$data["MerchantKey"] = C("MerchantKey"); //商户密钥
				$data["md5info"] = strtoupper(md5($data["MerchantID"] . $data["code_id"] . $data['money'] . $data['api_name'] . $data["MerchantKey"])); //签名

				$this->assign("data", $data);
				$this->display("bill");
			}
		}

		//账单中途处理并提交到JPP
		if ($_GET['turntojpp'] == "true") {
			$Receive = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['SCRIPT_NAME'] . "/hotel/show_payResult";
			$AutoReceive = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['SCRIPT_NAME'] . "/hotel/payResult";
			header("Content-type:text/html;charset=utf-8");
			echo "订单正在处理，请稍等...<form action='" . C('paymentAPI_Path') . "' method='post' name='payForm'>
				<input type='hidden' name='OrderId' value='{$_POST["OrderId"]}' />
				<input type='hidden' name='MerchantID' value='{$_POST["MerchantID"]}' />
				<input type='hidden' name='md5info' value='{$_POST["md5info"]}' />
				<input type='hidden' name='amount' value='{$_POST["amount"]}' />
				<input type='hidden' name='apiName' value='{$_POST["apiName"]}' />
				<input type='hidden' name='bank' value='{$_POST["bank"]}' />
				<input type='hidden' name='Receive' value='$Receive' />
				<input type='hidden' name='AutoReceive' value='$AutoReceive' />
			</form><script type='text/javascript'>payForm.submit();</script>";
		}
	}

	//接口回调（后台）
	public function payResult() {
		$check = strtoupper(md5($_POST["MerchantID"] . $_POST["OrderId"] . $_POST["amount"] . $_POST["apiName"] . $_POST["status"] . C("MerchantKey")));
		if ($_POST["md5info"] == $check) {
			//支付完成，更改订单的状态为“已支付”，并提示支付成功
			$rec = M("UserRecharge");
			$order = M("HotelOrder");
			$orderid = M()->table($rec->getTableName() . " rech")
					 ->join($order->getTableName() . " ord ON rech.order_id = ord.id")
					 ->where("rech.code_id='{$_POST["OrderId"]}'")
					 ->getField("ord.id");
			$order->where("id=$orderid")->setInc("pay_money", $_POST["amount"]);
			if ($this->check_order_And_create_bill($orderid, $_POST["OrderId"], $_POST["amount"], "ODYH", $_POST["pay_status"])) {
				$rec->where("order_id=$orderid")->save(array("status" => 1));
				$order->where("id=$orderid")->save(array("status" => 2));
			}
			echo "OK";
		} else
			echo "NO";
		exit;
	}

	//接口回调（前台）
	public function show_payResult() {
		$orders = M("HotelOrder");
		$order = M()->table(M("UserRecharge")->getTableName() . " rech")
				 ->join($orders->getTableName() . " ord ON rech.order_id = ord.id")
				 ->where("rech.code_id='{$_POST["OrderId"]}'")
				 ->getField("ord.id");
		$info = M()->table(M("User")->getTableName() . " user")
				 ->join($orders->getTableName() . " ord ON user.id = ord.user_id")
				 ->where("ord.id=$order")
				 ->find();

		$info["money"] = round($_POST["amount"], 2);
		$info["bank"] = $_POST["bank"];
		$this->assign("info", $info);

		if ($order > 0) {
			if ($_POST["status"] == "01")
				$this->assign("res", 1);
			else
				$this->assign("res", 2);
		} else
			$this->assign("res", 2);

		$this->display("hotel_success");
	}

	/**
	 * 检查订单是否完整支付，是的话即返回 true，否则返回 false，同时生成 bill 数据并写入数据库（无论是否完整支付均生成 bill）
	 */
	public function check_order_And_create_bill($orderID, $codeID, $money, $mark) {
		$bill = M("UserBill");
		$order = M("HotelOrder");
		$ord = M()->table($order->getTableName() . " ord")
				 ->join(M("User")->getTableName() . " user ON ord.user_id = user.id")
				 ->join("RIGHT JOIN " . M("HotelRoomType")->getTableName() . " room ON ord.room_id = room.id")
				 ->where("ord.id=$orderID")
				 ->field("ord.*, user.*, room.front_money as front_money")
				 ->find();
		if ($money != 0) {
			$bill_data["user_id"] = $ord["user_id"];
			$bill_data["code_id"] = $codeID;
			$bill_data["money"] = $money;
			$bill_data["balance"] = $ord["amount"];
			$bill_data["mark"] = $mark;
			$bill_data["type"] = 2;
			$bill_data["create_time"] = time();
			$bill->add($bill_data);
		}


		$order->where("id=$orderID")->setInc("front_money", $money);
		if (($ord["pay_money"] + $ord["serial_money"] + $ord["bonus_money"]) == (($ord["pay_status"] == 1) ? $ord["total"] : ($ord["total"] * $ord["front_money"] / 100)))
			return true;
		else
			return false;
	}

	// 等待支付
	public function wait() {
		$id = $_POST['order_id'];
		$data = M()->table(M("HotelOrder")->getTableName() . " ord")
				 ->join(M("HotelRoomType")->getTableName() . " room ON ord.room_id = room.id")
				 ->where("ord.order_id='$id'")
				 ->field("ord.order_id, room.hotel_id")
				 ->find();
		$this->assign("data", $data);
		$this->display();
	}

	/**
	 * 有效使用并注销一张代金券<br/>
	 * @param string $serial_num 代金券编号<br/><br/>
	 */
	private function use_serial($serial_num, $user_id) {
		$data["use_time"] = time();
		$data["status"] = 1;
		$data["use_userid"] = $user_id;
		M("CashCoupon")->where("serial_num='$serial_num' AND status=0 AND expire_time>=" . time())->save($data);
	}

	/**
	 * 有效使用并扣除相应奖金<br/>
	 */
	private function use_award(float $bonus_money, int $user_id) {
		$user = M("User");
		$user->where("id=$user_id")->setDec("award", $bonus_money);
	}

	public function ajax_use_serial() {
		$cash = M("CashCoupon");
		if ($cash->where("serial_num='{$_POST["serial_num"]}' AND status=0 AND expire_time>=" . time())->count()) {
			$this->ajaxReturn(array($cash->where("serial_num='{$_POST["serial_num"]}'")->getField("coupon_value")));
			exit;
		} else {
			$this->ajaxReturn(0);
			exit;
		}
	}

	private function getHttp($url = '', $post = array(), $cookie = array()) {
		$conn = curl_init($url);
		curl_setopt($conn, CURLOPT_TIMEOUT, $this->configs['timeout']);
		curl_setopt($conn, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($conn, CURLOPT_MAXREDIRS, 7); //HTTp定向级别
		curl_setopt($conn, CURLOPT_HEADER, 1);
		$this->cookies = array_merge($this->cookies, $cookie);
		if ($this->cookies) {
			curl_setopt($conn, CURLOPT_COOKIE, join(';', $this->cookies));
		}
		if ($post) {
			curl_setopt($conn, CURLOPT_POST, 1);
			curl_setopt($conn, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
		curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($conn);
		list($header, $body) = explode("\r\n\r\n", $content);
		preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $matches);
		$cookies = $cookies_tmp = array();
		if ($matches) {
			foreach ($matches[1] as $match) {
				$cookies_tmp = array_merge($cookies_tmp, explode(";", $match));
			}
		}
		foreach ($cookies_tmp as $item) {
			$item = trim($item);
			if (!in_array($item, $cookies))
				$cookies[] = $item;
		}
		$this->cookies = $cookies;
		$this->last_header = $header;
		preg_match("/^HTTP(?:S)?\/\d\.\d\s(\d*)\s/", $header, $http_status);
		$this->http_status = $http_status[1];
		return $body;
	}

}
