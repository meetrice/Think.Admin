<?php

// +----------------------------------------------------------------------
// | Think.Admin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/Think.Admin
// +----------------------------------------------------------------------

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use service\DbService;
use think\Db;

/**
 * 系统权限管理控制器
 * Class Auth
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 18:13
 */
class Codegen extends BasicAdmin
{

    /**
     * 默认数据模型
     * @var string
     */
    protected $table = 'SystemAuth';

    /**
     * 权限列表
     */
    public function index()
    {

        //得到所有的数据库
        $databases = Db::query('show databases');
        $tables = Db::query('SHOW TABLES');
        $table_k = 'Tables_in_xiaoma';
//var_dump($databases);die;
//        $this->assign('databases',$databases);
        $this->assign('tables', $tables);
        $this->assign('table_k', $table_k);
        return view();
    }


    //生成
    public function generate()
    {
        //需要处理的文件
        $path = '../template/default';
        $files = array(
            'admin' => array(
                'controller' => '/admin/controller/Base.php', //1 控制器模型
            ),
            'view' => array(
                'index' => '/admin/view/index.html',// 列表视图
            ),
        );

        $table = $this->request->post('id');
        if (!isset($table) || empty($table)) {
            return $this->error('请选择数据表');
        }

        $tabletatus = Db::query('SHOW TABLE STATUS WHERE Name =  "'.$table.'"');

        //得到字段元数据
        $columns = DbService::getTableColumn($table);

        $filetarget = $_SERVER['DOCUMENT_ROOT'].'/application';//生成目标路径

        $template_listview = $_SERVER['DOCUMENT_ROOT'].'/templates/view/index.html';//视图模板路径
        $template_formview = $_SERVER['DOCUMENT_ROOT'].'/templates/view/form.html';//视图模板路径
        $template_controller = $_SERVER['DOCUMENT_ROOT'].'/templates/controller/Base.php.html';//控制器模板路径

        $this->assign('tableInfoArray', $columns); //列数据
        $this->assign('tableComment', $tabletatus[0]['Comment']);//得到表名注释

        $this->assign('tbname', ucfirst($table)); //列数据
        $this->assign('tbcomment', $tabletatus[0]['Comment']); //列数据

//        var_dump(json_decode('{"test":"1234"}',true)['test']);die;

        $listview_content = $this->fetch($template_listview);//列表视图
        $formview_content = $this->fetch($template_formview);//表单视图
        $formview_content = str_replace('[SELF]', '__SELF__', $formview_content);//表单视图中特殊处理

        $filename = basename($template_listview);
        $formfilename = basename($template_formview);

        //生成主列表视图
        if ((is_dir($filetarget) || mkdir($filetarget)) && (is_dir($filetarget . '/admin/') || mkdir($filetarget . '/admin/')) && (is_dir($filetarget . '/admin/view') || mkdir($filetarget . '/admin/view'))) {
            file_put_contents($filetarget . '/admin/view/' . lcfirst($table) . '.' . $filename, $listview_content);
        }

        //生成表单视图
        if ((is_dir($filetarget) || mkdir($filetarget)) && (is_dir($filetarget . '/admin/') || mkdir($filetarget . '/admin/')) && (is_dir($filetarget . '/admin/view') || mkdir($filetarget . '/admin/view'))) {
            file_put_contents($filetarget . '/admin/view/' . lcfirst($table) . '.' . $formfilename, $formview_content);
        }

        //生成控制器
        $controller_content = $this->fetch($template_controller);//列表视图
//        var_dump($controller_content);die;

//        $controller_content = file_get_contents($template_controller);
        $controller_content = str_replace('[php]', '<?php', $controller_content);
        if ((is_dir($filetarget) || mkdir($filetarget)) && (is_dir($filetarget . '/admin/') || mkdir($filetarget . '/admin/')) && (is_dir($filetarget . '/admin/controller') || mkdir($filetarget . '/admin/controller'))) {
            file_put_contents($filetarget . '/admin/controller/' . ucfirst($table) . '.php', $controller_content);
        }
        $this->success("生成成功！", '');
//        $this->error("权限删除失败，请稍候再试！");
    }



}
