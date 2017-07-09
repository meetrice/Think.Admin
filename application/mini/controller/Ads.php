<?php

// +----------------------------------------------------------------------
// | 生成器
// +----------------------------------------------------------------------
// | 说明。
// +----------------------------------------------------------------------
// | Author: Meetrice <meetrice@qq.com> <http://www.rc3cr.com>
// +----------------------------------------------------------------------
namespace app\mini\controller;
use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Db;

    class Ads extends BasicAdmin {
        /**
         * 默认检查用户登录状态
         * @var bool
         */
        public $checkLogin = false;

        /**
         * 默认检查节点访问权限
         * @var bool
         */
        public $checkAuth = false;
    public $table = 'Mini_ads';

    /**
    * 列表
    */
    public function index() {
            $this->title = '管理汽车';
        return parent::_list($this->table);
    }

    public function rest() {
        $sexdicts=Db::name('Mini_ads')->select();
//            $userModel = model('User');
//            $param = $this->param;
//            $keywords = !empty($param['keywords']) ? $param['keywords']: '';
//            $page = !empty($param['page']) ? $param['page']: '';
//            $limit = !empty($param['limit']) ? $param['limit']: '';
//            $data = $userModel->getDataList($keywords, $page, $limit);
////        var_dump($data);die;
            return json(resultArray(['data' => $sexdicts]));
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


            if(isset($data['recom']) && $data['recom']=='on'){
                    $data['recom']=1;
                }else{
                    $data['recom']=0;
                }
            

        }
    }

}