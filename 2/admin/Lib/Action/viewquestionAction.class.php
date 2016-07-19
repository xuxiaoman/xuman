<?php

import("ORG.Util.Page");

class viewquestionAction extends CommonAction
{
    
    public function show_list()
    {
        $view_que = M('ViewpointQue');
        $view = M('Viewpoint');
        $user = M('User');
        $pages = ($_GET['p'] == null ? '1' : $_GET['p']);
        $ques = $view_que->page($pages . ',15')->select();
        $count = $view_que->count();
        $Page = new Page($count, 15);
        $show = $Page->show();
        
        foreach($ques as $p => $i)
        {
            $view_name = $view->where("id=".$i["view_id"])->getField("names");
            $user_name = $user->where("id=".$i["user_id"])->getField("login_name");
            $ques[$p]["view_name"] = $view_name;
            $ques[$p]["user_name"] = $user_name;
        }
        $this->assign("list",$ques);
        $this->assign("page",$show);
        $this->assign("p",$Page);
        $this->display();
    }
    
    
    public function answer()
    {
        $id = $_GET['id'];
        $this->assign('id', $id);
        $view_que = M('ViewpointQue');
        
        if(!isset($_POST['save']))
        {
            $view = M('Viewpoint');
            $ques = $view_que->where('id='.$id)->find();
            $view_name = $view->where("id=".$ques["view_id"])->getField("names");
            $ques['view_name'] = $view_name;
            $this->assign("view", $ques);
            $this->display();
        }
        else
        {
            if($_POST['answer']!=null && $_POST['answer']!=""){
                $_POST['status'] = 2;
                $_POST['answer_time'] = strtotime(date("Y-m-d H:i:s"));
            }else{
                $_POST['status'] = 1;
                $_POST['answer_time'] = null;
                $_POST['answer'] = null;
            }
            $view_que->create();
            $view_que->where('id='.$id)->save();
            $this->success("发布成功");
        }
    }
    
    
    //删除    
    public function ajax_del(){
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $ques_list = M('HotelQue');
        $ques_list->where("id=$id")->delete();
        echo 1;exit;
    }
    
    
    //点击了列表下方的删除按钮之后
    public function deleteall()
    {
        if(isset($_POST['dosubmit']))
        {
            $done = false;
            $View = M("ViewpointQue");
            $count = $View->count();
            $id = $View->getField("id",true);
            for($i=0;$i<$count;$i++)
            {
                if($_POST["items_".$id[$i]])
                {
                    $View->where("id=".$id[$i])->delete();
                    $done = true;
                }
            }
            if($done)
              $this->success("删除成功！");
            else
              $this->error("请勾选至少1项。");
        }
        else
        {
            $this->redirect("show_list");
        }
    }

    public function sort_list()
    {
        $view_que = M('ViewpointQue');
        $pages = ($_GET['p'] == null ? '1' : $_GET['p']);
        $list = $view_que->order("sort")->page($pages . ',15')->select();
        
        /* 将新排序写入数据库 */
        $sort_list=$list;
        for($i=0;$i<count($list);$i++){
            for($k=count($list)-2;$k>=$i;$k--){
                if($list[$k+1]['id']<$list[$k]['id']){
                    $tmp=$list[$k+1];
                    $list[$k+1]=$list[$k];
                    $list[$k]=$tmp;
        }}}foreach($list as $k=>$i){
            $data[$k]["view_id"]=$list[$k]["view_id"]=$sort_list[$k]["view_id"];
            $data[$k]["user_id"]=$list[$k]["user_id"]=$sort_list[$k]["user_id"];
            $data[$k]["question1"]=$list[$k]["question1"]=$sort_list[$k]["question1"];
            $data[$k]["question2"]=$list[$k]["question2"]=$sort_list[$k]["question2"];
            $data[$k]["answer"]=$list[$k]["answer"]=$sort_list[$k]["answer"];
            $data[$k]["publish_time"]=$list[$k]["publish_time"]=$sort_list[$k]["publish_time"];
            $data[$k]["answer_time"]=$list[$k]["answer_time"]=$sort_list[$k]["answer_time"];
            $data[$k]["status"]=$list[$k]["status"]=$sort_list[$k]["status"];
            $data[$k]["sort"]=$list[$k]["sort"]=$sort_list[$k]["sort"];
            $view_que->where("id=".$list[$k]["id"])->save($data[$k]);}

        $this->redirect("show_list",array("p"=>$pages));
    }
}

?>
