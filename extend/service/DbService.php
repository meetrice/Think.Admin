<?php
// +----------------------------------------------------------------------
// | Think.Admin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/Think.Admin
// +----------------------------------------------------------------------

namespace service;

use think\Db;
use think\Request;

/**
 * 操作日志服务
 * Class LogService
 * @package service
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/24 13:25
 */
class DbService {

    /**
     * 获取数据操作对象
     * @return \think\db\Query
     */
    protected static function db() {
        return Db::name('SystemLog');
    }



//得到表的字段信息
    public static function getTableColumn($table_name){
        $result = Db::query("select * from INFORMATION_SCHEMA.COLUMNS where table_name='{$table_name}'");
        if(isset($result)){
            return $result;
        }else{
        }
    }

}