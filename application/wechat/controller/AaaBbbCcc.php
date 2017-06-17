<?php

// +----------------------------------------------------------------------
// | 生成器
// +----------------------------------------------------------------------
// | 说明。
// +----------------------------------------------------------------------
// | Author: Meetrice <meetrice@qq.com> <http://www.rc3cr.com>
// +----------------------------------------------------------------------
namespace app\wechat\controller;
use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Db;

    class AaaBbbCcc extends BasicAdmin {

    public $table = 'Wechat_aaa_bbb_ccc';

    /**
    * 列表
    */
    public function index() {
            $this->title = '管理';
        parent::_list($this->table);
    }
    /**
    * 添加
    */
    public function add() {

    
        return $this->_form($this->table, 'form');
    }

    /**
    * 编辑
    */
    public function edit() {
            return $this->_form($this->table, 'form');
    }


    /**
    * 删除
    */
    public function del() {
        if (DataService::update($this->table)) {
        $id = $this->request->post('id');
        Db::name($this->table)->where('id', $id)->delete();
        $this->success("删除成功！", '');
        }
        $this->error("删除失败，请稍候再试！");
    }


    /**
    * 禁用
    */
    public function forbid() {
        if (DataService::update($this->table)) {
        $this->success("禁用成功！", '');
        }
        $this->error("禁用失败，请稍候再试！");
    }

    /**
    * 恢复
    */
    public function resume() {
        if (DataService::update($this->table)) {
        $this->success("启用成功！", '');
        }
        $this->error("启用失败，请稍候再试！");
    }

    /**
    * 表单数据默认处理
    * @param array $data
    */
    public function _form_filter(&$data) {
        if ($this->request->isPost()) {


        

        }
    }

}