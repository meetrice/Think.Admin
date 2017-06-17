<?php

// +----------------------------------------------------------------------
// | 生成器
// +----------------------------------------------------------------------
// | 说明。
// +----------------------------------------------------------------------
// | Author: Meetrice <meetrice@qq.com> <http://www.rc3cr.com>
// +----------------------------------------------------------------------
namespace app\erp\controller;
use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Db;

    class Car extends BasicAdmin {

    public $table = 'Erp_car';

    /**
    * 列表
    */
    public function index() {
                $sexarray=[];
            $sexdicts=Db::name('dict')->where('type', 'sex')->field('dkey,dvalue')->select();
            foreach ($sexdicts as $vo) {
            $sexarray[$vo['dkey']] = $vo['dvalue'];
            }
            $this->assign('sexdicts', $sexarray); //字典
                            $departarray=[];
            $departdicts=Db::name('dict')->where('type', 'depart')->field('dkey,dvalue')->select();
            foreach ($departdicts as $vo) {
            $departarray[$vo['dkey']] = $vo['dvalue'];
            }
            $this->assign('departdicts', $departarray); //字典
                            $interestarray=[];
            $interestdicts=Db::name('dict')->where('type', 'interest')->field('dkey,dvalue')->select();
            foreach ($interestdicts as $vo) {
            $interestarray[$vo['dkey']] = $vo['dvalue'];
            }
            $this->assign('interestdicts', $interestarray); //字典
                        $this->title = '管理汽车';
        return parent::_list($this->table);
    }
    /**
    * 添加
    */
    public function add() {

            $sexdicts=Db::name('dict')->where('type', 'sex')->field('dkey,dvalue')->select();
        $this->assign('sexdicts', $sexdicts); //字典
                    $departdicts=Db::name('dict')->where('type', 'depart')->field('dkey,dvalue')->select();
        $this->assign('departdicts', $departdicts); //字典
                    $interestdicts=Db::name('dict')->where('type', 'interest')->field('dkey,dvalue')->select();
        $this->assign('interestdicts', $interestdicts); //字典
            
        return $this->_form($this->table, 'form');
    }

    /**
    * 编辑
    */
    public function edit() {
        $sexdicts=Db::name('dict')->where('type', 'sex')->field('dkey,dvalue')->select();
    $this->assign('sexdicts', $sexdicts); //字典
            $departdicts=Db::name('dict')->where('type', 'depart')->field('dkey,dvalue')->select();
    $this->assign('departdicts', $departdicts); //字典
            $interestdicts=Db::name('dict')->where('type', 'interest')->field('dkey,dvalue')->select();
    $this->assign('interestdicts', $interestdicts); //字典
                return $this->_form($this->table, 'form');
    }


    /**
    * 删除
    */
    public function del() {
        if (DataService::update($this->table)) {
        $id = $this->request->post('id');
        Db::name($this->table)->where('id', $id)->delete();
        $this->success("汽车删除成功！", '');
        }
        $this->error("汽车删除失败，请稍候再试！");
    }


    /**
    * 禁用
    */
    public function forbid() {
        if (DataService::update($this->table)) {
        $this->success("汽车禁用成功！", '');
        }
        $this->error("汽车禁用失败，请稍候再试！");
    }

    /**
    * 恢复
    */
    public function resume() {
        if (DataService::update($this->table)) {
        $this->success("汽车启用成功！", '');
        }
        $this->error("汽车启用失败，请稍候再试！");
    }

    /**
    * 表单数据默认处理
    * @param array $data
    */
    public function _form_filter(&$data) {
        if ($this->request->isPost()) {


            if (isset($data['interest']) && is_array($data['interest'])) {
                $data['interest'] = join(',', $data['interest']);
                }
                if(isset($data['recom']) && $data['recom']=='on'){
                    $data['recom']=1;
                }else{
                    $data['recom']=0;
                }
            

        }
    }

}