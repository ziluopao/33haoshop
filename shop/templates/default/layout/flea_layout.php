<?php defined('In33hao') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php if ($output['goods_title']){ echo $output['goods_title'].' - '.$GLOBALS['setting_config']['flea_site_title'];}else{echo $GLOBALS['setting_config']['flea_site_title'];}?></title>
<meta name="keywords" content="<?php if ($output['seo_keywords']){ echo $output['seo_keywords'].',';}echo $GLOBALS['setting_config']['flea_site_keywords']; ?>" />
<meta name="description" content="<?php if ($output['seo_description']){ echo $output['seo_description'].',';}echo $GLOBALS['setting_config']['flea_site_description']; ?>" />
<meta name="author" content="33HAO">
<meta name="copyright" content="33HAO Inc. All Rights Reserved">
<meta name="renderer" content="webkit">
<meta name="renderer" content="ie-stand">
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body { _behavior: url(<?php echo SHOP_TEMPLATES_URL;
?>/css/csshover.htc);
}
</style>
<link rel="shortcut icon" href="<?php echo BASE_SITE_URL;?>/favicon.ico" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/flea.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/flea/common.js" charset="utf-8"></script>
<!--弹出层-->
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/flea/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/flea/jquery.flea_area.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
$(function(){
	//首页左侧分类菜单
	$(".category ul.menu").find("li").each(
		function() {
			$(this).hover(
				function() {
				    var cat_id = $(this).attr("cat_id");
					var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
					menu.show();
					$(this).addClass("hover");					
					var menu_height = menu.height();
					if (menu_height < 60) menu.height(80);
					menu_height = menu.height();
					var li_top = $(this).position().top;
					$(menu).css("top",-li_top + 37);
				},
				function() {
					$(this).removeClass("hover");
				    var cat_id = $(this).attr("cat_id");
					$(this).find("div[cat_menu_id='"+cat_id+"']").hide();
				}
			);
		}
	);
	$(".head-user-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
    <?php if (C('fullindexer.open')) { ?>
	// input ajax tips
	$('#keyword').focus(function(){
		if ($(this).val() == $(this).attr('title')) {
			$(this).val('').removeClass('tips');
		}
	}).blur(function(){
		if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
			$(this).addClass('tips').val($(this).attr('title'));
		}
	}).blur().autocomplete({
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                 $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#top_search_form').submit();
		}
	});
	<?php } ?>

	$('#button').click(function(){
	    if ($('#keyword').val() == '') {
		    if ($('#keyword').attr('data-value') == '') {
			    return false
			} else {
				window.location.href="<?php echo SHOP_SITE_URL?>/index.php?act=search&op=index&keyword="+$('#keyword').attr('data-value');
			    return false;
			}
	    }
	});
	$(".head-search-bar").hover(null,
	function() {
		$('#search-tip').hide();
	});
	// input ajax tips
	$('#keyword').focus(function(){
		if($('#search_act').val()=='search') {
			$('#search-tip').show();
		} else {
			$('#search-tip').hide();
		}
		}).autocomplete({
		//minLength:0,
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                    $('#search-tip').hide();
                    $(".head-search-bar").unbind('mouseover');
                    $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#top_search_form').submit();
		}
	});
	$('#search-his-del').on('click',function(){$.cookie('<?php echo C('cookie_pre')?>his_sh',null,{path:'/'});$('#search-his-list').empty();});
	
	$("#close").click(function(){
	     $("#j_headercitylist").css("display","none");
	});
	if ($.cookie('flea_area') != null && $.cookie('flea_area') != ''){
		$('#cityname').html($.cookie('flea_area'));
		$('#show_area').html('<?php echo $lang['flea_index_area']; ?>');
	}else{
		$('#show_area').html('<?php echo $lang['flea_all_country']?>');
	}
	
});
</script>
</head>
<body>
<!-- PublicTopLayout Begin -->
<?php require_once template('layout/layout_top');?>
<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1>
    <div class="logo-banner">
    	<!--地区-->
  <span id="cityname"></span>
  <div id="cityblock">
  <div id="show_area"></div>
  <ul id="j_headercitylist">
  <li onClick="areaGo(0,'')"><?php echo $lang['flea_all_country']?></li>
   <?php if($output['area_one_level']){?>
	 <?php foreach($output['area_one_level'] as $val){?>
		<li id="<?php echo $val['flea_area_id'];?>">
			<?php echo $val['flea_area_name'];?>
		</li>
	 <?php };?>
   <?php };?>
   <a id="close" href="javascript:void(0)">X</a>
  </ul>
  <ul id="citylist"></ul>
  </div>
    
    
    </div>
   
    <div class="head-search-layout">
      <div class="head-search-bar" id="head-search-bar">
        <form action="index.php" method="get" class="search-form" id="top_search_form">
          <input name="act" id="search_act" value="flea_class" type="hidden">
          <input name="key_input" id="keyword" type="text" class="input-text" value="<?php echo $keyword;?>" maxlength="60" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" placeholder="请输入您要搜索的闲置关键字" x-webkit-grammar="builtin:search" autocomplete="off" />
          <input type="submit" id="button" value="搜闲置" class="input-submit">
        </form>
      </div>
      <div class="keyword">
        <ul>
          <?php if(is_array($output['flea_hot_search']) and !empty($output['flea_hot_search'])) { foreach($output['flea_hot_search'] as $val) { ?>
       <li><a href="index.php?act=flea_class&key_input=<?php echo urlencode($val);?>"><?php echo $val; ?></a></li>
        <?php } }?>
        </ul>
      </div>
    </div>
    <div class="head-user-menu">
      <div class="flea-write">
        <a href="index.php?act=member_flea&op=add_goods"><i></i>我要卖闲置</a>
      </div>
    </div>
  </header>
</div>
<!-- PublicHeadLayout End --> 

<!-- publicNavLayout Begin -->
<nav class="public-nav-layout <?php if($output['channel']) {echo 'channel-'.$output['channel']['channel_style'].' channel-'.$output['channel']['channel_id'];} ?>">
  <div class="wrapper">
    <div class="all-category">
      <?php require template('layout/home_goods_class');?>
    </div>
    <ul class="site-menu">
      <li><a href="<?php echo BASE_SITE_URL;?>" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><span><?php echo $lang['nc_index'];?></span></a></li>
      <?php if (C('groupbuy_allow')){ ?>
      <li><a href="<?php echo urlShop('show_groupbuy', 'index');?>" <?php if($output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_groupbuy'];?></a></li>
      <?php } ?>
      <li><a href="<?php echo urlShop('brand', 'index');?>" <?php if($output['index_sign'] == 'brand' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_brand'];?></a></li>
	<li><a href="<?php echo urlShop('promotion','index');?>" <?php if($output['index_sign'] == 'promotion' && $output['index_sign'] != '0') {echo 'class="current"';} ?>>疯抢</a></li>
      <?php if (C('points_isuse') && C('pointshop_isuse')){ ?>
      <li><a href="<?php echo urlShop('pointshop', 'index');?>" <?php if($output['index_sign'] == 'pointshop' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_pointprod'];?></a></li>
      <?php } ?>
      <?php if (C('cms_isuse')){ ?>
      <li><a href="<?php echo urlShop('special', 'special_list');?>" <?php if($output['index_sign'] == 'special' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_special'];?></a></li>
      <?php } ?>
 <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
      <?php foreach($output['nav_list'] as $nav){?>
      <?php if($nav['nav_location'] == '1'){?>
      <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        switch($nav['nav_type']) {
            case '0':
                echo ' href="' . $nav['nav_url'] . '"';
                break;
            case '1':
                echo ' href="' . urlShop('search', 'index',array('cate_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '2':
                echo ' href="' . urlMember('article', 'article',array('ac_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '3':
                echo ' href="' . urlShop('activity', 'index', array('activity_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
        }
        ?>><?php echo $nav['nav_title'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  <!--地区--> 
  <?php if($output['area_two_level']){?>
	<?php foreach($output['area_two_level'] as $val){?>
	  <div style="display:none;" id="hidden_<?php echo $val['id'];?>"><?php echo $val['content'];?></div>
	<?php };?>
  <?php };?>
  <!--地区-->
  </div>
</nav>
<?php require_once($tpl_file);?>
<?php require_once template('footer');?>
</body>
</html>