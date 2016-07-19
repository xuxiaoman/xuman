<?php

/**
 * 文件名称：viewpointAction.class.php
 * 用途说明：景点管理类，用以实现景点管理的各项功能。
 * 开发人员：maruiming2010@jeechange.com
 * 创建时间：2013/11/05 14:04:00
 */
class viewpointAction extends CommonAction {
	/* 景点门票的罗列页 */

	protected $authentic = 0; //是否开启用户认证，0：不开启，1：开启
	private $type_select;
	private $topic = array(1 => "古镇游", 2 => "山水游", 3 => "海岛游", 4 => "乐园游");
	protected $max_p = 3000;

	public function _initialize() {
            C("TOKEN_ON", false);
		$this->authentic = 0;
		parent::_initialize();
		$this->type_select = $_GET["type"] ? intval($_GET["type"]) : -1;
	}

	public function viewpoint() {
		$viewpoint_order = M('viewpoint_order')->getTableName() . "";
		$viewpoint_ticket = M("viewpoint_ticket")->getTableName() . " ticket";
		$viewpoint = M("viewpoint")->getTableName() . " viewpoint";
		$viewpoint_impr = M("viewpoint_impr")->getTableName() . " impr";
		//热销门票
		$setting = conf("Viewpoint_CListCount");
		$order = M()->table($viewpoint_order)
				 ->join($viewpoint_ticket . " on ticket_id=ticket.id")
				 ->join($viewpoint . " on viewpoint.id=ticket.viewpoint_id")
				 ->field("$viewpoint_order.*,viewpoint.names,SUM(ticket_num) as num,ticket.price")
				 ->group("ticket_id")
				 ->order("num desc")
				 ->limit(6)
				 ->select();
                //echo M()->getLastSql();exit;
                

		//点评列表
		$impr = M()->table($viewpoint_impr)
				 ->join($viewpoint . " on viewpoint.id = impr.view_id")
				 ->join("RIGHT JOIN " . M("User")->getTableName() . " user ON impr.user_id = user.id")
				 ->where("impr.status=2")
				 ->field("user.username, viewpoint.names, impr.*")
				 ->order("impr.sort desc")
				 ->limit("0,6")
				 ->select();
		//echo M()->getLastSql();exit;
		//查询相应的景点图片
		//景点列表
		$view = M()->table($viewpoint)
				 ->join(M("Recommend")->getTableName() . " recommend ON viewpoint.id = recommend.obj_id")
				 ->join(M("CityBelong")->getTableName() . " cityb ON viewpoint.city_id = cityb.cid")
				 ->join(M("Area")->getTableName() . " area ON cityb.cid = area.id")
				 ->where("viewpoint.status = 0 AND cityb.isshow = 1 AND cityb.types='Viewpoint' AND recommend.type = 'Viewpoint' AND recommend.rec_type = 2 AND recommend.status = 1")
				 ->field("cityb.id as city_id, viewpoint.id as viewpoint_id, viewpoint.names as viewpoint_name, area.names as area")
				 ->order("viewpoint.sort desc")
				 //->limit($setting)
				 ->select();
		//echo M()->getLastSql();exit;
		//dump($view);

		$pics = M("viewpoint_pic");

		$ticket = M("viewpoint_ticket");
		$picinfo = array();
		foreach ($view as $key => $i) {
			//景点图片
			$pic = $pics->where("viewpoint_id={$i['viewpoint_id']} AND (`istitlepage` = 1 OR (`id` IN (SELECT MAX(id) FROM `" . $pics->getTableName() . "` WHERE viewpoint_id={$i['viewpoint_id']})))")
					 ->field("picpath")
					 ->find();


			//酒店最低价
			$price = $ticket->where("viewpoint_id={$i['viewpoint_id']} AND (`price` IN (SELECT MIN(price) FROM `" . $ticket->getTableName() . "` WHERE viewpoint_id={$i['viewpoint_id']}))")
					 ->field("price,id")
					 ->find();

			//echo M()->getLastSql();exit;
			//限制酒店个数并加入酒店图片
			if (count($picinfo[$view[$key]["city_id"]]) < 11) {
				$picinfo[$view[$key]["city_id"]][$key] = $view[$key];
				$picinfo[$view[$key]["city_id"]][$key]["picpath"] = $pic["picpath"];
				$picinfo[$view[$key]["city_id"]][$key]["price"] = ($price["price"] != null) ? $price["price"] : 0;
				$picinfo[$view[$key]["city_id"]][$key]["ticket_id"] = $price["id"];
				$picinfo[$view[$key]["city_id"]]["city"] = $view[$key]["area"];
			}
		};
		//dump($picinfo);
		$view_list = M()->table($viewpoint)
				 ->join(M("Recommend")->getTableName() . " recommend ON viewpoint.id = recommend.obj_id")
				 ->join(M("CityBelong")->getTableName() . " cityb ON viewpoint.city_id = cityb.cid")
				 ->join(M("Area")->getTableName() . " area ON cityb.cid = area.id")
				 ->where("viewpoint.status = 0 AND cityb.isshow = 1 AND cityb.types='Viewpoint' AND recommend.type = 'Viewpoint' AND recommend.rec_type = 2 AND recommend.status = 1")
				 ->field("cityb.id as city_id, viewpoint.id as viewpoint_id, viewpoint.names as viewpoint_name, area.names as area")
				 ->order("viewpoint.sort desc")
				 //->limit($setting)
				 ->select();
		//echo M()->getLastSql();exit;
		foreach ($view_list as $key => $i) {
			//酒店最低价
			$price = $ticket->where("viewpoint_id={$i['viewpoint_id']} AND (`price` IN (SELECT MIN(price) FROM `" . $ticket->getTableName() . "` WHERE viewpoint_id={$i['viewpoint_id']}))")
					 ->field("price,id")
					 ->find();
			//echo $ticket->getLastSql();exit;
			//限制景点个数并加入景点图片
			if (count($viewinfo[$view_list[$key]["city_id"]]) < 9) {
				$viewinfo[$view_list[$key]["city_id"]][$key] = $view[$key];
				$viewinfo[$view_list[$key]["city_id"]][$key]['viewpoint_name'] = $view[$key]['viewpoint_name'];
				$viewinfo[$view_list[$key]["city_id"]][$key]["price"] = ($price["price"] != null) ? $price["price"] : 0;
				$viewinfo[$view_list[$key]["city_id"]][$key]["ticket_id"] = $price["id"];
				$viewinfo[$view_list[$key]["city_id"]]["city"] = $view[$key]["area"];
			}
		}
		//dump($viewinfo);
		//滚动图片
		$view = M()->table($viewpoint)
				 ->join(M("Recommend")->getTableName() . " recommend ON viewpoint.id = recommend.obj_id")
				 ->where("viewpoint.status = 0 AND recommend.type = 'Viewpoint' AND recommend.rec_type = 1 AND recommend.status = 1")
				 ->field("viewpoint.id as viewpoint_id, viewpoint.names as viewpoint_name")
				 ->order("viewpoint.sort desc")
				 ->limit(6)
				 ->select();
		//echo M()->getLastSql();exit;
		//dump($view);
		$picinfos = array();
		foreach ($view as $key => $i) {
			//景点图片
			$pic = $pics->where("viewpoint_id={$i['viewpoint_id']} AND (`istitlepage` = 1 OR (`id` IN (SELECT MAX(id) FROM `" . $pics->getTableName() . "` WHERE viewpoint_id={$i['viewpoint_id']})))")
					 ->field("picpath")
					 ->find();
			//echo $pics->getLastSql();exit;
			//dump($pic);
			//酒店最低价
			$price = $ticket->where("viewpoint_id={$i['viewpoint_id']} AND (`price` IN (SELECT MIN(price) FROM `" . $ticket->getTableName() . "` WHERE viewpoint_id={$i['viewpoint_id']}))")
					 ->field("price")
					 ->find();
			//echo $ticket->getLastSql();exit;
			//dump($price);
			//限制酒店个数并加入酒店图片
			if (count($picinfos[$view[$key]["city_id"]]) < 6) {
				$picinfos[$view[$key]["city_id"]][$key] = $view[$key];
				$picinfos[$view[$key]["city_id"]][$key]["picpath"] = $pic["picpath"];
				$picinfos[$view[$key]["city_id"]][$key]["price"] = ($price["price"] != null) ? $price["price"] : 0;
				$picinfos[$view[$key]["city_id"]][$key]["ticket_id"] = $price["id"];
			}
		}//dump($picinfos);


		$this->assign("order", $order);
		$this->assign("picinfos", $picinfos);
		$this->assign("picinfo", $picinfo);
		$this->assign("viewinfo", $viewinfo);
		$this->assign("impr", $impr);

		$this->display();
	}

	public function init_function() {

		function url_params($params) {
			$url_params = array();
			foreach ($params as $k => $v) {
				$url_params[] = urlencode($k) . "=" . urlencode($v);
			}
			return join("&", $url_params);
		}

		function get_special_info($viewid = "0") {
			static $view_info = null;
			if ($view_info === null)
				$view_info = M("viewpoint");
			$content = $view_info->where("id=$viewid")->getField("view_info");
			//echo $view_info->getLastSql();exit;
			return preg_replace("/\<.*?\>/", "", $content);
		}

		function get_titlepage($lineid = "0", $type = "pic_small") {
			static $line_pic = null;
			if ($line_pic === null)
				$line_pic = M("line_pic");
			$pic_path = $line_pic->where("line_id=$lineid")->order("istitlepage")->getField($type);
			return $pic_path;
		}

		function get_tuan($lineid = "0") {
			static $line_price = null;
			if ($line_price === null)
				$line_price = M("line_price");

			if ($line_price->where("line_id=$lineid and price_type=4")->count())
				return "天天发团";

			$week = $line_price->where("line_id=$lineid and price_type=3")->order("price_date")->getField("id,price_date");
			if (count($week) == 7)
				return "天天发团";

			$stage = $line_price->where("line_id=$lineid and price_type=2 and from_unixtime(price_date_end,'%Y%m%d')>= from_unixtime(UNIX_TIMESTAMP(),'%Y%m%d')")->field("id,price_date,price_date_end")->select();
			$day = $line_price->where("line_id=$lineid and price_type=1 and from_unixtime(price_date,'%Y%m%d')>= from_unixtime(UNIX_TIMESTAMP(),'%Y%m%d')")->getField("id,price_date");
			$i = 1;
			foreach ($day as $d) {
				if ($i = 5)
					break;
				$str.=date("Y-m-d,", $d);
				$i++;
			}
			foreach ($stage as $sd) {
				if ($i = 5)
					break;
				$str.=date("Y-m-d至", $sd["price_date"]) . date("Y-m-d,", $sd["price_date_end"]);
				$i++;
			}
			$wed = array("1" => "二", "1" => "三", "1" => "四", "1" => "五", "1" => "六", "1" => "日",);
			foreach ($week as $wd) {
				if ($i = 5)
					break;
				$str.="每周" . $wed[$wd] . ",";
				$i++;
			}
			$str = rtrim($str, ",") . "等";
			return $str;
		}

	}

	public function filter() {



		$start_id = $this->tVar["currentCity"]["id"];
		$type_select = $this->type_select;
		$where["city_id"] = $start_id;
		if (($_GET['cityName'])) {
			$areas = M("area")->getTableName() . " areas";
			$city = M()->table($areas)
					 ->field("id")
					 ->where("names like '" . $_GET['cityName'] . "%'")
					 ->find();

			$where['city_id'] = $city['id'];
		}
		$where["view_type"] = $type_select;
		if ($type_select == -1) {
			unset($where['view_type']);
		}
		$viewpoint_type = M("viewpoint_type")->getTableName() . " type";
		$ticket = M("viewpoint_ticket")->getTableName() . " ticket";
		$viewpoint = M("viewpoint")->getTableName() . " viewpoint";
		$orders = M("viewpoint_order")->getTableName() . " orders";
		//热门目的地推荐
		$hotest_targets = M()->table($orders)
				 ->join($ticket . " on orders.ticket_id=ticket.id")
				 ->join($viewpoint . " on viewpoint.id=ticket.viewpoint_id")
				 ->field("orders.*,viewpoint.names,SUM(ticket_num) as num,ticket.price")
				 ->where("city_id =" . $start_id)
				 ->group("ticket_id")
				 ->order("num desc")
				 ->limit(6)
				 ->select();

		//$this->hot_target();
		//玩法列表
		$zppo = M()->table($viewpoint_type)->join($viewpoint . " on viewpoint.view_type=type.id")->where("viewpoint.city_id='" . $where['city_id'] . "'")->order("type.sort")->getField("type.id,type.names");
		//echo M()->getLastSql();exit;
		//出游天数        //交通信息
		$goal_name = array(); //目的地
		if (!$zppo) {
			$zppo[0] = 0;
		}
		$search = array();
		$no_search = array();
		$query = array(
			 "zppo" => isset($zppo[$_GET["zppo"]]) ? $_GET["zppo"] : 0, //玩法筛选            
			 "traffic" => isset($line_traffic[$_GET["traffic"]]) ? $_GET["traffic"] : 0, //出游天数
		);
		$titles = array("zppo" => "景点主题", "price" => "价格", "goal" => "主题城市");

		if ($query["zppo"] > 0) {
			$goal = M("viewpoint")->where("view_type=" . $query["zppo"])->order("sort")->getField("city_id,id");
			//echo M()->getLastSql();

			if (!$goal) {
				$goal[0] = 0;
			}
			$goal_name = M("area")->where("id in(" . join(",", array_keys($goal)) . ")")->order("sort")->getField("id,names");
			$search["zppo"] = array($query["zppo"], $zppo[$query["zppo"]]);
			$query["goal"] = isset($goal[$_GET["goal"]]) ? $_GET["goal"] : 0; //玩法筛选             
			$where["view_type"] = array(
				 array('like', $query["zppo"]),
			);
			//dump($where);
		} else {
			$no_search["zppo"] = $zppo;
			$query["goal"] = 0;
		}
		if ($query["goal"] > 0) {
			$where["city_id"] = array(
				 array('like', $query["goal"]),
			);
			$search["goal"] = array($query["goal"], $goal_name[$query["goal"]]);
		} else {
			$no_search["goal"] = $goal_name;
		}
		foreach ($query as $qk => $qv) {
			if ($qv == 0 && isset($_GET[$qk])) {
				unset($_GET[$qk]);
			}
		}

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
			$where["ticket.price"] = array("elt", $mat[1]);
		} elseif (preg_match("/^([1-9]\d{2,})\_([1-9]\d{2,})$/", $_GET['price'], $mat)) {
			$where["ticket.price"] = array("between", array($mat[1], $mat[2]));
		} elseif (preg_match("/^([1-9]\d{2,})\_$/", $_GET['price'], $mat)) {
			$where["ticket.price"] = array("egt", $mat[1]);
		} else {
			$where["ticket.price"] = array("gt", 0);
		}

		$unix_time = time();
		$week_date = date("w");
		//$price_sql = "price_type=4 or (price_type=3 and '$week_date'=price_date) or (price_type=2 and '$unix_time'>price_date and '$unix_time'<price_date_end) or (price_type=1 and from_unixtime(price_date,'%Y%m%d')= from_unixtime(UNIX_TIMESTAMP(),'%Y%m%d'))";
		$view = M("viewpoint")->getTableName() . " view";
		$pic = M("viewpoint_pic")->getTableName() . " pic";
		$table = M()->table($view)
				 ->join($ticket . " on view.id=ticket.viewpoint_id")
				 ->join($orders . " on orders.ticket_id=ticket.id")
				 ->field("view.*,viewpoint_id,begin_time,end_time,price,sum(orders.ticket_num) as num")
				 ->order("price")
				 ->group("id")
				 ->select(false);
		//dump($table);

		$count = M()->table($table . " as tmp_table")->where($where)->where($price_sql)->group("viewpoint_id")->field("viewpoint_id")->select();


		//echo M()->getLastSql();
		$p = $this->pagebar(count($count));
		if (($_GET['viewName'])) {
			$lists = M()->table($table . " as tmp_table")->join($viewpoint_type . " on tmp_table.view_type=type.id")
							  ->join($ticket . " on tmp_table.id=ticket.viewpoint_id")
							  ->join($pic . " on tmp_table.id=pic.viewpoint_id")
							  ->where("tmp_table.names like '%" . $_GET['viewName'] . "%'")
							  ->group("tmp_table.id")
							  ->field("tmp_table.*,type.names type_name,ticket.price price,pic.picpath pic_path")->select();
		} else {
			$lists = M()->table($table . " as tmp_table")
							  ->join($viewpoint_type . " on tmp_table.view_type=type.id")
							  ->join($ticket . " on tmp_table.id=ticket.viewpoint_id")
							  ->join($pic . " on tmp_table.id=pic.viewpoint_id")
							  ->where($where)->group("tmp_table.id")->page($p)->order($this->sort_list())
							  ->field("tmp_table.*,type.names type_name,ticket.price price,pic.picpath pic_path")->select();
		}
		//echo M()->getLastSql();
		//dump($lists);
		$this->assign("lists", $lists);
		if (isset($_GET["_URL_"]))
			unset($_GET["_URL_"]);
		$base_param = $_GET;
		unset($base_param["type"], $base_param["current_city"]);



		$this->assign("hotest_targets", $hotest_targets);

		$this->assign("search", $search);
		$this->assign("no_search", $no_search);
		$this->assign("search_lists", $assign);
		$this->assign("titles", $titles);
		$this->assign("base_param", $base_param);
		$this->assign("base_url", U("filter", array("type" => $_GET["type"], "current_city" => $_GET["current_city"])));
		$this->init_function();
		C('TOKEN_ON', false);
		$this->display();
	}

	public function hot_target() {
		$start_id = $this->tVar["currentCity"]["id"];
		$type_select = $this->type_select;
		$table_target = M('line_target')->getTableName() . " target";
		$table_target_topic = M('line_target_topic')->getTableName() . " topic";

		$target_list = M()->table($table_target)->join("$table_target_topic on topic.id = target.topic_id ")->order("travel_num desc,topic.id desc")->where("")->limit("0,5")->select();
		//dump($target_list);
		$line = M('line')->select();
		$targetnum = array();
		foreach ($line as $k => $v) {
			$target_arr = explode(",", $v['target']);
			foreach ($target_arr as $sv) {
				$tar_arr[$v['id']][] = $sv;
				if (!in_array($sv, $targetnum)) {
					$targetnum["$sv"] = M('line_target')->where("id = '$sv'")->getfield('travel_num');
				}
			}
		}
		rsort($targetnum);
		$this->assign("target_list", $target_list);
	}

	protected function sort_list() {
		switch ($_GET['sort']) {
			case "price_desc":
				$order = "ticket.price desc";
				break;
			case "price_asc":
				$order = "ticket.price asc";
				break;
			case "hits_desc":
				$order = "hits asc";
				break;
			case "num_desc":
				$order = "num desc";
				break;
			case "num_asc":
				$order = "num asc";
				break;
			default:
				$order = "id desc";
		}
		$this->assign("lists_sort", $_GET['sort']);
		return $order;
	}

	public function detail() {
		$id = $_GET["id"] ? intval($_GET["id"]) : 0;
                $uid = $_SESSION['user_id'];
		$viewpoint_table = M('viewpoint')->getTableName() . " viewpoint";
		$fit_person_table = M('fit_person')->getTableName() . " person";
		$viewpoint_ticket = M('viewpoint_ticket')->getTableName() . " ticket";
		$viewpoint_pic = M('viewpoint_pic')->getTableName() . " pic";
		$viewpoint_type = M('viewpoint_type')->getTableName() . " type";
                $viewpoint_collection = M('viewpoint_collection')->getTableName() . " coll";
		//景点信息
		$info = M()->table($viewpoint_table)->where("id='$id'")->find();
                $colls = M()->table($viewpoint_collection)->where("viewpoint_id='".$id."' and user_id ='".$uid."'")->find();
                //echo M()->getLastSql();exit;
		if ($info['position'] != NULL) {
			$position = explode(",", $info['position']);
			$info['location_x'] = $position[0];
			$info['location_y'] = $position[1];
			//dump($position);
		}

		$showpic = M()->table($viewpoint_pic)->where("viewpoint_id='" .$info['id']."'")->order("istitlepage desc")->find();

		//适合人群
		$tmp_person = explode(",", $info['fit_person']);
		foreach ($tmp_person as $v) {
			$list = M()->table($fit_person_table)->where("id='$v'")->getfield('names');
			$person_list[] = $list;
		}
		//景点类型
		$view_type = M()->table($viewpoint_type)
						  ->join($viewpoint_table . " on viewpoint.view_type=type.id")
						  ->field("type.names")
						  ->where("viewpoint.id='{$info['id']}'")->select();
		$type[] = $view_type[0]['names'];

		//门票类型
		$ticket = M()->table($viewpoint_ticket)->where("viewpoint_id='{$info['id']}'")->select();
		//景点图片
		$pic = M()->table($viewpoint_pic)->where("viewpoint_id='{$info['id']}'")->select();

		//景点点评
		$this->viewpoint_impr($id);

		//线路问答
		$this->viewpoint_que($id);

		$this->assign("ticket", $ticket);
		$this->assign("pic", $pic);
                $this->assign("colls", $colls);
		$this->assign("show", $showpic);
		$this->assign("type", $type);
		$this->assign("person_list", $person_list);
		$this->assign("viewpoint", $info);
		$this->display();
	}

	//景点点评
	protected function viewpoint_impr($id) {
		$viewpoint_order = M("viewpoint_order")->getTableName() . " orders";
		$viewpoint_impr = M("viewpoint_impr")->getTableName() . " impr";
		$viewpoint_ticket = M("viewpoint_ticket")->getTableName() . " ticket";
		$viewpoint = M("viewpoint")->getTableName() . " view";
		$M = M();
		$impr_lists = $M->table($viewpoint_impr)->join($viewpoint_order . " on impr.user_id=orders.user_id")
						  ->join($viewpoint_ticket . " on orders.ticket_id=ticket.id")
						  ->join($viewpoint . " on view.id=ticket.viewpoint_id")
						  ->field("impr.*,ticket.award_price as award_price")
						  ->where("impr.view_id='" . $id . "'")
						  ->group("impr.id")
						  ->order("impr.publish_time desc")->select();
		//echo $M->getLastSql();exit;
		$counts = $M->table($viewpoint_impr)->join($viewpoint_order . " on impr.user_id=orders.user_id")
						  ->field("count(*) as tmp_count,avg(satisfaction) as avg_satisfaction,avg(decoration) as avg_decoration,avg(goods) as avg_goods,avg(traffic) as avg_traffic,avg(hygiene) as avg_hygiene")
						  ->where("impr.view_id='" . $id . "'")->find();
		$count = $counts['avg_traffic'] + $counts['avg_decoration'] + $counts['avg_goods'] + $counts['avg_hygiene'];
		$grade["points"] = round($count / 80, 1);
		$grade["mod"] = $count % 80;
		$stars = null;
		for ($i = 0; $i < 5; $i++) {
			if ($i < intval($grade["points"])) {
				$stars .="<img src='../Public/images/1.png' width='22' height='21' border='0' />";
			} elseif ($i >= intval($grade["points"]) && $grade["mod"] >= 40) {
				$stars .="<img src='../Public/images/0.5.png' width='22' height='21' border='0' />";
				$grade["mod"] = 0;
			} else {
				$stars .="<img src='../Public/images/0.png' width='22' height='21' border='0' />";
			}
		}

		//dump($grade);
		//echo $M->getLastSql();exit;
		function impr_detail($val) {
			$details = array("1" => "差", 2 => "一般", 3 => "好", 4 => "很好", 5 => "非常好");
			return $details[$val];
		}

		function impr_list($impr) {
			$com_impr = M("comm_impr")->where("id in($impr)")->select();
			foreach ($com_impr as $impr) {
				$color = $impr["color"] ? $impr["color"] : "blue";
				$imprs.="<li class=\"li {$color}\">{$impr["names"]}</li>";
			}
			return $imprs;
		}

		$this->assign("grade", $grade["points"]);
		$this->assign("stars", $stars);
		$this->assign("impr_lists", $impr_lists);
		$this->assign("impr_counts", $counts);
	}

	protected function viewpoint_que($id) {
		$viewpoint_que = M("viewpoint_que");
		$lists = $viewpoint_que->where("view_id=$id")->order("id desc")->select();
		//echo $viewpoint_que->getLastSql();exit;
		$this->assign("viewpoint_que", $lists);
	}

	public function order() {
		// 判断用户是否已经登录
		if (!(isset($_SESSION["user_id"]) && M("User")->where("id={$_SESSION["user_id"]}")->count() > 0)) {
			$this->redirect('login/index');
		}
		$ticket = $_GET['id'] ? intval($_GET['id']) : 0;

		$viewpoint_table = M('viewpoint')->getTableName() . " viewpoint";
		$viewpoint_ticket_table = M('viewpoint_ticket')->getTableName() . " ticket";
		$viewpoint_order = M('viewpoint_order');
		$viewpoint_order_ticket = M('ViewpointOrderticket');

		if (!$_POST) {
			$ticket_list = M()->table($viewpoint_ticket_table)
					 ->join($viewpoint_table . " ON ticket.viewpoint_id = viewpoint.id")
					 ->where("ticket.id='$ticket'")
					 ->field("ticket.id, ticket.award_price, ticket.viewpoint_id, viewpoint.names as viewpoint_names, viewpoint.city_id as viewpoint_cityid")
					 ->find();
			$tickets = M()->table($viewpoint_ticket_table)
					 ->where("viewpoint_id = {$ticket_list['viewpoint_id']}")
					 ->order("field(id, {$ticket_list['id']}) desc")
					 ->select();
                        $ticket_num = count($tickets);
			$userinfo = M("User")->where("id={$_SESSION['user_id']}")->find();
			$this->assign("ticket_list", $ticket_list);
			$this->assign("tickets", $tickets);
                        $this->assign("ticket_num", $ticket_num);
			$this->assign("user", $userinfo);
			$this->display();
		} else {
			if (isset($_POST["ticket"])) {
				$ticket = array();
				foreach ($_POST["ticket"] as $key => $t)
					if ($t["ticket_count"] != "" && $t["ticket_count"] != null)
						$ticket[$key] = $t;
				sort($ticket);
				//验证代金券
				if (isset($_POST["serial_num"]) && $_POST["serial_num"] != "") {
					$serial = M("CashCoupon")->where("serial_num='{$_POST["serial_num"]}' AND status=0 AND expire_time>=" . time())->find();
					if (!$serial) {
						$this->error("代金券编号无效！");
						exit;
					} else {
						$data["coupon_num"] = $_POST["serial_num"];
						$data["coupon_money"] = $serial["coupon_value"];
						$this->use_serial($_POST["serial_num"], $_SESSION["user_id"]);
					}
				}
				//验证奖金数额
				if (isset($_POST["use_award"]) && $_POST["use_award"] != "") {
					$user_amount = M("User")->where("id={$_SESSION['user_id']}")->getField("amount");
					if ($_POST["use_award"] > $user_amount) {
						$this->error("您的奖金余额不足！");
						exit;
					} else {
						$this->use_award($_POST["use_award"], $_SESSION['user_id']);
					}
				}
				//生成订单号
				do {
					$data['serial_num'] = "V" . date("ymdHi") . rand_string(5, 1);
				} while ($viewpoint_order->where("code='$code'")->find());
				//将联系人信息写入数据库
				$ticket_contactor = M("TicketContactor");
				$ticket_contactor->create();
				$ticket_contactor->user_id = $_SESSION['user_id'];
				$ticket_contactor->status = 0;
				$data['contactor_id'] = $ticket_contactor->add();
                                //echo $ticket_contactor->getLastSql();exit;
				//统计所有门票总数
				$data['ticket_num'] = 0;
				foreach ($ticket as $i) {
					$data['ticket_num'] += $i['ticket_count'];
				}
				$data['all_money'] = $_POST['all_money'];
				$data['must_pay'] = $_POST['must_pay'];
				$data['use_award'] = $_POST['use_award'];
				$data['user_id'] = $data['status_change_user'] = $_SESSION['user_id'];
				$data['special_requirement'] = $_POST["special_want"];
				$data['create_time'] = $data['status_change_time'] = time();
				$data['status'] = 0;
				//写入订单表
				$order_id = $viewpoint_order->add($data);
				//写入订单门票统计表
				$ticket_ids = array();
				foreach ($ticket as $key => $i) {
					$ticket_ids[$key] = $viewpoint_order_ticket->add(array(
						 "order_id" => $order_id,
						 "ticket_id" => $i['ticket_id'],
						 "ticket_count" => $i['ticket_count']
					));
				}
				$viewpoint_order->where("id=$order_id")->save(array("ticket_id" => join(",", $ticket_ids)));
				//订单提交完毕，展示成功页面
				//$this->redirect("order_untreated", array("order" => $viewpoint_order->where("id=$order_id")->getField("serial_num")));
				$this->redirect("order_success", array("id" => $order_id));
			} else {
				$this->redirect('order');
			}
		}
	}

	//未付款的订单展示页面
	public function order_untreated() {
		$viewpoint_order = D('ViewpointOrder');
		if ($viewpoint_order->where("serial_num='{$_GET["order"]}' AND user_id={$_SESSION['user_id']}")->count() > 0) {
			$this->assign("usinfo", $viewpoint_order->get_order_untreatedDATA($_GET["order"]));
			$this->display();
		} else {
			$this->redirect("viewpoint");
		}
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

	public function order_success() {
		$table_viewpoint_order = M('viewpoint_order')->getTableName() . " orders";
		$table_viewpoint = M('viewpoint')->getTableName() . " viewpoint";
		$table_viewpoint_ticket = M('viewpoint_ticket')->getTableName() . " ticket";
		$tabke_ticket_contactor = M('ticket_contactor')->getTableName() . " contactor";
		$order_id = $_GET['id'];
		$list = D('ViewpointOrder')->get_order_treatedDATA($order_id);
		//echo D()->getLastSql();
		//统计票数
		$tik_count = M('ViewpointOrderticket')->where("order_id=$order_id")->count();
		$deal = false;
		$deal_type = 0;
		if ($tik_count == 1) {
			$deal = true;
			$tmp = M()->table(M('ViewpointOrderticket')->getTableName() . " odtikcet")
					 ->join($table_viewpoint_ticket . " ON ticket.id=odtikcet.ticket_id")
					 ->where("odtikcet.order_id=$order_id")
					 ->field("ticket.deal_type")
					 ->find();
			$deal_type = intval($tmp["deal_type"]);
		}
		$list['pri_card'] = D('cash_coupon')->show_coupon($list['coupon_num']);
		$list['amount'] = $list['all_money'];
		$list['should_money'] = $list['must_pay'];
		//dump($list);exit;
		if (empty($list)) {
			$this->error("页面出错！");
		}
		$paymentapi = A("paymentapi");
		$banks = $paymentapi->get_banks('wangyin');

		$processed = 2;
		if ($deal) {
			if ($deal_type == 0)
				$processed = 0;
			elseif ($deal_type == 1)
				$processed = 1;
		} else {
			if ($list['status'] == 0)
				$processed = 0;
			elseif ($list['status'] == 1)
				$processed = 1;
		}

		if ($processed == 0) {
			$this->assign("usinfo", $list);
			$this->display('order_untreated');
			exit;
		} elseif ($processed == 1) {
			$this->assign("usinfo", $list);
			$this->assign("banks", $banks);
			$this->assign("amount", M('user')->where("id='{$_SESSION['user_id']}'")->getfield('amount'));
			$this->display('order_processed');
			exit;
		} else {
			$this->error('订单错误!');
		}
	}

	/**
	 * 有效使用并扣除相应奖金<br/>
	 */
	private function use_award($bonus_money, $user_id) {
		$user = M("User");
		$user->where("id=$user_id")->setDec("award", $bonus_money);
	}

	public function ajax_award() {
		$code = $_POST['code'];
		$time = time();
		$cash_coupon = M('cash_coupon');
		//$count = $cash_coupon->where("serial_num='$code' AND ((expire_time>$time) OR (expire_time='0')) AND status='0'")->find();
		$count = $cash_coupon->select();

		if ($count) {
			$this->ajaxReturn($count, "不为空", 1);
			exit;
		} else {
			$this->ajaxReturn(0, "号码不存在或者已经使用!", 0);
		}
	}

	public function index() {
		$viewpoint_order = M('viewpoint_order')->getTableName() . "";
		$viewpoint_ticket = M("viewpoint_ticket")->getTableName() . " ticket";
		$viewpoint = M("viewpoint")->getTableName() . " viewpoint";
		$imprs = M("viewpoint_impr")->getTableName() . " impr";
		$types = M("viewpoint_type")->getTableName() . " type";


		$start_id = $this->tVar["currentCity"]["id"];
		$type_select = $this->type_select;
		$where2["city_id"] = $start_id;
		$this->init_function();
		/* $table_line = M('line')->getTableName() . " line";
		  $table_topic = M('line_target_topic')->getTableName() . " topic";
		  $table_city = M('city_belong')->getTableName() . " city";
		  $table_area = M('area')->getTableName() . " area";
		  $table_type = M('line_type')->getTableName() . " type";
		  $table_target = M('line_target')->getTableName() . " target";
		  $table_price = M('line_price')->getTableName() . " price"; */

		$impr = M()->table($imprs)
				 ->join($viewpoint . " ON viewpoint.id = impr.view_id")
				 ->join("RIGHT JOIN " . M("User")->getTableName() . " user ON impr.user_id = user.id")
				 ->where("impr.status=2 AND viewpoint.status = 0 ")
				 ->field("viewpoint.id as viewpointid, user.username, viewpoint.names, impr.*")
				 ->order("field(viewpoint.city_id, $start_id) desc ,impr.id desc")
				 ->limit("0,6")
				 ->select();

		$type = M()->table($types)
				 ->join($viewpoint . " ON viewpoint.view_type = type.id")
				 ->where("viewpoint.status = 0")
				 ->field("viewpoint.id as viewpointid,type.names,type.id as id")
				 ->group("type.names")
				 ->order("field(viewpoint.city_id, $start_id) desc ,viewpoint.id desc")
				 ->limit("0,12")
				 ->select();

		//echo M()->getLastSql();exit;
		//dump($impr);
		$this->hot_pic_list();


		//热门目的地推荐
		$hotest_targets = M()->table($viewpoint_order)
				 ->join($viewpoint_ticket . " on ticket_id=ticket.id")
				 ->join($viewpoint . " on viewpoint.id=ticket.viewpoint_id")
				 ->field("$viewpoint_order.*,viewpoint.names,SUM(ticket_num) as num,ticket.price")
				 ->group("ticket_id")
				 ->order("field(viewpoint.city_id, $start_id) desc ,num desc")
				 ->limit(6)
				 ->select();
		//echo M()->getLastSql();
		//热门线路
		$where["property"] = '4';
		$hot_view = $this->hot_viewpoint($where, 3);
		//dump($hot_view);
		$this->assign("hot_view", $hot_view);
		//特价线路
		$where["property"] = '2';
		$special_view = $this->special_view($where, 4);
		$this->assign("special_view", $special_view);
		//新品线路
		$where["property"] = '3';
		$new_view = $this->new_view($where, 3);
		$this->assign("new_view", $new_view);
		$this->assign("hotest_targets", $hotest_targets);
		$this->assign("impr", $impr);
		$this->assign("type", $type);
		$this->assign("base_url", U("travel_lists", array("type" => $_GET["type"], "current_city" => $_GET["current_city"])));
		$this->display();
	}

	//热门图片展播
	public function hot_pic_list() {
		$start_city = $this->tVar["currentCity"]["id"];
		$hot_view = M('viewpoint')->field("id")->where("city_id='" . $start_city . "'")->order("hits desc")->limit("5")->select();
		//echo M()->getLastSql();exit;
		$this->assign("view_img_id", $hot_view);
	}

	private function hot_viewpoint($where1, $num = '5') {
		$start_id = $this->tVar["currentCity"]["id"];
		$type_select = $this->type_select;
		//热门景点
		$where2["city_id"] = $start_id;
		//$where2["target_type"] = $type_select;
		$imprs = M("viewpoint_impr")->getTableName() . " impr";
		$view = M("viewpoint")->getTableName() . " view";
		$price = M("viewpoint_ticket");
		$lists = M()->table($view)->where($where1)->group("id")->order("field(view.city_id, $start_id) desc ,id desc")->limit("$num")->select();
		foreach ($lists as $key => $i) {

			//景点最低价
			$prices = $price->where("viewpoint_id={$i['id']} AND (`price` IN (SELECT MIN(price) FROM `" . $price->getTableName() . "` WHERE viewpoint_id={$i['id']}))")
					 ->field("price,id")
					 ->find();

			$impr = M()->table($imprs)->where("view_id={$i['id']}")
					 ->field("impr.satisfaction,impr.id")
					 ->find();

			$count = M()->table($imprs)->where("view_id={$i['id']}")
					 ->count();
			//echo M()->getLastSql();exit;
			$lists[$key]["price"] = ($prices["price"] != null) ? $prices["price"] : 0;
			$lists[$key]["satisfaction"] = ($impr["satisfaction"] != null) ? $impr["satisfaction"] : 0;
			$lists[$key]["count"] = ($count["tp_count"] != null) ? $count["tp_count"] : 0;
		};
		//dump($lists);
		return $lists;
	}

	private function special_view($where1, $num = '5') {
		$start_id = $this->tVar["currentCity"]["id"];
		$type_select = $this->type_select;
		//热门线路
		$where2["city_id"] = $start_id;
		//$where2["target_type"] = $type_select;
		//按路线查找

		$view = M("viewpoint")->getTableName() . " view";
		$price = M("viewpoint_ticket");
		$lists = M()->table($view)->where($where1)->group("id")->order("field(view.city_id, $start_id) desc ,id desc")->limit("$num")->select();
		foreach ($lists as $key => $i) {

			//酒店最低价
			$prices = $price->where("viewpoint_id={$i['id']} AND (`price` IN (SELECT MIN(price) FROM `" . $price->getTableName() . "` WHERE viewpoint_id={$i['id']}))")
					 ->field("price,id")
					 ->find();


			//echo M()->getLastSql();exit;
			//限制酒店个数并加入酒店图
			$lists[$key]["price"] = ($prices["price"] != null) ? $prices["price"] : 0;
		};
		//dump($lists);
		return $lists;
	}

	private function new_view($where1, $num = '5') {
		$start_id = $this->tVar["currentCity"]["id"];
		$type_select = $this->type_select;
		//热门线路
		$where2["city_id"] = $start_id;
		//$where2["target_type"] = $type_select;
		//按路线查找
		$imprs = M("viewpoint_impr")->getTableName() . " impr";
		$view = M("viewpoint")->getTableName() . " view";
		$price = M("viewpoint_ticket");
		$lists = M()->table($view)->join(M("viewpoint_ticket")->getTableName() . " ticket ON view.id = ticket.viewpoint_id")->where($where1)
						  ->field("view.*")
						  ->group("view.id")->order("ticket.addtime desc")->order("field(view.city_id, $start_id) desc ,id desc")->limit("$num")->select();
		//echo M()->getLastSql();exit;
		foreach ($lists as $key => $i) {

			//酒店最低价
			$prices = $price->where("viewpoint_id={$i['id']} AND (`price` IN (SELECT MIN(price) FROM `" . $price->getTableName() . "` WHERE viewpoint_id={$i['id']}))")
					 ->field("price,id")
					 ->find();

			$impr = M()->table($imprs)->where("view_id={$i['id']}")
					 ->field("impr.satisfaction,impr.id")
					 ->find();

			$count = M()->table($imprs)->where("view_id={$i['id']}")
					 ->count();
			//echo M()->getLastSql();exit;
			//限制酒店个数并加入酒店图
			$lists[$key]["price"] = ($prices["price"] != null) ? $prices["price"] : 0;
			$lists[$key]["satisfaction"] = ($impr["satisfaction"] != null) ? $impr["satisfaction"] : 0;
			$lists[$key]["count"] = ($count["tp_count"] != null) ? $count["tp_count"] : 0;
		};
		//dump($lists);
		return $lists;
	}

	public function consult() {
		if (!isset($_SESSION["user_id"])) {
			$this->ajaxReturn(array("info" => "请先登录", "status" => "n"));
			exit;
		}
		if ($_SESSION["verify"] != md5($_POST["verify"])) {
			$this->ajaxReturn(array("info" => "验证码不正确", "status" => "n"));
			exit;
		}
		if (strlen(trim($_POST["question1"])) < 5) {
			$this->ajaxReturn(array("info" => "至少输入5个字符", "status" => "n"));
			exit;
		}
		$id = $this->_get("id");
		$view_que = M("viewpoint_que");
		$publish_time = time() - 300;
		$last_que_count = $view_que->where("view_id=$id and user_id={$_SESSION["user_id"]} and publish_time>$publish_time")->count();
		if ($last_que_count > 0) {
			$this->ajaxReturn(array("info" => "您的操作过于频繁", "status" => "n"));
			exit;
		}
		$viewpoint = M("viewpoint");
		$viewpoint_count = $viewpoint->where("id=$id and status=0")->count();
		if ($viewpoint_count == 0) {
			$this->ajaxReturn(array("info" => "您咨询的景点不存在或已经停用", "status" => "n"));
			exit;
		}
		$data = array(
			 "view_id" => $id,
			 "user_id" => $_SESSION["user_id"],
			 "question1" => trim($_POST["question1"]),
			 "publish_time" => time(),
			 "status" => 1,
			 "sort" => 0,
		);
		$view_que->add($data);
		$public = APP_TMPL_PATH . "Public";
		$publish_time = date("Y-m-d H:i:s", $data["publish_time"]);
		$user_name = get_user($_SESSION["user_id"]);
		$dom = <<<EOF
        <div class="customer">
            <div class="customer_on">
                <div class="customer_on_1">
                   <div class="customer_on_left">
                   <span class="font_blue"><img src="$public/images/att_img_iocn.jpg" width="17" height="17"/>
                      &nbsp;咨询内容：{$data["question1"]}</span>
                   </div>
                   <div class="customer_on_right"><span class="font_brown">{$user_name}&nbsp;{$publish_time}</span></div>
                   <div class="customer_on_left">
                   <span class="font_brown"><img src="$public/images/att_img_iocn2.jpg" width="17" height="17"/>
                   &nbsp;客服回复：请等待回复!</span>
                 </div>
              </div>
            </div>
         </div>
EOF;
		$this->ajaxReturn(array("info" => $dom, "status" => "y"));
	}

	public function validform() {
		$param = $_POST["param"];
		$name = $_POST["name"];
		switch ($name) {
			case"verify":
				if ($_SESSION["verify"] == md5($param)) {
					$ajaxreturn = array("info" => "输入正确", "status" => "y");
				} else {
					$ajaxreturn = array("info" => "输入错误", "status" => "n");
				}
				break;
		}
		$this->ajaxReturn($ajaxreturn);
	}

	public function add_coll() {
		$uid = $_SESSION['user_id'];
		$id = $_GET['id'];

		$viewpoint_collection = M('viewpoint_collection');
		$data['viewpoint_id'] = $id;
		$data['user_id'] = $uid;
		$data['create_time'] = date("Y-m-d H-m-s", time());
		$data['status'] = 1;
		$count = $viewpoint_collection->where("user_id = '$uid' and viewpoint_id='$id'")->count();

		if ($count > 0) {
			$this->ajaxReturn("", "该景点已经收藏", "0");
			exit;
		}
		if (empty($uid)) {
			$this->ajaxReturn("", "用户未登陆", "0");
			exit;
		}
		$viewpoint_collection->add($data);
		$this->ajaxReturn("", "收藏成功!", "1");
		exit;
	}

	public function order_pay() {

		if (!$_POST['order_id']) {
			$this->error("线路选择错误");
		}
		$user = M('user');
		$order_id = $_POST['order_id'];
		$table_line_order = M('line_order')->getTableName() . " line_order";
		$list = M()->table($table_line_order)
				 ->where("id='$order_id' AND user_id='{$_SESSION['user_id']}'")
				 ->find();
		$should_amount = $list['amount'] - $list['used_award'] - $list['pri_card'];
		$earnest = _get_front_money($list['line_id'], $should_amount);

		if ($_POST['psw_on']) {
			$uinfo = $user->where("id ='$id' AND password='" . md5($_POST['password']) . "'")->find();
			if ($uinfo) {
				if ($usinfo['amount'] >= $earnest) {
					$user->where("id ='$id'")->setDec('money', $earnest);
				} else {
					$surplus = $earnest - $usinfo['amount'];
					$user->where("id ='$id'")->setDec('money', $usinfo['amount']);
				}
				//剩余未付金额 $surplus
			} else {
				$this->error("密码输入错误!");
			}
		} else {
			dump($earnest);
		}
	}

}
