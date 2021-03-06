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
<!-- TPLSTART 以上内容不需更改，保证该TPL页内的标签匹配即可 -->

<div class="btn-toolbar" style="margin-bottom:2px;">
    <a href="<?php echo U(appAdd);?>" class="btn btn-primary"><i class="icon-plus"></i> 添加广告应用</a>
</div>
<form class="form_search"  action="/lhq/Backend/App/app" method="GET" style="margin-bottom:0px">
    <div style="float:left;margin-right:5px">
        <label>查询应用</label>
        <input type="text" name="app_name" value="<?php echo ($_GET["app_name"]); ?>" placeholder="输入应用名称" >
        <input type="hidden" name="search" value="1" >
    </div>
    <?php if($user_group == 1|| $user_group == 3 || $user_group == 5): ?><div style="float:left;margin-right:5px">
            <label>选择广告商</label>
            <select name="provider_id">
                <?php if(is_array($providers)): foreach($providers as $provider_id=>$provider): ?><option value="<?php echo ($provider_id); ?>"
                    <?php if($_GET['provider_id'] == $provider_id): ?>selected="selected"<?php endif; ?>
                ><?php echo ($provider); ?></option><?php endforeach; endif; ?>
            </select>
        </div><?php endif; ?>
  <!--  <div style="float:left;margin-right:5px">
        <label>全部配置</label>
        <select name="pz">
            <option value="1">全部配置</option>
        </select>
    </div>
    -->
    <div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
        <button type="submit" class="btn btn-primary">检索</button>
    </div>
    <div style="clear:both;"></div>
</form>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">广告应用管理</a>
	<div id="page-stats" class="block-body collapse in">
	<table class="table table-striped">
			<thead>
			<tr>
				<th>应用id</th>
				<th>应用名称</th>
                <?php if($user_group == 1|| $user_group == 3 || $user_group == 5): ?><th>广告商</th><?php endif; ?>
                <?php if($user_group == 1|| $user_group == 4|| $user_group == 5): ?><th>渠道</th><?php endif; ?>
				<th>广告主广告id</th>
				<th>下载地址</th>
                <th>Url Scheme</th>
				<th>备注</th>
                <?php if($user_group == 1|| $user_group == 5): ?><th>回调比例</th><?php endif; ?>
                <th>广告类型</th>
                <th>是否排重</th>
                <th>logo</th>
                <?php if($user_group == 1|| $user_group == 5): ?><th>操作</th><?php endif; ?>
				
			</tr>
			</thead>
			<tbody>							  
			<?php if(is_array($apps)): foreach($apps as $key=>$app): ?><tr>
				 
				<td><?php echo ($app["app_id"]); ?></td>
				
				<td><?php echo ($app["app_name"]); ?></td>

                <?php if($user_group == 1|| $user_group == 3|| $user_group == 5): ?><td><?php echo ($providers[$app['provider_id']]); ?></td><?php endif; ?>

                <?php if($user_group == 1|| $user_group == 4|| $user_group == 5): ?><td><?php echo ($app["chan_name"]); ?></td><?php endif; ?>

				<td><?php echo ($app["adsid"]); ?></td>
				
				<td><?php echo ($app["appstore_url"]); ?></td>
                <td><?php echo ($app["url_scheme"]); ?></td>
				<td><?php echo ($app["remark"]); ?></td>
                <?php if($user_group == 1|| $user_group == 5): ?><td><?php echo ($app["cutoff"]); ?></td><?php endif; ?>
                <td><?php echo ($adtypes[$app['adtype_id']]); ?></td>
                <td><?php echo ($app["is_repeat"]); ?>
                   <!-- <?php if($app["is_repeat"] == '3'): ?>自身兼广告主排重
                        <?php elseif($app["is_repeat"] == '2'): ?>
                        广告主排重
                        <?php elseif($app["is_repeat"] == '4'): ?>
                        自身排重
                        <?php elseif($app["is_repeat"] == '1'): ?>
                        不排重<?php endif; ?>
                    -->
                </td>
                <td><img style="height: 30px;width: 40px;" src="/lhq/Public<?php echo ($app["img"]); ?>"></td>
				<td>
                    <?php if($user_group == 1|| $user_group == 5): ?><a href="<?php echo U('appModify',array('app_id'=>$app['app_id']));?>" title= "修改" ><i class="icon-pencil"></i></a>
					
					&nbsp;
					<a data-toggle="modal" href="<?php echo U('app',array('app_id'=>$app['app_id'],'method'=>del));?>" title= "删除" ><i class="icon-remove"></i></a><?php endif; ?>
				</td>
				
				</tr><?php endforeach; endif; ?>
		  </tbody>
		</table>
		<!--- START 分页模板 -->

                    <?php echo ($page_html); ?>

			   <!--- END -->	
			    每页25项							
	</div>
</div>
<!-- TPLEND 以下内容不需更改，请保证该TPL页内的标签匹配即可 -->
                    
	
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