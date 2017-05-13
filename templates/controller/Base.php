<?php

// +----------------------------------------------------------------------
// | 生成器
// +----------------------------------------------------------------------
// | 说明。
// +----------------------------------------------------------------------
// | Author: Meetrice <meetrice@qq.com> <http://www.rc3cr.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Db;

class {$tbname} extends BasicAdmin {

    protected $table = '{$tbname}';

    /**
     * 列表
     */
    public function index() {
        $data=[];
        $dicts=Db::name('dict')->where('type', 'sex')->field('dkey,dvalue')->select();
        foreach ($dicts as $vo) {
            $data[$vo['dkey']] = $vo['dvalue'];
        }
        $this->assign('dicts', $data); //字典

        $this->title = '管理{$tbcomment}';
        parent::_list($this->table);
    }
    /**
     * 添加
     */
    public function add() {

        $dicts=Db::name('dict')->where('type', 'sex')->select();
        $this->assign('dicts', $dicts); //字典
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑
     */
    public function edit() {
        $dicts=Db::name('dict')->where('type', 'sex')->select();
        $this->assign('dicts', $dicts); //字典
        return $this->_form($this->table, 'form');
    }


    /**
     * 删除
     */
    public function del() {
        if (DataService::update($this->table)) {
            $id = $this->request->post('id');
            Db::name('{$tbname}')->where('id', $id)->delete();
            $this->success("{$tbcomment}删除成功！", '');
        }
        $this->error("{$tbcomment}删除失败，请稍候再试！");
    }


    /**
     * 禁用
     */
    public function forbid() {
        if (DataService::update($this->table)) {
            $this->success("{$tbcomment}禁用成功！", '');
        }
        $this->error("{$tbcomment}禁用失败，请稍候再试！");
    }

    /**
     * 恢复
     */
    public function resume() {
        if (DataService::update($this->table)) {
            $this->success("{$tbcomment}启用成功！", '');
        }
        $this->error("{$tbcomment}启用失败，请稍候再试！");
    }

}