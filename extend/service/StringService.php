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

namespace service;

/**
 * 字符串工具服务
 * Class ToolsService
 * @package service
 * @author meetrice <meetrice@qq.com>
 * @date 2016/10/25 14:49
 */
class StringService {
    public static function formatContent( $content )
    {
        // character(s) to escape
        $x = '`~!#^*()-_+={}[]:\'"<>.';
        $content = preg_replace_callback('#(?<!\\\)!!([^\n]+?)!!#', function($m) use($x) {
            $s = htmlentities($m[1], ENT_NOQUOTES);
            return  self::__fnc($s, $x);
        }, $content);
        $content = preg_replace_callback('#\<php\>(.+?)\<\/php\>#s',create_function(
            '$matches',

            '$attr["code"] = $matches[1];
		    return  view("core.code", $attr);'
        ), $content);
        $content = preg_replace_callback('#\<pre\>(.+?)\<\/pre\>#s',create_function(
            '$matches',
            'return "<pre class=\"prettyprint lang-php\">".htmlentities($matches[1])."</pre>";'
        ), $content);

        return $content;
    }

}
