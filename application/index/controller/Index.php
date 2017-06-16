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

namespace app\index\controller;

use think\Controller;
use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Db;
use think\View;


/**
 * 网站入口控制器
 * Class Index
 * @package app\index\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/04/05 10:38
 */
class Index extends Controller {

    /**
     * 网站入口
     */
    public function index() {
//        $this->redirect('@admin');
//        $page = $this->request->get(1);
        $page = $this->request->path();



        if($page !='/') :


            var_dump('etwstw');die;

        else :
            $sql = Db::name('CmsPages')->where('default', '1')->order('sort asc,id asc')->select();
//            var_dump($sql);die;
//            $sql = DB::table('tb_pages')->where('default',1)->get();
            if(count($sql)>=1)
            {
                $row = $sql[0];
                $this->assign('pageTitle', $row['title']);
                $this->assign('pageNote', $row['note']);
                $this->assign('breadcrumb', 'inactive');
                $this->assign('pageMetakey', $row['metakey']);
                $this->assign('pageMetadesc', $row['metadesc']);
                $this->assign('filename', $row['filename']);

                if(file_exists($_SERVER['DOCUMENT_ROOT'].'/application/index/layouts/'.sysconf('theme').'/template/'.$row['filename'].'.template.php') &&  $row['filename'] !='' )               {
                    $page_template = $_SERVER['DOCUMENT_ROOT'].'/application/index/layouts/'.sysconf('theme').'/template/'.$row['filename'];
                } else {
                    $page_template = $_SERVER['DOCUMENT_ROOT'].'/application/index/layouts/'.sysconf('theme').'/template/page.blade.php';
                }
//var_dump($page_template);die;
                $this->assign('pages', $page_template);
                $this->assign('content', ToolsService::formatContent($row['note']));


                $page = $_SERVER['DOCUMENT_ROOT'].'/application/index/layouts/'.sysconf('theme').'/index.php';
//                return view($page,$this->data);
                return $this->fetch($page);

            } else {

                return ' No Default page set up !';
            }

        endif;





    }

}
