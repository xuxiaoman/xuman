<?php

/**
 * Description of travelAction
 *
 * @author Genini
 */
class travelAction extends CommonAction {

    private $type_select;
    private $topic = array(1 => "古镇游", 2 => "山水游", 3 => "海岛游", 4 => "乐园游");
    protected $max_p = 3000;

    public function _initialize() {
        $authentic = array("order", "order_success");
        in_array(ACTION_NAME, $authentic) ? $this->authentic = 1 : $this->authentic = 0;
        parent::_initialize();
        $this->type_select = $_GET["type"] ? intval($_GET["type"]) : -1;
        $this->assign("current", $this->type_select);
    }

    public function travel() {        
        $Line = D('Line');
        $start_id = $this->tVar["currentCity"]["id"];
        $type_select = $this->type_select;
        $this->init_function();

        $this->hot_pic_list();

        //按主题查找目的地
        $this->line_target();

        //热门目的地点评
        $line_comments = $Line->get_line_comments();
        $this->assign("line_comments", $line_comments);
        //热门线路
        $where["property"] = '4';
        $hot_line = $this->travel_price($where, 3);
        $this->assign("hot_line", $hot_line);
        //特价线路
        $where["property"] = '2';
        $special_line = $this->travel_price($where, 4);
        $this->assign("special_line", $special_line);
        //新品线路
        $where["property"] = '3';
        $new_line = $this->travel_price($where, 3);
        $this->assign("new_line", $new_line);
        $this->assign("hotest_targets", $hotest_targets);
        $this->assign("base_url", U("travel_lists", array("type" => $_GET["type"], "current_city" => $_GET["current_city"])));
        $this->display();
    }

    //按主题查找目的地
    public function line_target() {
        $line_topic_type = M("line_topic_type");
        $line_topics = $line_topic_type->where("status=1")->order('sort')->getField("id,names");
        $this->assign("base_url", U("travel_lists", array("type" => $_GET["type"], "current_city" => $_GET["current_city"])));
        $this->assign("line_topics", $line_topics);
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

    private function travel_price($where1, $num = '5') {
        $start_id = $this->tVar["currentCity"]["id"];
        $type_select = $this->type_select;
        //热门线路
        //$where2["city_id"] = $start_id;
        //$where2["target_type"] = $type_select;
        $where2["status"] = '0';
        //按路线查找
        $unix_time = time();
        $week_date = date("w");
        $price_sql = "price_type=4 or (price_type=3 and '$week_date'=price_date) or (price_type=2 and '$unix_time'>price_date and '$unix_time'<price_date_end) or (price_type=1 and from_unixtime(price_date,'%Y%m%d')= from_unixtime(UNIX_TIMESTAMP(),'%Y%m%d'))";
        $line = M("Line")->getTableName() . " line";
        $line_price = M("line_price")->getTableName() . " price";
        $table = M()->table($line)
                ->join($line_price . " on line.id=price.line_id")
                ->field("line.*,line_id,price_type,price_date,	price_date_end,price_adult")
                ->order("price_adult")
                ->select(false);
        $lists = M()->table($table . " as tmp_table1")->where($where2)->where($where1)->where($price_sql)->order("city_id='$start_id' desc,target_type='$type_select'")->group("line_id")->limit("$num")->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['order_num'] = M('line_order')->where("line_id='{$v['line_id']}'")->count();
            $impr = M('line_impr')->getTableName() . " impr";
            $conn = M()->table($impr)->join("jee_line_order orders on impr.order_id=orders.id")
                            ->field("avg(point) as avg_point")
                            ->where("orders.line_id='{$v['line_id']}'")->find();
            $lists[$k]['impr_point'] = $conn['avg_point'];
            $lists[$k]['impr_num'] = M('line_order')->join("RIGHT JOIN $impr on jee_line_order.id=impr.order_id")->where("jee_line_order.line_id='{$v['line_id']}'")->count();
        }
        return $lists;
    }

    //热门图片展播
    public function hot_pic_list() {
        $start_city = $this->tVar["currentCity"]["id"];
        $hot_line = M('line')->field("id")->order("city_id='$start_city',hits desc")->limit("5")->select();
        $this->assign("line_img_id", $hot_line);
    }

    private function order_price($id, $time) {
        //按路线查找
        $unix_time = empty($time) ? time() : $time;
        $week_date = date("w", $unix_time);
        $where['id'] = $id;
        $price_sql = "price_type=4 or (price_type=3 and '$week_date'=price_date) or (price_type=2 and '$unix_time'>=price_date and '$unix_time'<=price_date_end) or (price_type=1 and from_unixtime(price_date,'%Y%m%d')= from_unixtime(UNIX_TIMESTAMP(),'%Y%m%d'))";
        $line = M("Line")->getTableName() . " line";
        $line_price = M("line_price")->getTableName() . " price";
        $table = M()->table($line)
                ->join($line_price . " on line.id=price.line_id")
                ->field("line.*,line_id,price_type,price_date,	price_date_end,price_adult,price_children")
                ->order("price_type")
                ->select(false);
        $lists = M()->table($table . " as tmp_table1")->where($where)->where($price_sql)->group("line_id")->find();
        return $lists;
    }

    public function travel_lists() {
        $start_id = $this->tVar["currentCity"]["id"];
        $type_select = $this->type_select;
        $where["city_id"] = $start_id;
        $where["target_type"] = $type_select;
        $line_topic_type = D("line_topic_type");
        $Line = D("Line");
        //按主题查找目的地
        $this->line_target();
        //热门目的地推荐
        $this->hot_target();
        //热门目的地点评
        $line_comments = $Line->get_line_comments();
        $this->assign("line_comments", $line_comments);
        //线路主题列表
        $line_theme = $line_topic_type->where("status=1")->order("sort")->getField("id,names");
        //目的地列表
        $line_targer = M("line_target");
        $line_targer_table = $line_targer->getTableName() . ' target';
        $area_table = M("area")->getTableName() . ' area';
        $where1['target.start_id'] = $start_id;
        $where1['target.type_id'] = $type_select;
        $goal_name = $line_targer->table($line_targer_table)
                ->join($area_table . ' on target.area_id=area.id')
                ->where($where1)
                ->getField('target.area_id,area.names');

        //出游天数
        $line_days = array("1" => "一日游", "2" => "二日游", "3" => "三日游", "4" => "四日游", "5" => "五日游", "6" => "六日游", "7" => "七日游以上");
        //交通信息
        $line_traffic = array("1" => "大巴", "2" => "火车", "3" => "飞机");

        $search = array();
        $no_search = array();
        $query = array(
            "goal" => isset($goal_name[$_GET["goal"]]) ? $_GET["goal"] : 0, //目的地
            "theme" => isset($line_theme[$_GET["theme"]]) ? $_GET["theme"] : 0, //线路主题
            "day" => isset($line_days[$_GET["day"]]) ? $_GET["day"] : 0, //出游天数
            "traffic" => isset($line_traffic[$_GET["traffic"]]) ? $_GET["traffic"] : 0, //出游天数
        );
        $titles = array("goal" => "目的地", "theme" => "线路主题", "day" => "出游天数", "traffic" => "交通信息");

        if ($query["goal"] > 0) {
            $where["target"] = array(
                array('like', $query["goal"] . ',%'),
                array('like', '%,' . $query["goal"] . ',%'),
                array('like', '%,' . $query["goal"]),
                $query["goal"], 'or'
            );
            $search["goal"] = array($query["goal"], $goal_name[$query["goal"]]);
        } else {
            $no_search["goal"] = $goal_name;
        }

        if ($query["theme"] > 0) {
            $where["target"] = $query["theme"];
            $search["theme"] = array($query["theme"], $line_theme[$query["theme"]]);
        } else {
            $no_search["theme"] = $line_theme;
        }
        if ($query["day"] > 0) {
            $where["trip_days"] = $query["day"] == 7 ? array("egt", 7) : $query["day"];
            $search["day"] = array($query["day"], $line_days[$query["day"]]);
        } else {
            $no_search["day"] = $line_days;
        }
        if ($query["traffic"] > 0) {
            $where["traffic"] = $query["traffic"];
            $search["traffic"] = array($query["traffic"], $line_traffic[$query["traffic"]]);
        } else {
            $no_search["traffic"] = $line_traffic;
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
        } elseif (is_numeric($_GET['max_price'])) {
            $_GET['price'] = "_" . $_GET['max_price'];
            $assign['max_price'] = $_GET['max_price'];
            unset($_GET['min_price'], $_GET['max_price']);
        }
        if (preg_match("/^\_([1-9]\d{2,})$/", $_GET['price'], $mat)) {
            $where["price_adult"] = array("elt", $mat[1]);
        } elseif (preg_match("/^([1-9]\d{2,})\_([1-9]\d{2,})$/", $_GET['price'], $mat)) {
            $where["price_adult"] = array("between", array($mat[1], $mat[2]));
        } elseif (preg_match("/^([1-9]\d{2,})\_$/", $_GET['price'], $mat)) {
            $where["price_adult"] = array("egt", $mat[1]);
        } else {
            $where["price_adult"] = array("gt", 0);
        }

        $where["city_id"] = $start_id;
        $where["target_type"] = $type_select;
        $unix_time = time();
        $week_date = date("w");
        $price_sql = "price_type=4 or (price_type=3 and '$week_date'=price_date) or (price_type=2 and '$unix_time'>price_date and '$unix_time'<price_date_end) or (price_type=1 and from_unixtime(price_date,'%Y%m%d')= from_unixtime(UNIX_TIMESTAMP(),'%Y%m%d'))";
        $line = M("Line")->getTableName() . " line";
        $line_price = M("line_price")->getTableName() . " price";
        $table = M()->table($line)
                ->join($line_price . " on line.id=price.line_id")
                ->field("line.*,line_id,price_type,price_date,	price_date_end,price_adult")
                ->order("price_adult")
                ->select(false);
        $count = M()->table($table . " as tmp_table")->where($where)->where($price_sql)->group("line_id")->field("line_id")->select();

        $p = $this->pagebar(count($count));
        $lists = M()->table($table . " as tmp_table")->where($where)->where($price_sql)->group("line_id")->page($p)->order($this->sort_list())->select();
       
        $this->assign("lists", $lists);
        if (isset($_GET["_URL_"]))
            unset($_GET["_URL_"]);
        $base_param = $_GET;
        unset($base_param["type"], $base_param["current_city"]);

        $this->assign("search", $search);
        $this->assign("no_search", $no_search);
        $this->assign("search_lists", $assign);
        $this->assign("titles", $titles);
        $this->assign("base_param", $base_param);
        $this->assign("base_url", U("travel_lists", array("type" => $_GET["type"], "current_city" => $_GET["current_city"])));
        $this->init_function();
        C('TOKEN_ON', false);
        $this->display();
    }

    protected function sort_list() {
        switch ($_GET['sort']) {
            case "price_desc":
                $order = "price_adult desc";
                break;
            case "price_asc":
                $order = "price_adult asc";
                break;
            case "hits_desc":
                $order = "hits asc";
                break;
            case "day_desc":
                $order = "trip_days desc";
                break;
            case "day_asc":
                $order = "trip_days asc";
                break;
            default:
                $order = "id desc";
        }
        $this->assign("lists_sort", $_GET['sort']);
        return $order;
    }

    public function init_function() {

        function url_params($params) {
            $url_params = array();
            foreach ($params as $k => $v) {
                $url_params[] = urlencode($k) . "=" . urlencode($v);
            }
            return join("&", $url_params);
        }

        function get_special_info($lineid = "0") {
            static $line_info = null;
            if ($line_info === null)
                $line_info = M("line_info");
            $content = $line_info->where("lid=$lineid")->getField("special_info");
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

    public function order() {
        if (!$_POST['order_submit']) {
            $amount = M('user')->where("id='{$_SESSION['user_id']}'")->getfield('award');
            $line_id = $_GET['id'];
            $ke_award = M('line')->where("id='$line_id'")->find();
            $this->travel_price_list($line_id);
            $this->assign("amount", $amount);
            $this->assign("ke_award", $ke_award);
            $this->assign("pos", $_POST);
            $this->display();
        } else {

            if (empty($_SESSION['user_id'])) {
                $this->error("用户没有登录!");
            }
            if (empty($_GET['id'])) {
                $this->error("线路选择出错!");
            }
            $ke_award = M('line')->where("id='{$_GET['id']}'")->getfield('award');
            if ($_POST['used_award'] > $ke_award) {
                $this->error("奖金最多使用{$ke_award}元!");
            }
            $user_award = M('user')->where("id='{$_SESSION['user_id']}'")->getfield('award');
            if ($user_award - $_POST['used_award'] < 0) {
                $this->error("用户奖金余额不足!");
            }
            $uid = $_SESSION['user_id'];
            $line_order = D('line_order');
            $coupon = D('cash_coupon');
            $line = M('line');
            $user = M('user');
            $order_userinfo = M('order_userinfo');
            $income_award = D('income_award');
            $go_time = strtotime($_POST['go_time']);

            $order_price = $this->order_price($_GET['id'], $go_time);
            if ($line_order->create()) {
                do {
                    $code = "L" . date("ymdHi") . rand_string(5, 1);
                } while ($line_order->where("code='$code'")->find());
                $line_order->user_id = $uid;
                $line_order->line_id = $_GET['id'];
                $line_order->code = $code;
                $line_order->used_card = $coupon->change_coupon($_POST['used_card'], $_SESSION['user_id']);
                $line_order->go_city = $order_price['city_id'];
                $line_order->amount = floatval((($order_price['price_adult']) * count($_POST['adult']) + floatval($order_price['price_children']) * count($_POST['child'])) - $line_order->used_award - $coupon->show_coupon($line_order->used_card));
                $line_order->review_award = $order_price['bonus_comm'];
                $line_order->status = $order_price['deal_type'] == '1' ? '1' : '2';
                $line_order->front_money = _get_front_money($_GET['id'], ($line_order->amount));
                $id = $line_order->add();
                $user->where("id='$uid'")->setDec('award', $_POST['used_award']);

                //$income_award->change_award($_SESSION['user_id'], $_POST['used_award'], 0, 0);
                //订单游客信息
                foreach ($_POST['adult'] as $v) {
                    $data['order_id'] = $id;
                    $data['names'] = $v[0];
                    $data['old_type'] = '1';
                    $data['credentials'] = $v[1];
                    $data['content'] = $v[2];
                    $data['type'] = 'LINE';
                    $order_userinfo->add($data);
                };
                foreach ($_POST['child'] as $v) {
                    $data['order_id'] = $id;
                    $data['names'] = $v[0];
                    $data['old_type'] = '2';
                    $data['credentials'] = $v[1];
                    $data['content'] = $v[2];
                    $data['type'] = 'LINE';
                    $order_userinfo->add($data);
                }
                unset($_GET['_URL_']);
                unset($_GET['id']);
                $_GET['order_id'] = $id;
                redirect(U('order_success', $_GET));
            } else {
                $this->error("添加失败!" . M()->getError());
            }
        }
    }

    public function order_ajax_award() {
        $param = $_POST['param'];
        $award = M('user')->where("id='{$_SESSION['user_id']}'")->getfield("award");
        if (!empty($param) && $award > $param) {
            $this->ajaxReturn("", "", "y");
        } else {
            $this->ajaxReturn("", "用户奖金金额不足", "n");
        }
    }

    public function order_success() {
        $table_line_order = M('line_order')->getTableName() . " line_order";
        $table_line = M('line')->getTableName() . " line";
        $order_userinfo = M('order_userinfo');
        $order_id = $_GET['order_id'];
        $list = M()->table($table_line_order)
                ->join("$table_line on line_order.line_id=line.id")
                ->field('line_order.*,line.names')
                ->where("line_order.id='$order_id' AND line_order.user_id='{$_SESSION['user_id']}'")
                ->find();
        $list['pri_card'] = D('cash_coupon')->show_coupon($list['used_card']);
        $list['num'] = count($order_userinfo->where("order_id='$order_id' and type='LINE'")->select());
        $list['earnest'] = $list['front_money'];

        if (empty($list)) {
            $this->error("页面出错！");
        }
        $paymentapi = A("paymentapi");
        $banks = $paymentapi->get_banks('wangyin');
        if ($list['status'] == '1') {
            $this->assign("usinfo", $list);
            $this->display('order_untreated');
            exit;
        } elseif ($list['status'] == '2') {
            $this->assign("usinfo", $list);
            $this->assign("banks", $banks);
            $this->assign("amount", M('user')->where("id='{$_SESSION['user_id']}'")->getfield('amount'));
            $this->display('order_processed');
            exit;
        } else {
            $this->error('订单错误!');
        }
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
            
        }
    }

    public function ajax_award() {
        $code = $_POST['code'];
        $time = time();
        $cash_coupon = M('cash_coupon');
        $count = $cash_coupon->where("serial_num='$code' AND ((expire_time>$time) OR (expire_time='0')) AND status='0'")->find();
        if ($count) {
            $this->ajaxReturn($count['coupon_value'], "不为空", 1);
            exit;
        } else {
            $this->ajaxReturn(0, "号码不存在或者已经使用!", 0);
        }
    }

    public function detail() {
        $id = $_GET["id"] ? intval($_GET["id"]) : 0;
        $line = M("line");
        $line_info = M("LineInfo");
        $line_pic = M("LinePic");
        $line_base = $line->find($id);
        $line_keep = M('line_keep');
        $keep = $line_keep->where("user_id='{$_SESSION['user_id']}' and line_id='$id'")->count();
        if ($line_base) {
            //显示方式(1,按天，2,可视化)
            $view_result = array();
            $view_method = $line_base["edit_model"];

            //获取按天显示方式的记录
            if ($view_method == 1) {
                $line_travel = M("LineTravel");
                $line_travel_section = M("LineTravelSection");
                $line_day = $line_travel->where("line_id=" . $line_base["id"])->order("day")->select();
                if ($line_day) {
                    foreach ($line_day as $day) {
                        $map = array(
                            'line_id' => array("eq", $line_base["id"]),
                            'travel_id' => array("eq", $day["id"])
                        );
                        $view_result[] = array(
                            "day_info" => $day,
                            "Section_info" => $line_travel_section->where($map)->order("names")->select()
                        );
                    }
                }
            }
            //出发城市
            $belong = M("Area");
            $start_city_belong = $belong->where("id=" . $line_base["city_id"])->find();
            //目的地城市
            $target_city_belong = $belong->table($belong->getTableName() . " be")
                            ->join(M("line_target")->getTableName() . " tar on be.id=tar.area_id")
                            ->where(array("tar.id" => array("in", $line_base["target"])))->getField("tar.id,names");
            //线路详细描述
            $lineinfo = $line_info->where("lid=" . $line_base["id"])->find();
            // dump($lineinfo); exit();
            //图片列表
            $linepic = $line_pic->where("line_id=" . $line_base["id"])->select();

            //基本价格
            //线路价格列表
            $this->travel_price_list($id);

            //线路点评
            $this->travel_impr($id);
            //线路问答
            $this->travel_que($id);
        }
        $this->assign("keep_status", $keep);
        $this->assign("line_base", $line_base); //基本信息
        $this->assign("view_method", $view_method); //显示方式1按天，2可视化
        $this->assign("view_result", $view_result); //按天显示方式内容
        $this->assign("lineinfo", $lineinfo); //其它信息
        $this->assign("linepic", $linepic); //图片
        $this->assign("start_city_belong", $start_city_belong); //出发城市
        $this->assign("target_city_belong", $target_city_belong); //目的地城市
        $this->assign("price_pt", $price_pt); //普通价格
        $this->assign("price_date", $price_date_json); //按星期几价格
        $this->assign("price_stage", $price_stage_json); //指定阶段价格
        $this->assign("price_day_json", $price_day_json); //指定日期价格
        $this->assign("line_id", $id); //线路id
        //dump($line_base);      
        $this->init_function();
        $this->display();
    }

    protected function travel_price_list($id) {
        $line_price = M("LinePrice");
        //普通价格
        $price_pt = $line_price->where("price_type=4 and line_id=$id")->field("RACKRATE,price_adult,price_children")->find();
        //按星期价格
        $price_date_tmp = $line_price->where("price_type=3 and line_id=$id")->select();
        foreach ($price_date_tmp as $tmp) {
            $price_date[$tmp["price_date"]] = array("RACKRATE" => $tmp["RACKRATE"], "price_adult" => $tmp["price_adult"], "price_children" => $tmp["price_adult"]);
        }
        //按阶段
        $price_stage_tmp = $line_price->where("price_type=2 and line_id=$id")->select();
        foreach ($price_stage_tmp as $tmp) {
            $key = date("Ymd", $tmp["price_date"]) . "_" . date("Ymd", $tmp["price_date_end"]);
            $price_stage[$key] = array("RACKRATE" => $tmp["RACKRATE"], "price_adult" => $tmp["price_adult"], "price_children" => $tmp["price_adult"]);
        }
        //按指定日期
        $price_day_tmp = $line_price->where("price_type=1 and line_id=$id")->select();
        foreach ($price_day_tmp as $tmp) {
            $key = date("Ymd", $tmp["price_date"]);
            $price_day[$key] = array("RACKRATE" => $tmp["RACKRATE"], "price_adult" => $tmp["price_adult"], "price_children" => $tmp["price_adult"]);
        }

        $travel_price_list = array(4 => $price_pt, 3 => $price_date, 2 => $price_stage, 1 => $price_day);


        $this->assign("travel_price_list", json_encode($travel_price_list));
    }

    protected function travel_impr($id) {
        $line_order = M("line_order")->getTableName() . " orders";
        $line_impr = M("line_impr")->getTableName() . " impr";
        $M = M();
        $impr_lists = $M->table($line_impr)->join($line_order . " on impr.order_id=orders.id")
                        ->field("*,orders.create_time as order_time,impr.create_time as impr_time")
                        ->where("orders.line_id=$id")
                        ->order("impr.create_time desc")->select();
        $counts = $M->table($line_impr)->join($line_order . " on impr.order_id=orders.id")
                        ->field("count(*) as tmp_count,avg(travel) as avg_travel,avg(guide) as avg_guide,avg(traffic) as avg_traffic,avg(room) as avg_room,avg(point) as avg_point")
                        ->where("orders.line_id=$id")->find();

        function impr_detail($val) {
            $details = array("1" => "差", 2 => "一般", 3 => "好", 4 => "很好", 5 => "非常好");
            return $details[$val];
        }

        function impr_list($impr) {
            $com_impr = M("comm_impr")->where("id in($impr)")->select();
            $color = array("#7CB30D", "#999", "#A95A34", "#F957B4", "#0D93E3", "#F5A40C", "#8B05B1", "#CA032D");
            foreach ($com_impr as $impr) {
                shuffle($color);
                $imprs.="<li class=\"{$color}\" style=\"background-color:$color[0];\">{$impr["names"]}</li>";
            }
            return $imprs;
        }

        $this->assign("impr_lists", $impr_lists);
        $this->assign("impr_counts", $counts);
    }

    protected function travel_que($id) {
        $line_que = M("line_que");
        $lists = $line_que->where("line_id=$id")->order("id desc")->select();
        $this->assign("lists_que", $lists);
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
        $line_que = M("line_que");
        $publish_time = time() - 300;
        $last_que_count = $line_que->where("line_id=$id and user_id={$_SESSION["user_id"]} and publish_time>$publish_time")->count();
        if ($last_que_count > 0) {
            $this->ajaxReturn(array("info" => "您的操作过于频繁", "status" => "n"));
            exit;
        }
        $line = M("line");
        $line_count = $line->where("id=$id and status=1")->count();
        if ($line_count == 0) {
            $this->ajaxReturn(array("info" => "您咨询的路线不存在或已经停用", "status" => "n"));
            exit;
        }
        $data = array(
            "line_id" => $id,
            "user_id" => $_SESSION["user_id"],
            "question1" => trim($_POST["question1"]),
            "publish_time" => time(),
            "status" => 1,
            "sort" => 0,
        );
        $line_que->add($data);
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

    /**
     * 价格列表转换成json
     * @param type $arr  价格结果
     * @param type $date_f 是否格式化日期
     * @return type
     */
    private function json_price_list($arr, $date_f = false) {
        $json_arr = array();
        if ($date_f) {
            foreach ($arr as $k => $v) {
                $json_arr[date("Y-m-d", $k)] = explode("|", $v);
            }
        } else {
            foreach ($arr as $k => $v) {
                $json_arr[$k] = explode("|", $v);
            }
        }
        return json_encode($json_arr);
    }

    public function add_coll() {
        $uid = $_SESSION['user_id'];
        $id = $_GET['id'];
        $line_keep = M('line_keep');
        $data['line_id'] = $id;
        $data['user_id'] = $uid;
        $data['create_time'] = time();
        $data['status'] = 1;
        $count = $line_keep->where("user_id = '$uid' and line_id='$id'")->count();
        if (empty($uid)) {
            $this->ajaxReturn("", "用户未登陆", "0");
            exit;
        }
        if ($count > 0) {
            $line_keep->where("user_id = '$uid' and line_id='$id'")->delete();
            $this->ajaxReturn("", "", "2");
            exit;
        } else {
            $line_keep->add($data);
            $this->ajaxReturn("", "收藏成功!", "1");
            exit;
        }
    }

    public function get_line_city() {
        $city_belong = M("city_belong")->getTableName() . " belong";
        $area = M("area")->getTableName() . " area";
        $target_city = M()->table($city_belong)
                ->join($area . " on area.id=belong.cid")
                ->where("belong.types ='LINE'")
                ->order("area.pid,belong.sort")
                ->getField("belong.cid,belong.id,area.pid,area.names,area.names_en");
        $this->ajaxReturn($target_city);
    }

    public function search_key_word() {
        $key_word = trim($_GET["key_word"]);
        $start_id = intval($_GET['cid']) > 0 ? intval($_GET['cid']) : $this->tVar["currentCity"]["id"];
        $current_city = M("area")->where(array("id" => $start_id))->getField("names_en");
        $url = U("travel_lists", array("type" => $this->type_select, "current_city" => $current_city));
        setcookie("current_city", $current_city, time() + 86400, "/");
        if (empty($key_word)) {
            header("Location:$url");
            exit;
        }
        $line_target_topic = D("line_target_topic");
        $line_topic_type = D("line_topic_type");
        $word['zppo'] = $line_target_topic->where("start_id=$start_id and type_id=" . $this->type_select)->order("sort")->getField("id,target_topic");
        //线路主题列表
        $word['theme'] = $line_topic_type->where("status=1")->order("sort")->getField("id,names");
        //出游天数
        $word['day'] = array("1" => "一日游", "2" => "二日游", "3" => "三日游", "4" => "四日游", "5" => "五日游", "6" => "六日游", "7" => "七日游以上");
        //交通信息
        $word['traffic'] = array("1" => "大巴", "2" => "火车", "3" => "飞机");
        $keys = $vals = "";
        foreach ($word as $key => $val) {
            $ikey = array_keys($val, $key_word);
            if ($ikey) {
                $keys = $key;
                $vals = $ikey[0];
                break;
            }
        }
        if ($keys) {
            header("Location:{$url}?{$keys}={$vals}");
            exit;
        }
        if (preg_match("/^([1-9][0-9]*)(以上|元以上)?$/", $key_word, $matchs)) {
            header("Location:{$url}?min_price={$matchs[1]}");
            exit;
        } elseif (preg_match("/^([1-9][0-9]*)(元|以下|元以下)?$/", $key_word, $matchs)) {
            header("Location:{$url}?max_price={$matchs[1]}");
            exit;
        } elseif (preg_match("/^([1-9][0-9]*)(\-|至|到)([1-9][0-9]*)(元)?$/", $key_word, $matchs)) {
            header("Location:{$url}?min_price={$matchs[1]}&max_price={$matchs[3]}");
            exit;
        }
        header("Location:{$url}");
    }

}

?>
