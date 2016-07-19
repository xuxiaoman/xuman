<?php

import("ORG.Util.Page");

class viewpointtypeAction extends CommonAction {

    //添加
    public function add() {
        if (!isset($_POST['save'])) {
            $types = $_GET['types'];
            $this->assign('types', $types);
            $this->display();
        } else {
        	$types = $_POST['types'];
            $ViewpointType = M('ViewpointType');
            $ViewpointType->create();
            $ViewpointType->names = $_POST['names'];
            $ViewpointType->sort = $_POST['sort'];
            $ViewpointType->types = $_POST['types'];
            //dump($_POST['types']);exit;
            $ViewpointType->add();
           //$this->redirect("show_list","t=".$types);
           $this->success("添加成功");
        }
    }

    //编辑
    public function ajax_save() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $names = $_POST['names'];
        $sort = $_POST['sort'];
        $ViewpointType = M('ViewpointType');
        $ViewpointType->create();
        $ViewpointType->id = $id;
        $ViewpointType->names = $names;
        $ViewpointType->sort = $sort;
        $ViewpointType->save();
        echo 1;exit;
    }
    
    //删除    
	public function ajax_del(){
		header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $ViewpointType = M('ViewpointType');
        $ViewpointType->where("id=$id")->delete();
        echo 1;exit;
    }

    //全部列表
    public function show_list() {
        $types = $_GET['t'];
        $ViewpointType = M("ViewpointType");
        $count = $ViewpointType->count();
        $page = $this->pagebar($count);
        $list = $ViewpointType->page($pages)->order("sort,id desc")->select();

        $this->assign('list', $list);
        $this->assign('types', $types);
        $this->display();
    }
    
    public function sort_list() {
        $types = $_GET['t'];
        $ViewpointType = M("ViewpointType");
        foreach ($_POST["sort"] as $key => $val) {
            $ViewpointType->where("id={$key}")->setField("sort", $val);
        }

        $this->redirect("show_list",array("t"=>$types));
    }
    
    
    //点击了列表下方的删除按钮之后
    public function deleteall()
    {
        if(isset($_POST['dosubmit']))
        {
            $done = false;
            $view = M('ViewpointType');
            $count = $view->count();
            $id = $view->getField("id",true);
            for($i=0;$i<$count;$i++)
            {
                if($_POST["items_".$id[$i]])
                {
                    $view->where("id=".$id[$i])->delete();
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

}

?>