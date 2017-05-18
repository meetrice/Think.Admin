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
use think\helper\Str;

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
//        $tables = Db::query('SHOW TABLES');
        $tbschemasql = "SELECT TABLE_SCHEMA, TABLE_NAME,TABLE_COMMENT FROM information_schema.tables where TABLE_SCHEMA ='".config('database.database')."'";
//        var_dump($tbschemasql);die;
//        $tables = Db::execute("use information_schema");
        $tables = Db::query($tbschemasql);

        $table_k = 'Tables_in_xiaoma';

        $tablename = $this->request->get('table');
        $columns = array();
        $info = DB::query("SHOW TABLE STATUS FROM `" . config('database.database') . "` WHERE `name` = '" . $tablename . "'");

//        var_dump($tables);die;
        if(count($info)>=1)
        {
            $info = $info[0];
        }
        if($tablename != null)
        {
            foreach(DB::query("SHOW FULL COLUMNS FROM `$tablename`") as $column)
            {
                $columns[] = $column;
            }
        }
        $isno = array(
            "1" => "是",
            "0" => "否",
            "" => "否",
        );

        $this->assign('default', array('NULL','USER_DEFINED','CURRENT_TIMESTAMP'));
        $this->assign('tbtypes', array('bigint','binary','bit','blob','bool','boolean','char','date','datetime','decimal','double','enum','float','int','longblob','longtext','mediumblob','mediuminit','mediumtext','numerice','real','set','smallint','text','time','timestamp','tinyblob','tinyint','tinytext','varbinary','varchar','year'));
        $this->assign('engine', array('MyISAM','InnoDB'));

        $this->assign('tablename', $tablename);
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

        $table = $this->request->get('table');
        $field = $this->request->get('field');
        $type = $this->request->get('type');
        $lenght = $this->request->get('lenght');
        $default = $this->request->get('default');
        $notnull = $this->request->get('notnull');
        $key = $this->request->get('key');
        $ai = $this->request->get('ai');
        $require = $this->request->get('require');
        $displayname = $this->request->get('displayname');
        $formtype = $this->request->get('formtype');
        $validate = $this->request->get('validate');
        $dict = $this->request->get('dict');
        $isgrid = $this->request->get('isgrid');
        $isform = $this->request->get('isform');

        $this->assign('table', $table);
        $this->assign('field', $field);
        $this->assign('type', $type);
        $this->assign('lenght', $lenght);
        $this->assign('default', $default);
        $this->assign('notnull', $notnull);
        $this->assign('key', $key);
        $this->assign('ai', $ai);
        $this->assign('require', $require);
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
            "textarea" => "textarea",
            "password" => "password",
            "hidden" => "hidden",
            "radio" => "radio",
            "select" => "select",
            "checkbox" => "checkbox",
            "switch" => "switch",
            "image" => "image",
        );
        $this->assign('fieldformtypes', $fieldformtypes);


        $validatetypes = array(
            "email" => "email",
            "url" => "url",
        );
        $this->assign('validatetypes', $validatetypes);

        return $this->_form($this->table, 'form');
    }

    public function savetable() {
        $tablename = $this->request->param('tablename');
        $oname = $this->request->param('oname');
        $engine = $this->request->param('engine');
        $comment = $this->request->param('comment');
        $ocomname = $this->request->param('ocomname');
        $sql='';
//        ALTER TABLE <table> COMMENT = 'blah blah blah';
        if ($tablename!=$oname){
            $sql = 'Rename Table '.$oname.' TO '.$tablename.'';
            try {
                DB::execute( $sql );
            }catch(Exception $e){
                return $this->error('只能有一个主键');
            }
        }



        if ($comment!=$ocomname){
         $sql = " ALTER TABLE $tablename COMMENT '".$comment."'";
            try {
                DB::execute( $sql );
            }catch(Exception $e){
                return $this->error('只能有一个主键');
            }

        }


        return $this->success('保存成功!');

    }
    public function save() {

//        extract($_POST);
        $field = $this->request->param('field');
        $table = $this->request->param('table');
        $key="" ;
            if($this->request->param('key')=='on'){
                $key = ' ,add primary key('.$field.')';
            }else if($this->request->param('key')=='off'){
                $key =  ' ,DROP PRIMARY KEY' ;
            }

        $type = $this->request->param('type');

        $lenght     = self::lenght($type,$this->request->param('lenght'));
        $default    = $this->request->param('default');
        $null       = ($this->request->param('null')=='on') ? 'NOT NULL' : '' ;
//        var_dump($this->request->get('null'));die;
        $ai         ='';
                        if( $this->request->param('ai')=='on'){
                        $ai = ' AUTO_INCREMENT';
                        }else if( $this->request->param('ai')=='off'){
                            $ai =  '';
                        }


        if ($null != "" and $ai =='AUTO_INCREMENT') {
            $default = '';
        } elseif ($null == "" && $default !='') {

            $default = "DEFAULT '".$default."'";
        } else {
            if($null == 'NOT NULL')
            {
                $default = "";
            }  else {
                $default = " DEFAULT NULL ";
            }

        }

//        如果没有传注释,则从表单获得
        $comment =trim($this->request->param('comment'));

        $displayname = trim($this->request->param('displayname'));
        $formtype = trim($this->request->param('formtype'));
        $validate = trim($this->request->param('validate'));
        $dict = trim($this->request->param('dict'));
        $require = $this->request->param('require')=='on'?'1':'0';
        $isgrid = $this->request->param('isgrid')=='on'?'1':'0';
        $isform = $this->request->param('isform')=='on'?'1':'0';
        //如果没有从列表中传入comment,则表明从表单更新,则新建一个注释
        if (is_null($comment)||$comment==''){
            $comment = "COMMENT '[{\"name\":\"$displayname\",\"type\":\"$formtype\",\"validate\":\"$validate\",\"dict\":\"$dict\",\"isgrid\":\"$isgrid\",\"isform\":\"$isform\",\"require\":\"$require\"}]'";
        //否则是从表格中的按钮来,带入注释,但是要修改其中特定的值
         }else{
            $commentarray = json_decode(urldecode($comment),true);
            if(!empty($this->request->param('require'))){
                foreach ($commentarray as $ckey => $value) {
                    if( $ckey == "require"){
                        $commentarray[0]["require"] = "$require";
                    }
                }
            }
            if(!empty($this->request->param('isgrid'))){
                foreach ($commentarray as $ckey => $value) {
                    if( $ckey == "isgrid"){
                        $commentarray[0]["isgrid"] = "$require";
                    }
                }
            }
            if(!empty($this->request->param('isform'))){
                foreach ($commentarray as $ckey => $value) {
                    if( $ckey == "isform"){
                        $commentarray[0]["isform"] = "$require";
                    }
                }
            }
            $comment  = " COMMENT '".json_encode($commentarray)."'";
        }


        $currentfield = $this->request->param('currentfield');

//        var_dump((string)$key);die;
        if( $currentfield !='')
        {
            if($currentfield == $field )
            {
                $sql = " ALTER TABLE `" . $table . "` MODIFY COLUMN `$field` $type  $lenght   $null $default $ai  $comment $key";
            }   else {
                $sql = " ALTER TABLE `" . $table . "` CHANGE  `$currentfield` `$field`  $type $lenght   $null $default $ai  $comment $key";
            }

        } else {
            $sql = " ALTER TABLE `" . $table . "` ADD COLUMN `$field` $type  $lenght   $null $default $ai  $comment $key";
        }
//var_dump($sql);die;

        try {

            DB::execute( $sql );

        }catch(Exception $e){

            return $this->error('只能有一个主键');
        }
//       "/admin.html#/admin/codegen/index.html?table=".$table
        return $this->success('保存成功!');
    }
    function replace_key($find, $newvalue, $array) {
        $arr = array();
        foreach ($array as $key => $value) {
            if ($key == $find) {
                $arr[$key] = $newvalue;
            }else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
    //生成
    public function generate()
    {
        $table = $this->request->post('id');
        if (!isset($table) || empty($table)) {
            return $this->error('请选择数据表');
        }

        $moduledir= 'admin';//模块名
        $classname='';//类名 文件名
        $viewfilename='';//类名 文件名
        if (strpos($table,'_')){
//            $module = Str::studly($table);
            $fixs = explode('_',$table);
            $moduledir = lcfirst($fixs[0]);

            for ($x=1; $x<count($fixs); $x++) {
                $classname = $classname.lcfirst($fixs[$x]);
                $viewfilename = $viewfilename.lcfirst($fixs[$x]);
            }
            $classname = ucfirst($classname);
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

        $this->assign('moduledir', $moduledir); //列数据
        $this->assign('classname', $classname); //列数据
        $this->assign('tbname', ucfirst($table)); //列数据
        $this->assign('tbcomment', $tabletatus[0]['Comment']); //列数据


        $listview_content = $this->fetch($template_listview);//列表视图
        $formview_content = $this->fetch($template_formview);//表单视图
        $formview_content = str_replace('[SELF]', '__SELF__', $formview_content);//表单视图中特殊处理

        $filename = basename($template_listview);
        $formfilename = basename($template_formview);

        //生成主列表视图
        if ((is_dir($filetarget) || mkdir($filetarget)) && (is_dir($filetarget . '/'.$moduledir.'/') || mkdir($filetarget . '/'.$moduledir.'/')) && (is_dir($filetarget . '/'.$moduledir.'/view') || mkdir($filetarget . '/'.$moduledir.'/view'))) {
            file_put_contents($filetarget . '/'.$moduledir.'/view/' . $viewfilename . '.' . $filename, $listview_content);
        }

        //生成表单视图
        if ((is_dir($filetarget) || mkdir($filetarget)) && (is_dir($filetarget . '/'.$moduledir.'/') || mkdir($filetarget . '/admin/')) && (is_dir($filetarget . '/'.$moduledir.'/view') || mkdir($filetarget . '/'.$moduledir.'/view'))) {
            file_put_contents($filetarget . '/'.$moduledir.'/view/' . $viewfilename . '.' . $formfilename, $formview_content);
        }

        //生成控制器
        $controller_content = $this->fetch($template_controller);//列表视图

        $controller_content = str_replace('[php]', '<?php', $controller_content);
        if ((is_dir($filetarget) || mkdir($filetarget)) && (is_dir($filetarget . '/'.$moduledir.'/') || mkdir($filetarget . '/'.$moduledir.'/')) && (is_dir($filetarget . '/'.$moduledir.'/controller') || mkdir($filetarget . '/'.$moduledir.'/controller'))) {
            file_put_contents($filetarget . '/'.$moduledir.'/controller/' . $classname . '.php', $controller_content);
        }
        $this->success("生成成功！", '');
    }

    static function lenght( $type , $lenght)
    {
        if($lenght == '')
        {
            switch (strtolower(trim( $type))) {
                default ;
                    $lenght = '';
                    break;
                case 'bit':
                    $lenght = '(1)';
                    break;
                case 'tinyint':
                    $lenght = '(4)';
                    break;
                case 'smallint':
                    $lenght = '(6)';
                    break;
                case 'mediumint':
                    $lenght = '(9)';
                    break;
                case 'int':
                    $lenght = '(11)';
                    break;
                case 'bigint':
                    $lenght = '(20)';
                    break;
                case 'decimal':
                    $lenght = '(10,0)';
                    break;
                case 'char':
                    $lenght = '(50)';
                    break;
                case 'varchar':
                    $lenght = '(255)';
                    break;
                case 'binary':
                    $lenght = '(50)';
                    break;
                case 'varbinary':
                    $lenght = '(255)';
                    break;
                case 'year':
                    $lenght = '(4)';
                    break;

            }
            return $lenght;
        } else {
            return "( $lenght )" ;
        }
    }


}
