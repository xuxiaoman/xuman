<?php

class indexAction extends CommonAction {

    protected $authentic = 0;

    public function _initialize() {
        C("TOKEN_ON", false);
        parent::_initialize();
    }

    public function index() {
        $target_city = $this->target_city();
        $cid = $this->tVar['currentCity']["id"];
        $belog_id = $target_city[$cid]["id"];
        $search["cid"] = $belog_id;
        $search["city_info"] = $target_city[$cid];
        $line = D("Line");
        //按旅游类型，最多筛选5个类型，每个类型获取点击量最高的12条记录
        $types = M("line_type")->cache("index/index/types", 3600)->where("status=1")->order('sort')->limit(5)->select();
        $types_result = array();
        foreach ($types as $v) {
            $types_lists = $line->show_line("city_id=" . $cid . " and status='0' and target_type=" . $v["id"], 12);
            $types_result[$v['id']][0] = array_slice($types_lists, 0, 4);
            $types_result[$v['id']][1] = array_slice($types_lists, 4, 4);
            $types_result[$v['id']][2] = array_slice($types_lists, 8, 4);
        }
        //按主题查找目的地
        $this->line_target($types);
        $line_travel = A('travel');
        $line_travel->init_function();
        $this->assign("base_url", U("travel_lists", array("type" => $_GET["type"], "current_city" => $_GET["current_city"])));
        //路线点评
        $line_order = M("line_order")->getTableName() . " orders";
        $line_impr = M("line_impr")->getTableName() . " impr";
        $M = M();
        $impr_lists = $M->cache("index/index/impr_lists", 600)->table($line_impr)->join($line_order . " on impr.order_id=orders.id")
                        ->field("*,orders.create_time as order_time,impr.create_time as impr_time")
                        ->order("impr.create_time desc")->select();
        //按目的城市，最多筛选12个城市，每个类型获取点击量最高的13条记录
        $hotel_target = $this->target_city("HOTEL", 12);
        $hotel_result = array();
        foreach ($hotel_target as $val) {
            $hotel_lists = M("hotel")->cache("index/index/hotel_result_" . $val['cid'], 3600)->where("city_id=" . $val['cid'] . " and (status=1 or status=0)")->order("hits desc")->limit(13)->select();
            $hotel_result[$val['cid']][0] = array_slice($hotel_lists, 0, 3);
            $hotel_result[$val['cid']][1] = array_slice($hotel_lists, 3, 5);
            $hotel_result[$val['cid']][2] = array_slice($hotel_lists, 8, 5);
        }
        //品牌连锁酒店
        $hotel_chain_lists = M("hotel_chain")->cache("index/index/hotel_chain_lists", 3600)->where('status=1')->order('sort')->limit("15")->select();

        //按城市，最多筛选12个城市，每个类型获取点击量最高的13条记录
        $Viewpoint_target = $this->target_city("Viewpoint", 12);
        $Viewpoint_result = array();
        foreach ($Viewpoint_target as $val) {
            $Viewpoint_lists = M("viewpoint")->cache("index/index/Viewpoint_lists_" . $val['cid'], 3600)->where("city_id=" . $val['cid'] . " and (status=1 or status=0)")->order("hits desc")->limit(14)->select();
            $Viewpoint_result[$val['cid']][0] = array_slice($Viewpoint_lists, 0, 4);
            $Viewpoint_result[$val['cid']][1] = array_slice($Viewpoint_lists, 4, 5);
            $Viewpoint_result[$val['cid']][2] = array_slice($Viewpoint_lists, 9, 5);
        }
        //游玩主题
        $viewpoint_type_lists = M("viewpoint_type")->cache("index/index/viewpoint_type_lists", 3600)->where('status=1')->order('sort')->limit("15")->select();

        //网站公告
        $time = time();
        $notice_list = M("notice")->cache("index/index/notice_list", 600)->where("status=1 and (start_time is null or start_time<=$time) and (end_time is null or end_time>=$time)")
                        ->order("sort asc ,id desc")->limit(8)->select();
        //最新资讯
        $cid = M("article_section")->cache("index/index/News_cid", 0)->where("e_names='News' and status=1")->getField("id");
        if ($cid) {
            $article = M("article")->cache("index/index/article_lists", 0)->where("cid=$cid and status=1")->order("hits desc,id desc")->limit(4)->select();
            if ($article) {
                $articles[0] = array_slice($article, 0, 1);
                $articles[1] = array_slice($article, 1, 3);
            }
        }
        //dump(f_html($articles[0][0]['content']));
        $this->assign('hot_recommend', $hot_recommend);
        $this->assign('types_result', $types_result);
        $this->assign('types', $types);
        $this->assign("recom", $recom_list);
        $this->assign("impr_lists", $impr_lists);
        $this->assign("hotel_target", $hotel_target);
        $this->assign("hotel_result", $hotel_result);
        $this->assign("Viewpoint_target", $Viewpoint_target);
        $this->assign("Viewpoint_result", $Viewpoint_result);
        $this->assign("hotel_chain_lists", $hotel_chain_lists);
        $this->assign("viewpoint_type_lists", $viewpoint_type_lists);
        $this->assign("notice_list", $notice_list);
        $this->assign("search", $search);
        $this->assign("articles", $articles);
        $this->display();
    }

    //异步读取目的地城市
    public function ajax_target() {
        $cid = isset($_GET["cid"]) ? intval($_GET["cid"]) : 0;
        if ($cid > 0) {
            $line_type = M('LineType');
            $line_topic = M('LineTargetTopic');
            $line_target = D('TargetView');
            $types = $line_type->order('sort')->select();
            $target = array();
            if ($types) {
                $result = array();
                foreach ($types as $vv) {
                    $topic_list = $line_topic->getField("id,id as id1", "start_id=" . $cid . " and type_id=" . $vv["id"]);
                    $map["Target.topic_id"] = array("in", $topic_list);
                    $result = $line_target->where($map)->Distinct(true)
                            ->select();

                    if ($result) {
                        $target[] = array(
                            "type" => $vv["names"],
                            "result" => $result
                        );
                    }
                }
            }
            $this->assign("target", $target);
            $data = $this->fetch();
            $this->ajaxReturn($data, "", $data ? 1 : 0);
        }
    }

    protected function target_city($types = 'LINE', $limit = 12) {

        $city_belong = M("city_belong")->getTableName() . " belong";
        $area = M("area")->getTableName() . " area";
        $target_city = M()->cache("target_city_12_$types", 0)->table($city_belong)
                ->join($area . " on area.id=belong.cid")
                ->where("belong.types ='$types'")
                ->order("belong.sort,area.pid")
                ->limit($limit)
                ->getField("belong.cid,belong.id,area.pid,area.names,area.names_en");

        if ($types == 'LINE')
            $this->assign("target_city", $target_city);
        return $target_city;
    }

    //按线路类型查找目的地
    public function line_target($types) {
        $start_id = $this->tVar["currentCity"]["id"];
        $line_targer = M("line_target");
        $line_targer_table = $line_targer->getTableName() . ' target';
        $area_table = M("area")->getTableName() . ' area';
        $target_type = array();
        $where['target.start_id'] = $start_id;
        foreach ($types as $type) {
            $where['target.type_id'] = $type['id'];
            $target_type[$type['id']] = $line_targer->table($line_targer_table)
                    ->join($area_table . ' on target.area_id=area.id')
                    ->where($where)
                    ->field('target.*,area.names areanames')
                    ->select();
            $types_names[$type['id']]=$type['names'];
        }       
        $this->assign("target_type", $target_type);
        $this->assign("types_names", $types_names);
    }

    /**
     * 意见反馈
     */
    public function feedback() {
        if (isset($_POST["sendout"])) {
            $msg = "用户未提供";
            $email = (empty($_POST['email'])) ? $msg : $_POST['email'];
            $qq = (empty($_POST['qq'])) ? $msg : $_POST['qq'];
            $phone = (empty($_POST['phone'])) ? $msg : $_POST['phone'];
            $content = "<h2>TripEC 客户意见反馈</h2>
						<table width=\"700\" border=\"0\">
						  <tr>
							 <th width=\"140\" align=\"right\">客户 E-mail：</th>
							 <td>$email</td>
						  </tr>
						  <tr>
							 <th align=\"right\">客户 QQ：</th>
							 <td>$qq</td>
						  </tr>
						  <tr>
							 <th align=\"right\">客户联系号码：</th>
							 <td>$phone</td>
						  </tr>
						  <tr>
							 <th align=\"right\">意见内容：</th>
							 <td>{$_POST['content']}</td>
						  </tr>
						</table>";
            $mail = C("email_api");
            $freemail = new freemail(
                    $mail["email_server"], 25, $mail["email_user"], $mail["email_pwd"], $mail["email_user"], $mail["email_fromname"]
            );
            $freemail->fsock_send("TripEC 客户意见反馈", $content);
            $this->success("您的宝贵建议我们已经收到，感谢您的支持。", "../");
        } else {
            $this->display();
        }
    }

}

?>