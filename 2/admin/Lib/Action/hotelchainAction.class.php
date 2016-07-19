<?php
import("ORG.Util.Page");
import("ORG.Net.UploadFile");
import("@.ORG.file");

class hotelchainAction extends CommonAction {

    public function add() {
        if (!isset($_POST['save'])) {
            $this->display();
        } else {
            $HotelChain = M('HotelChain');
            $HotelChain->create();
            $HotelChain->names = $_POST['names'];
            $HotelChain->sort = $_POST['sort'];
            $HotelChain->logopath = $_POST['pic_front'];
            $HotelChain->add();
            $this->success("添加成功！", "show_list");
        }
    }

    public function update() {
        if (!isset($_POST['save'])) {
            $HotelChain = M('HotelChain');
            $hotel = $HotelChain->where("id=" . $_GET['id'])->find();
            $this->assign("hotel", $hotel);
            $this->display();
        } else {
            $HotelChain = M('HotelChain');
            $data["names"] = $_POST['names'];
            $data["sort"] = $_POST['sort'];
            if ($_POST['pic_front'] != "") {
                $data["logopath"] = $_POST['pic_front'];
                $logopath = $HotelChain->where("id=" . $_POST['id'])->getField("logopath");
                @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $logopath);
            }

            $HotelChain->where("id=" . $_POST['id'])->save($data);
            $this->success("编辑成功！", U("show_list"));
        }
    }

    //点击了列表下方的删除按钮之后
    public function deleteall() {
        if (isset($_POST['dosubmit'])) {
            $done = false;
            $Hotel = M("HotelChain");
            $count = $Hotel->count();
            $id = $Hotel->getField("id", true);
            for ($i = 0; $i < $count; $i++) {
                if ($_POST["items_" . $id[$i]]) {
                    $picpath = $Hotel->where("id=" . $id[$i])->getField("logopath");
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
        $HotelChain = M('HotelChain');
        $HotelChain->create();
        $HotelChain->id = $id;
        $HotelChain->names = $names;
        $HotelChain->sort = $sort;
        $HotelChain->save();
        echo 1;
        exit;
    }

    //删除    
    public function ajax_del() {
        header('Content-Type:text/html;charset=utf-8');
        $id = $_GET['id'];
        $HotelChain = M('HotelChain');
        $logopath = $HotelChain->where("id=$id")->getField("logopath");
        $HotelChain->where("id=$id")->delete();

        @unlink($_SERVER['DOCUMENT_ROOT'] . __ROOT__ . $logopath);
        echo 1;
        exit;
    }

    //全部列表
    public function show_list() {
        $HotelChain = M("HotelChain");

        if (!empty($_GET['title'])) {
            $where['names'] = array('like', "%{$_GET['title']}%");
            $where['_logic'] = 'or';
            $this->assign("title", $_GET['title']);
        } else {
            $where = "1=1";
        }
        $count = $HotelChain->where($where)->count();
        $page = $this->pagebar($count);
        $list = $HotelChain->page($page)->where($where)
                ->order('sort,id desc')
                ->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function sort_list() {
        $HotelChain = M("HotelChain");
        foreach ($_POST["sort"] as $key => $val) {
            $HotelChain->where("id={$key}")->setField("sort", $val);
        }
        $this->redirect("show_list");
    }

}
?>