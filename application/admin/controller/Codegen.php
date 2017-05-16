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
    public $table = 'SystemAuth';

    /**
     * 权限列表
     */
    public function index()
    {

        //得到所有的数据库
        $databases = Db::query('show databases');
        $tables = Db::query('SHOW TABLES');
        $table_k = 'Tables_in_xiaoma';

        $tablename = $this->request->get('id');
        $columns = array();
        $info = DB::query("SHOW TABLE STATUS FROM `" . config('database.database') . "` WHERE `name` = '" . $tablename . "'");

//        var_dump($info);die;
        if(count($info)>=1)
        {
            $info = $info[0];
        }
        if($tablename != null)
        {
            foreach(DB::query("SHOW FULL COLUMNS FROM `$tablename`") as $column)
            {
                // echo '<pre>';print_r($column);echo '</pre>';
                $columns[] = $column;
            }
        }
        $isno = array(
            "1" => "是",
            "0" => "否",
            "" => "否",
        );

//        var_dump($columns);die;
        $this->assign('default', array('NULL','USER_DEFINED','CURRENT_TIMESTAMP'));
        $this->assign('tbtypes', array('bigint','binary','bit','blob','bool','boolean','char','date','datetime','decimal','double','enum','float','int','longblob','longtext','mediumblob','mediuminit','mediumtext','numerice','real','set','smallint','text','time','timestamp','tinyblob','tinyint','tinytext','varbinary','varchar','year'));
        $this->assign('engine', array('MyISAM','InnoDB'));

        $this->assign('isno', $isno);
        $this->assign('info', $info);
        $this->assign('columns', $columns);
        $this->assign('tables', $tables);
        $this->assign('table_k', $table_k);
        return view();
    }
    /**
     * 编辑
     */
    public function editcolumn() {

        $field = $this->request->get('field');
        $type = $this->request->get('type');
        $lenght = $this->request->get('lenght');
        $default = $this->request->get('default');
        $notnull = $this->request->get('notnull');
        $key = $this->request->get('key');
        $ai = $this->request->get('ai');
        $required = $this->request->get('required');
        $displayname = $this->request->get('displayname');
        $formtype = $this->request->get('formtype');
        $validate = $this->request->get('validate');
        $dict = $this->request->get('dict');
        $isgrid = $this->request->get('isgrid');
        $isform = $this->request->get('isform');

        $this->assign('field', $field);
        $this->assign('type', $type);
        $this->assign('lenght', $lenght);
        $this->assign('default', $default);
        $this->assign('notnull', $notnull);
        $this->assign('key', $key);
        $this->assign('ai', $ai);
        $this->assign('required', $required);
        $this->assign('displayname', $displayname);
        $this->assign('formtype', $formtype);
        $this->assign('validate', $validate);
        $this->assign('dict', $dict);
        $this->assign('isgrid', $isgrid);
        $this->assign('isform', $isform);

        $dicttypes=Db::name('dict')->where('status', '1')->field('type')->distinct("type")->select();
        $this->assign('dicttypes', $dicttypes); //字典

        $fieldtypes = array('bigint','binary','bit','blob','bool','boolean','char','date','datetime','decimal','double','enum','float','int','longblob','longtext','mediumblob','mediuminit','mediumtext','numerice','real','set','smallint','text','time','timestamp','tinyblob','tinyint','tinytext','varbinary','varchar','year');
        $this->assign('fieldtypes', $fieldtypes);

        $fieldformtypes = array(
            "input" => "input",
            "select" => "select",
            "checkbox" => "checkbox",
        );
        $this->assign('fieldformtypes', $fieldformtypes);


        $validatetypes = array(
            "email" => "email",
            "url" => "url",
        );
        $this->assign('validatetypes', $validatetypes);

        return $this->_form($this->table, 'form');
    }


    //生成
    public function generate()
    {
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

        $controller_content = str_replace('[php]', '<?php', $controller_content);
        if ((is_dir($filetarget) || mkdir($filetarget)) && (is_dir($filetarget . '/admin/') || mkdir($filetarget . '/admin/')) && (is_dir($filetarget . '/admin/controller') || mkdir($filetarget . '/admin/controller'))) {
            file_put_contents($filetarget . '/admin/controller/' . ucfirst($table) . '.php', $controller_content);
        }
        $this->success("生成成功！", '');
    }



}
