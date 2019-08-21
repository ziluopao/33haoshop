<?php defined('In33hao') or exit('Access Invalid!');?>
<script src="<?php echo SHOP_RESOURCE_SITE_URL.'/js/search_goods.js';?>"></script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
 <div id="nav-search" class="wrapper nch-breadcrumb-layout">
  <?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
      <?php foreach($output['nav_link_list'] as $nav_link){?>
          <?php if(!empty($nav_link['link'])){?>
		  <?php
					$model_goods_class = Model('goods_class');
					$nav_info = $model_goods_class->getGoodsClassList(array('gc_id'=>$nav_link['id']));
					$where = array();
					$where['gc_id'] = array('not in',$nav_link['id']);
					$where['gc_parent_id'] = $nav_info[0]['gc_parent_id'];
					$nav_list = $model_goods_class->getGoodsClassList($where);
		  ?>
    <div class="nch-breadcrumb">
     <span class="sort-box"> <a href="<?php echo $nav_link['link'];?>" class="current"><?php echo $nav_link['title'];?><i class="drop-arrow"></i></a>
     <?php if(!empty($nav_list)){?>
      <div class="sort-sub">
        <ul>
         <?php foreach($nav_list as $key => $val){?>
         <li><a href="<?php echo urlShop('search', 'index', array('cate_id' => $val['gc_id'], 'keyword' => $_GET['keyword']));?>"><?php echo $val['gc_name']?></a></li>
         <?php }?> 
        </ul>
      </div>
      <?php }?>  
       </span><span class="arrow">&gt;</span>
       </div>
        <?php }?>
    <?php }?>
     <?php }?>
	 <?php if((isset($output['checked_brand']) && is_array($output['checked_brand'])) || (isset($output['checked_attr']) && is_array($output['checked_attr']))){?>
	 <div class="select-undo"><a href="<?php echo dropParam(array('a_id', 'b_id'));?>" class="delAll">全部撤销</a></div>
		<?php if(isset($output['checked_brand']) && is_array($output['checked_brand'])){?>
		<?php foreach ($output['checked_brand'] as $key=>$val){?>
		<div class="selected-box"><a class="selected"  href="<?php echo removeParam(array('b_id' => $key));?>"><?php echo $lang['goods_class_index_brand'];?>: <em><?php echo $val['brand_name']?></em><i></i></a></div>
		<?php }?>
		<?php }?>

		 <?php if(isset($output['checked_attr']) && is_array($output['checked_attr'])){?>
		 <?php foreach ($output['checked_attr'] as $val){?>
		 <div class="selected-box"><a class="selected"  href="<?php echo removeParam(array('a_id' => $val['attr_value_id']));?>"><?php echo $val['attr_name'].': <em>'.$val['attr_value_name'].'</em>'?><i></i></a></div>
		 <?php }?>
		 <?php }?>
	 <?php }?>	 
    <div class="clear"></div>
  </div>
<div class="nch-container wrapper">
 <!-- 分类下的推荐商品 -->
    <div id="gc_goods_recommend_div" class="wrapper"></div>
    <div class="nch-module">
      <div class="title">
        <h3>
          <?php if (!empty($output['show_keyword'])) {?>
          <em><?php echo $output['show_keyword'];?></em> -
          <?php }?>
          商品筛选<i>搜索到<?php echo $output['goods_num'];?>件相关商品</i></h3>
      </div>
	<div class="content">
        <div class="nch-module-filter">	  
		<?php if($output['flag'] != 0):?>
			<dl>
            <dt><span>商品所在地<?php echo $lang['nc_colon'] ;?></span> </dt>
            <dd class="list">
              <ul>
                <li><a href="<?php echo replaceParam(array('areaid_3' => 0));?>">全市</a> </li>
                <?php foreach ($output['city_list'] as $key => $value) { ?>
                  <li><a href="<?php echo replaceParam(array('areaid_3' => $value[0]));?> "> <?php echo $value[1];?></a>  </li>
                <?php }?>
              </ul>
            </dd>  
          </dl>
		  <?php endif;?>
    <?php $dl=1;  //dl标记?>
     <?php if(!empty($output['goods_class_array']) and isset($output['goods_class_array'])):?>
          <dl>
            <dt><span>包含分类<?php echo $lang['nc_colon'] ;?></span> </dt>
            <dd class="list">
              <ul>
                <?php foreach ($output['goods_class_array'] as $key => $value) { ?>
                  <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=index&cate_id=<?php echo $value['gc_id']  ;?>"><?php echo $value['gc_name']  ;?></a>   </li>
                <?php }?>
              </ul>
            </dd>  
          </dl>
          <?php endif;?>
    <?php $dl=1; $num_dl = 1;  //dl标记?>
    <?php if(1){?>
          <?php if (!isset($output['checked_brand']) || empty($output['checked_brand'])){?>
          <?php if(!empty($output['brand_array']) && is_array($output['brand_array'])){?>
          <dl>
            <dt><?php echo $lang['goods_class_index_brand'].$lang['nc_colon'];?></dt>
            <dd class="list">
              <ul class="nch-brand-tab" nctype="ul_initial" style="display:none;">
                <li data-initial="all"><a href="javascript:void(0);">所有品牌<i class="arrow"></i></a></li>
                <?php if (!empty($output['initial_array'])) {?>
                <?php foreach ($output['initial_array'] as $val) {?>
                <li data-initial="<?php echo $val;?>"><a href="javascript:void(0);"><?php echo $val;?><i class="arrow"></i></a></li>
                <?php }?>
                <?php }?>
              </ul>
              <div id="ncBrandlist">
                <ul class="nch-brand-con" nctype="ul_brand">
                  <?php $i = 0;foreach ($output['brand_array'] as $k=>$v){$i++;?>
                  <li data-initial="<?php echo $v['brand_initial']?>" <?php if ($i > 14) {?>style="display:none;"<?php }?>><a href="<?php $b_id = (($_GET['b_id'] != '' && intval($_GET['b_id']) != 0)?$_GET['b_id'].'_'.$k:$k); echo replaceParam(array('b_id' => $b_id));?>">
                    <?php if ($v['show_type'] == 0) {?>
                    <img src="<?php echo brandImage($v['brand_pic']);?>" alt="<?php echo $v['brand_name'];?>" /> <span>
                    <?php  echo $v['brand_name'];?>
                    </span>
                    <?php } else { echo $v['brand_name'];?>
                    <?php }?>
                    </a></li>
                  <?php }?>
                </ul>
              </div>
            </dd>
            <?php if (count($output['brand_array']) > 16){?>
            <dd class="all"><span nctype="brand_show"><i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?></span></dd>
            <?php }?>
          </dl>
          <?php $dl++;$num_dl++;}?>
          <?php }?>
          <?php if(!empty($output['attr_array']) && is_array($output['attr_array'])){?>
          <?php $j = 0;foreach ($output['attr_array'] as $key=>$val){$j++;?>
          <?php if(!isset($output['checked_attr'][$key]) && !empty($val['value']) && is_array($val['value'])){?>          
          <dl <?php if($num_dl > 3){?>class="hide_dl" style="display: none;"<?php }?>>
            <dt><?php echo $val['name'].$lang['nc_colon'];?></dt>
            <dd class="list">
              <ul>
                <?php $i = 0;foreach ($val['value'] as $k=>$v){$i++;?>
                <li <?php if ($i>10){?>style="display:none" nc_type="none"<?php }?>><a href="<?php $a_id = (($_GET['a_id'] != '' && $_GET['a_id'] != 0)?$_GET['a_id'].'_'.$k:$k); echo replaceParam(array('a_id' => $a_id));?>"><?php echo $v['attr_value_name'];?></a></li>
                <?php }?>
              </ul>
            </dd>
            <?php if (count($val['value']) > 10){?>
            <dd class="all"><span nc_type="show"><i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?></span></dd>
            <?php }?>
          </dl>
          <?php $num_dl++;}?>
          <?php $dl++;} ?>
          <?php }?>
	  <?php }?>
        </div>
	
      </div>
    
 </div>
 <?php if($num_dl > 4){?>
	<div id="more_select_nav"><a href="javascript:void(0);" class="down"><span>更多选项&nbsp;</span><i class="icon-angle-down"></i></i></a></div>
 <?php }?>
    <div class="shop_con_list" id="main-nav-holder">
      <nav class="sort-bar" id="main-nav">
        <div class="pagination"><?php echo $output['show_page1']; ?> </div>
        <div class="nch-all-category">
          <div class="all-category">
            <?php require template('layout/home_goods_class');?>
          </div>
        </div>
        <div class="nch-sortbar-array"> 排序方式：
          <ul>
            <li <?php if(!$_GET['key']){?>class="selected"<?php }?>><a href="<?php echo dropParam(array('order', 'key'));?>"  title="<?php echo $lang['goods_class_index_default_sort'];?>"><?php echo $lang['goods_class_index_default'];?></a></li>
            <li <?php if($_GET['key'] == '1'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')):replaceParam(array('key' => '1', 'order' => '2')); ?>" <?php if($_GET['key'] == '1'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1')?$lang['goods_class_index_sold_asc']:$lang['goods_class_index_sold_desc']; ?>"><?php echo $lang['goods_class_index_sold'];?><i></i></a></li>
            <li <?php if($_GET['key'] == '2'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')):replaceParam(array('key' => '2', 'order' => '2')); ?>" <?php if($_GET['key'] == '2'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php  echo ($_GET['order'] == '2' && $_GET['key'] == '2')?$lang['goods_class_index_click_asc']:$lang['goods_class_index_click_desc']; ?>"><?php echo $lang['goods_class_index_click']?><i></i></a></li>
            <li <?php if($_GET['key'] == '3'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? replaceParam(array('key' => '3', 'order' => '1')):replaceParam(array('key' => '3', 'order' => '2')); ?>" <?php if($_GET['key'] == '3'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3')?$lang['goods_class_index_price_asc']:$lang['goods_class_index_price_desc']; ?>"><?php echo $lang['goods_class_index_price'];?><i></i></a></li>
          </ul>
        </div>
        <div class="nch-sortbar-filter" nc_type="more-filter">
        <span class="arrow"></span>
          <ul>
            <li><a href="<?php if ($_GET['type'] == 1) { echo dropParam(array('type'));} else { echo replaceParam(array('type' => '1'));}?>" <?php if ($_GET['type'] == 1) {?>class="selected"<?php }?>><i></i>平台自营</a></li>
            <li><a href="<?php if ($_GET['gift'] == 1) { echo dropParam(array('gift'));} else { echo replaceParam(array('gift' => '1'));}?>" <?php if ($_GET['gift'] == 1) {?>class="selected"<?php }?>><i></i>赠品</a></li>
            <!-- 消费者保障服务 -->
            <?php if($output['contract_item']){?>
            <?php foreach($output['contract_item'] as $citem_k=>$citem_v){ ?>
            <li><a href="<?php if (in_array($citem_k,$output['search_ci_arr'])){ echo removeParam(array('ci' => $citem_k));} else { echo replaceParam(array("ci" => $output['search_ci_str'].$citem_k));}?>" <?php if (in_array($citem_k,$output['search_ci_arr'])) {?>class="selected"<?php }?>><i></i><?php echo $citem_v['cti_name']; ?></a></li>
            <?php }?>
            <?php }?>
          </ul>
        </div>

      </nav>
      <!-- 商品列表循环  -->
      <div>
        <?php require_once (BASE_TPL_PATH.'/home/goods.squares.php');?>
      </div>
      <div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
    </div>
    
    <!-- S 推荐展位 -->
    <div nctype="booth_goods" class="nch-module" style="display:none;"> </div>
    <!-- 猜你喜欢 -->
    <div id="guesslike_div"></div>
   <!-- <?php if(!empty($output['viewed_goods'])){?>-->
    <!-- 我的足迹 -->
    <div class="nch-module nch-module-style03">
      <div class="title">
		  <h3><?php echo $lang['goods_class_viewed_goods']; ?><span><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_goodsbrowse&op=list">全部浏览历史</a></span></h3>
      </div>
      <div class="content">
        <div class="nch-sidebar-viewed">
          <ul>
            <?php if(!empty($output['viewed_goods']) && is_array($output['viewed_goods'])){?>
            <?php foreach ($output['viewed_goods'] as $k=>$v){?>
            <li class="nch-sidebar-bowers">
              <div class="goods-pic"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id'])); ?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo thumb($v, 60); ?>" title="<?php echo $v['goods_name']; ?>" alt="<?php echo $v['goods_name']; ?>" ></a></div>
              <dl>
                <dd><?php echo $lang['currency'];?><?php echo ncPriceFormat($v['goods_promotion_price']); ?></dd>
              </dl>
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </div>
        </div>
    </div>
    <?php } ?>
        <!-- E 推荐展位 -->
    <div class="nch-module"><?php echo loadadv(37,'html');?></div>
  <div class="clear"></div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/search_category_menu.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fly/jquery.fly.min.js" charset="utf-8"></script> 
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
var defaultSmallGoodsImage = '<?php echo defaultGoodsImage(240);?>';
var defaultTinyGoodsImage = '<?php echo defaultGoodsImage(60);?>';

$(function(){
    $('#files').tree({
        expanded: 'li:lt(2)'
    });
	//品牌索引过长滚条
	$('#ncBrandlist').perfectScrollbar({suppressScrollX:true});
    //浮动导航  waypoints.js
    $('#main-nav-holder').waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });
	// 单行显示更多
	$('span[nc_type="show"]').click(function(){
		s = $(this).parents('dd').prev().find('li[nc_type="none"]');
		if(s.css('display') == 'none'){
			s.show();
			$(this).html('<i class="icon-angle-up"></i><?php echo $lang['goods_class_index_retract'];?>');
		}else{
			s.hide();
			$(this).html('<i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?>');
		}
	});

	<?php if(isset($_GET['area_id']) && intval($_GET['area_id']) > 0){?>
  // 选择地区后的地区显示
  $('[nc_type="area_name"]').html('<?php echo $output['province_array'][intval($_GET['area_id'])]; ?>');
	<?php }?>

	<?php if(isset($_GET['cate_id']) && intval($_GET['cate_id']) > 0){?>
	// 推荐商品异步显示
    $('div[nctype="booth_goods"]').load('<?php echo urlShop('search', 'get_booth_goods', array('cate_id' => $_GET['cate_id']))?>', function(){
        $(this).show();
    });
	<?php }?>

	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });

	//商品分类推荐
	$('#gc_goods_recommend_div').load('<?php echo urlShop('search', 'get_gc_goods_recommend', array('cate_id'=>$output['default_classid'])); ?>');

  //获取更多
  $('#more_select_nav a').click(function(){
    var attr = $(this).attr('class');
    if(attr == 'down'){
      $(this).attr('class','up');
      $(this).find('i').removeClass('icon-angle-down').addClass('icon-angle-up');
      $(this).find('span').html('收起选项&nbsp;');
      $('.nch-module-filter .hide_dl').show();
    }else{
      $(this).attr('class','down');
      $(this).find('i').removeClass('icon-angle-up').addClass('icon-angle-down');
      $(this).find('span').html('更多选项&nbsp;');
      $('.nch-module-filter .hide_dl').hide();
    }
  });
});
</script> 
