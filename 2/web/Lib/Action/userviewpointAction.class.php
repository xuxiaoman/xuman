<?php

/**
 * 文件名称：viewpointAction.class.php
 * 用途说明：景点管理类，用以实现景点管理的各项功能。
 * 开发人员：maruiming2010@jeechange.com
 * 创建时间：2013/10/23 14:47:39
 */
import('ORG.Util.Page'); // 导入分页类

class userviewpointAction extends usercommonAction {

	/**
	 * 景点管理默认页，罗列该用户已经订购景点的订单
	 */
	public function index() {
		$Order = D('ViewpointOrder'); // 实例化ViewpointOrder数据对象

		if (!empty($_GET['status']) && is_numeric($_GET['status'])) {
			$count = $Order->count($_GET['status']); // 查询满足要求的总记录数,带订单状态
			$page = $this->pagebar($count);
			$orders = $Order->get_orders($page, $_GET['status']);
			$this->assign('reccent_status', $_GET['status']);
		} else {
			$count = $Order->where("user_id=" . $_SESSION['user_id'])->count(); // 查询满足要求的总记录数,不带订单状态
			$page = $this->pagebar($count);

			$orders = $Order->get_orders($page);
                        //echo $Order->getLastSql();exit;
		}

		foreach ($orders as $key => $value) {
			$orders[$key]['total'] = $value['ticket_num'] * $value['price'];
			$orders[$key]['option'] = $this->get_option($value['order_status'], $value['id']);

			$orders[$key]['option'] .= "<a href=\"" . U("userviewpoint/order", array('id' => $value['id'])) . "\">查看订单<a>";
		}
		$this->assign('list', $orders);
		$this->display();
	}

	public function collect() {
		$uid = $_SESSION['user_id'];
		$id = $_GET['id'];

		$viewpoint_collection = M('viewpoint_collection');
		$data['viewpoint_id'] = $id;
		$data['user_id'] = $uid;
		$data['create_time'] = date("Y-m-d H-m-s", time());
		$data['status'] = 1;

		$viewpoint_collection->add($data);
		$this->redirect("userviewpoint/collection");
	}

	/**
	 * 景点订单页面，展示订单详情
	 */
	public function order() {
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

	/**
	 * 景点点评，景点获得的总体评价
	 */
	public function comment() {
		if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
			$Comment = D("ViewpointImpr");
			$ViewpointOrder = D("ViewpointOrder");
			$Impression = M("CommImpr");

			$order = $ViewpointOrder->get_order_by_id($_GET['id']);
			$comment = $Comment->get_comment_by_order_id($order['id']);

			$impressions = json_decode($comment['impr_type']);

			foreach ($impressions as $key => $value) {
				$impression[] = $Impression->where("types='VIEWPOINT' AND id=" . $value)
						 ->getField("names");
			}

			$this->assign('impression', $impression);
			$this->assign('comment', $comment);
			$this->assign('order', $order);

			$this->display();
		} else {
			$this->error("非法访问！");
		}
	}

	/**
	 * 我的点评，我对景点的点评
	 */
	public function my_comment() {
		$Order = D("ViewpointOrder");
		$Impression = D("CommImpr");
		$colors = array("blue", "green", "pink", "orange", "red", "brown", "purple", "gray");

		if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
			$order = $Order->get_order_by_id($_GET['id']);
			$impressions = $Impression->where("types='VIEWPOINT'")->select();
			$total_impressions = $Impression->where("types='TOTAL_IMPR'")->select();
			$viewpoint_impressions = $Impression->where("types='IMPRESSION'")->select();

			foreach ($impressions as $key => $value) {
				$impressions[$key]['color'] = $colors[rand(0, 7)];
			}

			$_SESSION['order_id'] = $order['id'];
			$_SESSION['comment_award'] = $order['review_bonus'];
			$this->assign("total_impressions", $total_impressions);
			$this->assign("viewpoint_impressions", $viewpoint_impressions);
			$this->assign("impressions", $impressions);
			$this->assign("order", $order);
			$this->display();
		}
	}

	/**
	 * 处理提交的景点评论
	 */
	public function comment_processor() {

		if (!empty($_POST) && !empty($_SESSION['order_id'])) {
			$ViewpointImpr = M("ViewpointImpr");
			$ViewpointOrder = D("ViewpointOrder");

			$order = $ViewpointOrder->get_order_by_id($_SESSION['order_id']);
			$view_id = $order['viewpoint_id'];

			$count = $ViewpointImpr
					 ->where("user_id=" . $_SESSION['user_id'] . " AND order_id=" . $_SESSION['view_id'])
					 ->count();

			if (!$count) {
				$data = array();
				$data['view_id'] = $view_id;
				$data['order_id'] = $_SESSION['order_id'];
				$data['user_id'] = $_SESSION['user_id'];
				$data['impr_type'] = json_encode($_POST['impr_type']);
				$data['decoration'] = $_POST['decoration'];
				$data['traffic'] = $_POST['traffic'];
				$data['hygiene'] = $_POST['hygiene'];
				$data['prices'] = $_POST['prices'];
				$data['show_name'] = $_POST['show_name'];
				$data['content'] = $_POST['content'];
				$data['points'] = $_POST['points'];
				$data['publish_time'] = time();
				$data['status'] = 0;
				$result = $ViewpointImpr->data($data)->add();

				if ($result) {
					$User = M("User");
					$ViewpointOrder->where("id=" . $_SESSION['order_id'])->save(array('status' => 5));
					$this->redirect("userviewpoint/comment", array('id' => $_SESSION['order_id']));
				}
			} else {
				$this->error("您已经点评过该景点！");
			}
		} else {
			$this->error("非法访问！");
		}
	}

	/**
	 * 景点问答，对景点的相关问题的咨询及回复
	 * faq: Frequently Asked Questions
	 */
	public function faq() {
		$Question = D('ViewpointQue');
		$Viewpoint = M('Viewpoint');

		$count = $Question->count($_SESSION['user_id']);
		$page = $this->pagebar($count);

		$questions = $Question->get_questions($_SESSION['user_id'], $page);

		$this->assign('questions', $questions);


		$this->display();
	}

	/**
	 * 景点收藏列表
	 */
	public function collection() {
		$Collection = D('ViewpointCollection');
		$Viewpoint = D('Viewpoint')->getTableName() . " viewpoint";

		$count = $Collection->count($_SESSION['user_id']); // 查询满足要求的总记录数
		$page = $this->pagebar($count);
		$status = $_GET['status'] != null ? $_GET['status'] : 1;


		// 分页记录数
		$collections = $Collection->get_collections($_SESSION['user_id'], $page, $status);

		foreach ($collections as $key => $value) {
			$collections[$key]['url'] = U('userviewpoint/delete_collection', array('id' => $value['id']));
		}

		$this->assign('collections', $collections);
		$this->assign('status', $status);
		$this->display();
	}

	/**
	 * 收藏景点
	 */
	public function set_collection() {
		$Collection = M("ViewpointCollection");

		$id = $_GET['id'];

		if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
			$Collection->where("user_id=" . $_SESSION['user_id'] . " AND id=" . $_GET['id'])->save(array('status' => 1));

			$this->redirect("userviewpoint/collection");
		} else {
			$this->error("您无权操作该收藏记录！");
		}
	}

	/**
	 * 取消景点收藏
	 */
	public function delete_collection() {
		$Collection = M("ViewpointCollection");

		$collection = $Collection->where("id=" . $_GET['id'] . " AND user_id=" . $_SESSION['user_id'])
				 ->count();

		if (!empty($_GET['id']) && is_numeric($_GET['id']) && $collection > 0) {
			$result = $Collection->where("id=" . $_GET['id'])->save(array('status' => 0));

			if (false !== $result) {
				$this->redirect('userviewpoint/collection');
			} else {
				$this->error('删除出错！');
			}
		} else {
			$this->error("您无权操作该收藏记录！");
		}
	}

	/**
	 * 取消订单
	 */
	public function cancel() {
		$Order = D("ViewpointOrder");

		if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
			$order = $Order->where("id=" . $_GET['id'] . " AND user_id=" . $_SESSION['user_id'])
					 ->find();

			// 只有“待处理”状态和“待付款”状态用户才能取消订单
			if (($order['status'] == 0) or ($order['status'] == 1)) {
				$data = array('status' => 6, 'status_change_user' => $_SESSION['user_id']);
				$Order->data($data)
						 ->where("id=" . $_GET['id'])
						 ->save();
				//退还奖金
				$info = $Order->where("id={$_GET['id']}")->find();
				//要退的奖金
				$bonus_money = $info["use_award"];
				//要退的已付金额
				$pay_money = $info["pay_money"];
				//要退的代金券（编号）
				$serial_num = $info["coupon_num"];
				//退钱给用户
				M()->query("UPDATE " . M("User")->getTableName() . " SET `award` = `award`+{$bonus_money}, `amount` = `amount`+{$pay_money} WHERE `id`={$info["user_id"]}");
				//退代金券
				M("CashCoupon")->where("serial_num='$serial_num'")->save(array("use_time" => null, "status" => 0, "use_userid" => null));
				$Order->where("id={$_GET['id']}")->save(array("serial_money" => 0, "serial_num" => null));

				$this->success("恭喜您，订单已成功取消！");
			} else {
				$this->error("您无权操作该订单！只有“待处理”状态和“待付款”状态用户才能取消订单。");
			}
		}
	}

	/**
	 *  针对订单当前状态，获取相应操作
	 */
	private function get_option($status, $id) {
		switch ($status) {
			case 0:
			case 1:
				$option = "<a href=\"" . U("userviewpoint/cancel", array('id' => $id)) . "\">取消订单<a><br />";
				return $option;
				break;

			case 3:
				$option = "<a href=\"" . U("userviewpoint/my_comment", array('id' => $id)) . "\">我要点评<a><br />";
				return $option;
				break;
			case 4:
			case 5:
				$option = "<a href=\"" . U("userviewpoint/comment", array('id' => $id)) . "\">查看点评<a><br />";
				return $option;
				break;
		}
	}

}
