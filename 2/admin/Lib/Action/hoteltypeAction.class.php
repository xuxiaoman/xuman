<?php
import("ORG.Util.Page");
import("ORG.Net.UploadFile");
import("@.ORG.file");

class hoteltypeAction extends CommonAction {

    public function add() {
        if (!isset($_POST['save'])) {
            $this->display();
        } else {
            $HotelType = M('HotelType');
            $HotelType->create();
            $HotelType->names = $_POST['names'];
            $HotelType->sort = $_POST['sort'];
            $HotelType->picpath = $_POST['pic_front'];
            $HotelType->add();
            $this->success("添加成功！");
        }
    }

    //点击了列表下方的删除按钮之后
    public function deleteall() {
        if (isset($_POST['dosubmit'])) {
            $done = false;
            $Hotel = M("HotelType");
            $count = $Hotel->count();
            $id = $Hotel->getField("id", true);
            for ($i = 0; $i < $count; $i++) {
                if ($_POST["items_" . $id[$i]]) {
                    $picpath = $Hotel->where("id=" . $id[$i])->getField("picpath");
                    $Hotel->where("id=" . $id[$i])->delete();

                    @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $picpath);
                    $done = true;
                }
            }
            if ($done)
                $this->success("删除成功！");
            else
                $this->error("请勾选至少1项。");
        }
        else {
            $this->redirect("show_list");
        }
    }

    //编辑
    public function ajax_save() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $names = $_POST['names'];
        $sort = $_POST['sort'];
        $HotelType = M('HotelType');
        $HotelType->create();
        $HotelType->id = $id;
        $HotelType->names = $names;
        $HotelType->sort = $sort;
        $HotelType->save();
        echo 1;
        exit;
    }

    //删除    
    public function ajax_del() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $HotelType = M('HotelType');
        $picpath = $HotelType->where("id=$id")->getField("picpath");
        $HotelType->where("id=$id")->delete();
        @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $picpath);
        echo 1;
        exit;
    }

    //全部列表
    public function show_list() {
        $HotelType = M("HotelType");
        $count = $HotelType->count();
        $page = $this->pagebar($count);
        $list = $HotelType->page($page)->order("sort,id desc")->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function sort_list() {
        $HotelType = M("HotelType");
        foreach ($_POST["sort"] as $key => $val) {
            $HotelType->where("id={$key}")->setField("sort", $val);
        }
        $this->redirect("show_list");        
    }

}
?>