<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<title>{{ CNF_APPNAME }} | {{ $pageTitle}}</title>
		<meta name="keywords" content="{{ $pageMetakey }}" />
		<meta name="description" content="{{ $pageMetadesc }}" />
		<meta name="Author" content="meetrice" />

		<!-- mobile settings -->
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
		<link rel="shortcut icon" href="" type="image/x-icon">

		<link rel="stylesheet" href="http://www.rc3cr.com/css/common.css" type="text/css">
		<link rel="stylesheet" href="http://www.rc3cr.com/css/animate.css"/>
		<link rel="stylesheet" href="http://www.rc3cr.com/vendor/slick/slick.css"/>
		<link rel="stylesheet" href="http://www.rc3cr.com/css/aboutus.master.css"/>

		<script type="text/javascript" src="http://www.rc3cr.com/vendor/jquery-1.10.2.min.js"></script>
		<script src="http://www.rc3cr.com/vendor/jquery.transit.js" type="text/javascript"></script>
		<script src="http://www.rc3cr.com/js/public.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://www.rc3cr.com/js/jquertFunc.js"></script>
		<script type="text/javascript" src="http://www.rc3cr.com/js/right-nav.js"></script>
		<script type="text/javascript" src="http://www.rc3cr.com/vendor/slick/slick.min.js"></script>
		<script type="text/javascript" src="http://www.rc3cr.com/vendor/queryloader2.min.js"></script>
		<script type="text/javascript" src="http://www.rc3cr.com/js/head.js"></script>


		<!-- CORE CSS -->
		<link href="{{ asset('frontend') }}/default/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

		<!-- THEME CSS -->
		<link href="{{ asset('frontend') }}/default/css/core.css" rel="stylesheet" type="text/css" />
		<link href="{{ asset('frontend') }}/default/css/post.css" rel="stylesheet" type="text/css" />
		<link href="{{ asset('frontend') }}/default/css/default.css" rel="stylesheet" type="text/css" />
		<!-- PAGE LEVEL SCRIPTS -->
		<link href="{{ asset('frontend') }}/default/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

		<!-- JAVASCRIPT FILES -->
		<script type="text/javascript">var plugin_path = '{{ asset("frontend/default/plugins/") }}/';</script>
		<script type="text/javascript" src="{{ asset('frontend') }}/default/plugins/jquery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="{{ asset('frontend') }}/default/js/default.js"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/parsley.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.jCombo.min.js') }}"></script>

	</head>

	<body class="smoothscroll enable-animation">
		

		<div id="wrapper">

			<!-- Top Bar -->
			<div id="topBar">
				<div class="container">

					<!-- right -->
					<ul class="top-links list-inline pull-right">
						@if(Auth::check())
						<li class="text-welcome hidden-xs">{{ Lang::get('core.welcome_to') }} {{ CNF_APPNAME }}, <strong> {{ Session::get('fid')}}</strong></li>
						<li>
							<a class="dropdown-toggle no-text-underline" data-toggle="dropdown" href="#"><i class="fa fa-user hidden-xs"></i> {{ Lang::get('core.m_myaccount') }}</a>
							<ul class="dropdown-menu pull-right">
								<li><a tabindex="-1" href="{{ url('dashboard') }}"><i class="fa fa-desktop"></i> {{ Lang::get('core.m_dashboard') }}</a></li>
								<li><a tabindex="-1" href="{{ url('user/profile?view=frontend') }}"><i class="fa fa-user"></i> {{ Lang::get('core.m_profile') }}</a></li>
								<li class="divider"></li>
								<li><a tabindex="-1" href="{{ url('user/logout') }}"><i class="glyphicon glyphicon-off"></i> {{ Lang::get('core.m_logout') }}</a></li>
							</ul>
						</li>
						@else
						<li class="text-welcome hidden-xs">Hello , <strong>Guest</strong></li>
						<li>
							<a class="dropdown-toggle no-text-underline" data-toggle="dropdown" href="#"><i class="fa fa-user hidden-xs"></i>{{ Lang::get('core.m_myaccount') }}</a>
							<ul class="dropdown-menu pull-right">
								<li><a tabindex="-1" href="{{ url('user/profile?view=frontend') }}"><i class="fa fa-history"></i> {{ Lang::get('core.signin') }} </a></li>
								<li class="divider"></li>
								<li><a tabindex="-1" href="{{ url('user/register') }}"><i class="glyphicon glyphicon-off"></i> {{ Lang::get('core.signup') }}</a></li>
							</ul>
						</li>
						@endif
					</ul>

					<!-- left -->
					<ul class="top-links list-inline">
						@if(CNF_MULTILANG ==1)		

							<?php 
							$flag ='en';
							$langname = 'English'; 
							foreach(SiteHelpers::langOption() as $lang):
								if($lang['folder'] == $pageLang or $lang['folder'] == CNF_LANG) {
									$flag = $lang['folder'];
									$langname = $lang['name']; 
								}
							endforeach;?>
						<li>
							<a class="dropdown-toggle no-text-underline" data-toggle="dropdown" href="#"><img class="flag-lang" src="{{ asset('sximo/images/flags/'.$flag.'.png') }}" width="16" height="11" alt="lang" /> {{ $langname }}</a>
							<ul class="dropdown-langs dropdown-menu">
								@foreach(SiteHelpers::langOption() as $lang)
								<li><a tabindex="-1" href="{{ url('home/lang/'.$lang['folder'])}}"><img class="flag-lang" src="{{ asset('sximo/images/flags/'.$lang['folder'].'.png') }}" width="16" height="11" alt="lang" /> {{  $lang['name'] }}</a></li>
								@endforeach
								
							</ul>
						</li>
						@endif
					</ul>

				</div>
			</div>
			<!-- /Top Bar -->

			<div id="header" class="sticky shadow-after-3 clearfix">

				<!-- TOP NAV -->
				<header id="topNav">
					<div class="container">

						<!-- Mobile Menu Button -->
						<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
							<i class="fa fa-bars"></i>
						</button>


						<!-- Logo -->
						
							<a class="logo pull-left" style="font-size:30px;" href="{{ url()}}">
							<img src="{{ asset('frontend/default/images/sximo_logo.png')}}" title="{{ CNF_APPNAME }} " style="height: 40px !important" />
							
							</a>
						

	
						@include('layouts/default/menu')


					</div>	
				</div>	


			@include($pages)


			<!-- FOOTER -->
			<footer id="footer" class="footer">


				<div class="flink"><span class="fr">Copyright ©南京炔点软件科技有限公司. All Rights Reserved.</span></div>
				<div class="footerWrap">
					<div class="fContact">
						<p><span style="font-size:14px;">业务咨询：</span><br>
							<span class="ftel">电话：15312083732</span></p>

						<p class="fctxt">地址: 南京市雨花台区安德门大街57号 楚翘城4号楼2层 <br>
							电邮：582197@qq.com<br><br>
							QQ: 582197 </p>
					</div>
					<ul class="footerNav-small">
						<li><a href="/aboutus/">关于我们</a></li>
						<li><a href="/service/">我们的服务</a></li>
						<li><a href="/contactus/map.html">地理位置</a></li>
						<li><a href="/aboutus/joinus.html">伙伴招募</a></li>
					</ul>
					<ul class="footerNav">
						<li class="fNav"><a href="/aboutus/" class="fnavmain">关于我们</a>
							<ul>
								<li><a href="/aboutus/#manro">了解炔点软件</a></li>
								<li><a href="/cases/">客户案例</a></li>
								<li><a href="/aboutus/#history">炔点历程</a></li>
								<li><a href="/aboutus/#views">我们的观点</a></li>
								<li><a href="/contactus/">联系方式</a></li>
							</ul>
						</li>
						<li class="fNav"><a href="/service/" class="fnavmain">我们的服务</a>
							<ul>
								<li><a href="/service/webdesign_servers.html">网站建设</a></li>
								<li><a href="/service/network_marketing.html">网络营销</a></li>
								<li><a href="/service/mobileapp.html">移动APP开发</a></li>
								<li><a href="/service/ecommerce.html">电商运营外包</a></li>
								<li><a href="/service/weboperator.html">网站运营维护</a></li>
								<li><a href="/service/webdeveloper.html">程序开发</a></li>
							</ul>
						</li>
						<li class="fNav"><a href="/news/article.php?colid=211" class="fnavmain">新闻资讯</a>
							<ul>
								<li><a href="/news/article.php?colid=220">公司资讯</a></li>
								<li><a href="/news/article.php?colid=221">行业新闻</a></li>
								<li><a href="/news/article.php?colid=222">设计文章</a></li>
							</ul>
						</li>
						<li class="fNav"><a href="/aboutus/joinus.html" class="fnavmain">伙伴招募</a>
							<ul>
								<li><a href="/aboutus/joinus.html#addesigner">资深网页设计师</a></li>
								<li><a href="/aboutus/joinus.html#prdesigner">实习网页设计师</a></li>
								<li><a href="/aboutus/joinus.html#pmadmin">项目经理</a></li>
							</ul>
						</li>
						<li class="fNav"><a class="fnavmain">关注我们</a>

							<div class="follow">
								<a href="http://weibo.com/manrosh" class="sina" target="_blank"></a>
								<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2220309840&amp;site=qq&amp;menu=yes" class="wechat" target="_blank"></a>
							</div>
						</li>
						<li class="fNav">
							<div class="back-top"></div>
						</li>
					</ul>
					<div class="phone-foot">
						<ul class="footerNav phone-fnav">
							<li class="fNav"><a class="fnavmain">关注我们</a>
								<div class="follow">
									<a href="http://weibo.com/manrosh" class="sina" target="_blank"></a>
									<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2220309840&amp;site=qq&amp;menu=yes" class="wechat" target="_blank"></a>
								</div>
							</li>
							<li class="fNav">
								<div class="back-top-p"></div>
							</li>
						</ul>
					</div>
				</div>


			</footer>
			<!-- /FOOTER -->

		</div>
		<!-- /wrapper -->
		<!-- SCROLL TO TOP -->
		<a href="#" id="toTop"></a>

		<script src="http://www.rc3cr.com/js/aboutus.js"></script>

	</body>
</html>