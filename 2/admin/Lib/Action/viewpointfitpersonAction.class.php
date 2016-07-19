<?php

import("ORG.Util.Page");

class viewpointfitpersonAction extends CommonAction {

    //添加
    public function add() {
        if (!isset($_POST['save'])) {
            $types = $_GET['types'];
            $this->assign('types', $types);
            $this->display();
        } else {
        	$types=$_POST['types'];
            $fit_person = M('FitPerson');
            $fit_person->create();
            $fit_person->names = $_POST['names'];
            $fit_person->sort = $_POST['sort'];
            $fit_person->types = $_POST['types'];
            //dump($_POST['types']);exit;
            $fit_person->add();
            //$this->success("添加成功");
            $this->redirect("show_list");
            
        }
    }

    //编辑
    public function ajax_save() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $names = $_POST['names'];
        $sort = $_POST['sort'];
        $fit_person = M('fit_person');
        $fit_person->create();
        $fit_person->id = $id;
        $fit_person->names = $names;
        $fit_person->sort = $sort;
        $fit_person->save();
        echo 1;exit;
    }
    
    //删除    
	public function ajax_del(){
		header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $fit_person = M('FitPerson');
        $fit_person->where("id=$id")->delete();
        echo 1;exit;
    }

    //全部列表
    public function show_list() {
        $types = $_GET['t'];
        $fit_person = M("fit_person");
        $count = $fit_person->count();
        $page = $this->pagebar($count);
        $list = $fit_person->page($page)->order("sort,id desc")->select();
        $this->assign('list', $list);
        $this->assign('types', $types);
        $this->display();
    }

    
    //点击了列表下方的删除按钮之后
    public function deleteall()
    {
        if(isset($_POST['dosubmit']))
        {
            $done = false;
            $view = M('FitPerson');
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
    
    public function sort_list() {
        $types = $_GET['t'];
        $fit_person = M("fit_person");
        foreach ($_POST["sort"] as $key => $val) {
            $fit_person->where("id={$key}")->setField("sort", $val);
        }

        $this->redirect("show_list",array("t"=>$types));
    }
}

?>