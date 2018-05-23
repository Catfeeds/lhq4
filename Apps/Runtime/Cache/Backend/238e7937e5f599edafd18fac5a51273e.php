<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo ($page_title); ?> - <?php echo (ADMIN_TITLE); ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="/lhq/Public/lib/bootstrap/css/bootstrap.css">

    <?php if($_SESSION['osa_user_info']['template']!= ''): ?><link rel="stylesheet" href="/lhq/Public/stylesheets_<?php echo ($_SESSION['osa_user_info']['template']); ?>/theme.css">
    <?php else: ?>
    <link rel="stylesheet" href="/lhq/Public/stylesheets_default/theme.css"><?php endif; ?>
<!--     <link rel="stylesheet" href="/lhq/Public/stylesheets_default/theme.css"> -->
    <link rel="stylesheet" href="/lhq/Public/lib/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/lhq/Public/css/other.css">
	<link rel="stylesheet" href="/lhq/Public/css/table-fenye.css">
	<link rel="stylesheet" href="/lhq/Public/css/jquery-ui.css" />
    <link rel="stylesheet" href="/lhq/Public/css/lanrenzhijia.css">
	<link rel="stylesheet" href="/lhq/Public/css/jquery-ui-timepicker-addon.css" />
    <link rel="stylesheet" href="/lhq/Public/css/xs.css" />


    <script src="/lhq/Public/lib/jquery-1.8.1.min.js" ></script>
	<script src="/lhq/Public/lib/jquery.cookie.js" ></script>
	<script src="/lhq/Public/lib/bootstrap/js/bootbox.min.js"></script>
	<script src="/lhq/Public/lib/bootstrap/js/bootstrap-modal.js"></script>
	<script src="/lhq/Public/js/other.js"></script>
	<script src="/lhq/Public/js/jquery-ui.js"></script>
	<script src="/lhq/Public/js/jquery-ui-timepicker-addon.js"></script>
   <!--   <script src="/lhq/Public/js/js.js"></script>-->
      <script src="/lhq/Public/select/js/jquery.jmpopups-0.5.1.js"></script>
    <script src="/lhq/Public/js/jquery.jmpopups-0.5.1.js"></script>

      <script type='text/javascript' src="/lhq/Public/select/js/jquery.selectlayer.js"></script>

      <link href="/lhq/Public/select/css/style.css" rel="stylesheet" type="text/css" />
      <!-- Demo page code -->

    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .brand { font-family: georgia, serif; }
        .brand .first {
            color: #ccc;
            font-style: italic;
        }
        .brand .second {
            color: #fff;
            font-weight: bold;
        }
    </style>

  </head>


<?php if($sidebarStatus == yes): ?><body id="body" class="body">
  <?php else: ?>
  <body id="body" class="body-fullscreen"><?php endif; ?>

<div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                    
					<?php if($sidebarStatus == yes): ?><li class="doSidebarClz"><a href="#" class="hidden-phone visible-tablet visible-desktop" role="button">
						关闭侧栏<i class="icon-step-backward"></i>
						</a></li>
					<?php else: ?>
						<li class="doSidebarClz"><a href="#" class="hidden-phone visible-tablet visible-desktop" role="button">
						打开侧栏<i class="icon-step-forward"></i>
						</a></li><?php endif; ?>
					 
					<?php if($user_info.setting): ?><li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-cog"></i>设置<i class="icon-caret-down"></i>
						</a>
                        <ul class="dropdown-menu">
                            <li><a href="/lhq/backend/panel/setting">系统设置</a></li>
                        </ul>
                    </li><?php endif; ?>
					
					<li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
							
                            选择模板
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
							<?php if(is_array($osa_templates)): foreach($osa_templates as $key=>$name): ?><li><a href="/lhq/backend/panel/set?t=<?php echo ($key); ?>"><?php echo ($name); ?></a></li><?php endforeach; endif; ?>
                        </ul>
                    </li>
					
					<li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> <?php echo ($user_info["user_name"]); ?>
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="/lhq/backend/panel/profile">我的账号</a></li>
                            <li><a tabindex="-1" href="/lhq/backend/panel/logout">登出</a></li>
                        </ul>
                    </li>
                    
                </ul>
                <a class="brand" href="/lhq/backend"><span class="first"></span> <span class="second"><?php echo (COMPANY_NAME); ?></span></a>
        </div>
</div>
<?php if($sidebarStatus == yes): ?><div id="sidebar-nav" class="sidebar-nav">
<?php else: ?>
<div id="sidebar-nav" class="sidebar-nav-hide"><?php endif; ?>
		<?php if(is_array($sidebar)): foreach($sidebar as $key=>$module): if(count($module.menu_list) > 0): ?><a href="#sidebar_menu_<?php echo ($module["module_id"]); ?>" class="nav-header collapsed" data-toggle="collapse"><i class="<?php echo ($module["module_icon"]); ?>"></i><?php echo ($module["module_name"]); ?> <i class="icon-chevron-up"></i></a>
				<?php if($module["module_id"] == $current_module_id): ?><ul id="sidebar_menu_<?php echo ($module["module_id"]); ?>" class="nav nav-list collapse in">
				<?php else: ?>
					<ul id="sidebar_menu_<?php echo ($module["module_id"]); ?>" class="nav nav-list collapse"><?php endif; ?>
				
				<?php if(is_array($module["menu_list"])): foreach($module["menu_list"] as $key=>$menu_list): if(strtolower(substr($menu_list.menu_url,0,7)) == 'http://'): ?><li><a target=_blank href="<?php echo ($menu_list["menu_url"]); ?>"><?php echo ($menu_list["menu_name"]); ?></a></li>
				<?php else: ?>
					<li><a href="/lhq<?php echo ($menu_list["menu_url"]); ?>"><?php echo ($menu_list["menu_name"]); ?></a></li><?php endif; endforeach; endif; ?>
			</ul><?php endif; endforeach; endif; ?>

        <a target="_blank" href="#" class="nav-header" ><i class="icon-question-sign"></i>帮助</a>
</div>
	 <!--- 以上为左侧菜单栏 sidebar -->
<?php if($sidebarStatus == yes): ?><div id="content" class="content">
<?php else: ?>
<div id="content" class="content-fullscreen"><?php endif; ?>        
        <div class="header">
            <div class="stats">
			<p class="stat"><!--span class="number"></span--></p>
			</div>

            <h1 class="page-title"><?php echo ($content_header["menu_name"]); ?></h1>
        </div>
        
		<ul class="breadcrumb">
            <li><a href="<?php echo (ADMIN_URL); echo ($content_header["module_url"]); ?>"> <?php echo ($content_header["module_name"]); ?> </a> <span class="divider">/</span></li>
           
			<?php if(empty($content_header["father_menu"] == false)): ?><li><a href="<?php echo (ADMIN_URL); echo ($content_header["father_menu_url"]); ?>"> <?php echo ($content_header["father_menu_name"]); ?> </a> <span class="divider">/</span></li><?php endif; ?>
			
			<li class="active"><?php echo ($content_header["menu_name"]); ?></li>
	        <?php if(empty($content_header["shortcut_allowed"] == false)): if(in_array($content_header[menu_id],$user_info[shortcuts_arr]) == true): ?><a title= "移除快捷菜单" href="#"><li class="active"><i class="icon-minus" method="del" menu_id="<?php echo ($content_header["menu_id"]); ?>" url="<?php echo U('Backend/ApiAjax/shortcut');?>"></i></li></a>
				<?php else: ?>
					<a title= "加入快捷菜单" href="#"><li class="active"><i class="icon-plus" method="add" menu_id="<?php echo ($content_header["menu_id"]); ?>" url="<?php echo U('Backend/ApiAjax/shortcut');?>"></i></li></a>
				<!--<?php echo ($smarty["const"]["ADMIN_URL"]); ?>/ajax/shortcut.php?menu_id=<?php echo ($content_header["menu_id"]); ?> --><?php endif; endif; ?>
			
        </ul>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="bb-alert alert alert-info" style="display: none;">
			<span>操作成功</span>
		</div>
<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php echo ($osadmin_action_alert); ?>
<?php echo ($osadmin_quick_note); ?>

<script>
	jQuery(document).ready(function($) {
		$('.theme-login').click(function(){
//            $('.theme-popover-mask').fadeIn(100);
			$('.theme-popover').slideDown(200);
		})
		$('.theme-poptit .close').click(function(){
			$('.theme-popover-mask').fadeOut(100);
			$('.theme-popover').slideUp(200);
		})

		$('.theme-login1').click(function(){
//            $('.theme-popover-mask').fadeIn(100);
			$('.theme-popover1').slideDown(200);
		})
		$('.theme-poptit1 .close1').click(function(){
			$('.theme-popover-mask1').fadeOut(100);
			$('.theme-popover1').slideUp(200);
		})

	})

	function fun(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-popover').slideUp(200);
		obj = document.getElementsByName("repeat_urls[]");

		check_val = [];
		for(k in obj){
			if(obj[k].checked)
				check_val.push(obj[k].value);
		}
		var bale= check_val.join(';');
		$('#repeat_url').val(bale);
	}

	function fun1(){
		$('.theme-popover-mask1').fadeOut(100);
		$('.theme-popover1').slideUp(200);
		obj = document.getElementsByName("config_ifs[]");

		check_val = [];
		for(k in obj){
			if(obj[k].checked)
				check_val.push(obj[k].value);
		}
		var bale= check_val.join(';');
		$('#config_if').val(bale);
	}

</script>


    
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">修改广告商配置信息资料</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">

           <form id="tab" method="post" action="">
				<label>配置信息名称</label>
				<input type="text" name="config_name" value="<?php echo ($config["config_name"]); ?>" class="input-xlarge" required="true" autofocus="true" >
				<label>应用名称</label>
				<select name="app_id"  required="true">
                    <?php if(is_array($apps)): foreach($apps as $app_id=>$app): if($app_id == 0): ?><option value="">请选择广告名称</option>
							<?php else: ?>
							<option value="<?php echo ($app_id); ?>"
							<?php if($config['app_id'] == $app_id): ?>selected="selected"<?php endif; ?>
							><?php echo ($app); ?></option><?php endif; endforeach; endif; ?>
                </select>
				<label>配置请求方式</label>
				<select name="request" id="" class="input-xlarge" required="true" autofocus="true">
					<?php if($config["request"] == 'get' ): ?><option value="get" selected="selected"> get </option>
					    <option value="post"> post </option>
						<option value="newpost">newpost</option>
					<?php elseif($config["request"] == 'post'): ?>
					    <option value="get" > get </option>
					    <option value="post" selected="selected"> post </option>
						<option value="newpost">newpost</option>
					<?php elseif($config["request"] == 'newpost'): ?>
						<option value="get" > get </option>
						<option value="post" > post </option>
						<option value="newpost" selected="selected">newpost</option><?php endif; ?>
				</select>
				<label>配置url</label>
				<textarea id='repeat_url' name="repeat_url" rows="3" class="input-xlarge"><?php echo (htmlspecialchars($config["repeat_url"])); ?></textarea>
				<a class="btnn btnp  theme-login" href="javascript:;">选择url</a>

			   <div class="theme-popover" style="overflow: auto;">
				   <div class="theme-poptit">
					   <a href="javascript:;" title="关闭" class="close">×</a>
					   <h5>请选择url</h5>
				   </div>
				   <div action="" method="post">
				   	   <?php if(is_array($configArrs)): foreach($configArrs as $key=>$configArr): if(!empty($configArr.repeat_url)): ?><label><input name="repeat_urls[]" type="checkbox" value="<?php echo ($configArr["repeat_url"]); ?>">
						   <?php echo ($configArr["repeat_url"]); ?> </label><?php endif; endforeach; endif; ?>

				   </div>
				   <div class="btn-toolbar ">
					   <!--<a type="submit" class="btn btn-primary" value="<?php echo ($_POST["label"]); ?>">确定</a>-->
					   <button type="button" class="btnprimary" onclick="fun()"><strong>确定</strong></button>

				   </div>
			   </div>

				<label>配置返回结果</label>
				<textarea id='config_if' name="config_if" rows="5" class="input-xlarge"><?php echo ($config["config_if"]); ?></textarea>

				<a class="btnn btnp  theme-login1" href="javascript:;">配置结果</a>

			   <div class="theme-popover1" style="overflow: auto;">
				   <div class="theme-poptit1">
					   <a href="javascript:;" title="关闭" class="close1">×</a>
					   <h5>请选择返回结果</h5>
				   </div>
				   <div action="" method="post">
					   <?php if(is_array($configArrs1)): foreach($configArrs1 as $key=>$configArr1): if(!empty($configArr1.config_if)): ?><label><input name="config_ifs[]" type="checkbox"  value='<?php echo ($configArr1["config_if"]); ?>'>
						   <?php echo ($configArr1["config_if"]); ?> </label><?php endif; endforeach; endif; ?>
				   </div>
				   <div class="btn-toolbar ">
					   <!--<a type="submit" class="btn btn-primary" value="<?php echo ($_POST["label"]); ?>">确定</a>-->
					   <button type="button" class="btnprimary" onclick="fun1()"><strong>确定</strong></button>

				   </div>
			   </div>
<!--
			   <label>配置排重类型</label>
			   <select name="config_type" id="" class="input-xlarge" required="true" autofocus="true">
				   <?php if($config["config_type"] == '3' ): ?><option value="3" selected="selected"> 自身兼广告主排重 </option>
					   <option value="2"> 广告主排重 </option>
					   <option value="1"> 自身排重 </option>
					   <option value="0"> 不排重（慎重选择） </option>
					<?php elseif($config["config_type"] == '2' ): ?>
					   <option value="3"> 自身兼广告主排重 </option>
					   <option value="2" selected="selected"> 广告主排重 </option>
					   <option value="1"> 自身排重 </option>
					   <option value="0"> 不排重（慎重选择） </option>
					   <?php elseif($config["config_type"] == '1' ): ?>
					   <option value="3"> 自身兼广告主排重 </option>
					   <option value="2"> 广告主排重 </option>
					   <option value="1" selected="selected"> 自身排重 </option>
					   <option value="0"> 不排重（慎重选择） </option>
					   <?php else: ?>
					   <option value="3"> 自身兼广告主排重 </option>
					   <option value="2"> 广告主排重 </option>
					   <option value="1"> 自身排重 </option>
					   <option value="0" selected="selected"> 不排重（慎重选择） </option><?php endif; ?>
			   </select>
-->
			   <input type="hidden" name="httpref" value="<?php echo ($_SERVER['HTTP_REFERER']); ?>">
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
				</div>
			</form>
        </div>
    </div>
</div>	
<!---操作的确认层，相当于javascript:confirm函数-->
<?php echo ($osadmin_action_confirm); ?>

<!-- END 以下内容不需更改，请保证该TPL页内的标签匹配即可 -->
                    
	
					<footer>
                        <hr>
                        <p class="pull-right">A <a href="#" target="_blank">Basic Backend Management System for China Only.</a> by <a href="#" target="_blank">jerry</a>.</p>

                        <p>&copy; 2016 <a href="#" target="_blank">jerry</a></p>
                    </footer>
				</div>
			</div>
		</div>
    <script src="/lhq/Public/lib/bootstrap/js/bootstrap.js"></script>
	
<!--- + -快捷方式的提示 -->
	
<script type="text/javascript">	
	
alertDismiss("alert-success",3);
alertDismiss("alert-info",10);
	
listenShortCut("icon-plus");
listenShortCut("icon-minus");
doSidebar();
</script>
  </body>
</html>