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

<!--- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->

<?php echo ($osadmin_action_alert); ?>

<div class="btn-toolbar" style="margin-bottom:2px;">
    <a href="<?php echo U('missionAdd');?>" class="btn btn-primary"><i class="icon-plus"></i> 添加计划任务</a>
</div>

<form class="form_search"  action="/lhq/Backend/Mission/mission" method="GET" style="margin-bottom:0px">
    <div style="float:left;margin-right:5px">
        <label> 选择起始时间 </label>
        <input type="text" id="start_date" name="start_date" value="<?php echo ($_GET["start_date"]); ?>" placeholder="起始时间" >
    </div>
    <div style="float:left;margin-right:5px">
        <label>选择结束时间</label>
        <input type="text" id="end_date" name="end_date" value="<?php echo ($_GET["end_date"]); ?>" placeholder="结束时间" >
    </div>
    <div style="float:left;margin-right:5px">
        <label>所属广告:</label>
        <input type="text" name="app_name" value="<?php echo ($_GET["app_name"]); ?>" placeholder="输入所属广告名称" >

    </div>
    <div style="float:left;margin-right:5px">
        <label>任务状态:</label>
        <select name="status">
            <option value="0"
            <?php if($_GET['status'] == ''): ?>selected="selected"<?php endif; ?>
            >不限</option>
            <option value="1"
            <?php if($_GET['status'] == 1): ?>selected="selected"<?php endif; ?>
            >未审核</option>
            <option value="2"
            <?php if($_GET['status'] == 2): ?>selected="selected"<?php endif; ?>
            >已上线</option>
            <option value="3"
            <?php if($_GET['status'] == 3): ?>selected="selected"<?php endif; ?>
            >已下线</option>
        </select>

    </div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
        <input type="hidden" name="search" value="1" >
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>

    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse">计划列表</a>
        <div id="page-stats" class="block-body collapse in">

            <form id="tab" method="post" action="">
            <table class="table table-striped">
              <thead>
                <tr>
					<th style="width:30px">ID</th>
                    <th style="width:90px">任务名称</th>
					<th style="width:100px">所属广告</th>
					<th style="width:100px">关键词</th>                                   
                    <th style="width: 80px">总量</th>
                    <th style="width: 80px">总剩余量</th>
					<th style="width:80px">用户量</th>
                    <th style="width:80px">用户剩余量</th>
                    <th style="width:80px">渠道量</th>
                    <th style="width:80px">渠道剩余量</th>
                    <th style="width:80px">出货价</th>
					<th style="width:180px">广告类型</th>
					<th style="width:180px">销售开始时间</th>
					<th style="width:180px">销售结束时间</th>
                    <th style="width:80px">状态</th>
					<th style="width:100px">操作</th>
                </tr>
              </thead>
              <tbody>							  
                <?php if(is_array($logs)): foreach($logs as $key=>$log): ?><tr>
                    <td><?php echo ($log["mission_id"]); ?></td>
					<td><?php echo ($log["mission_name"]); ?></td>
                    <td><?php echo ($apps[$log['app_id']]); ?></td>
                        <?php if($log["kwd_qx"] == 1): ?><td><?php echo ($log["kkwd"]); ?></td>
                        <?php elseif($log["kkwd"] == null ): ?>
                            <td><?php echo ($log["mkwd"]); ?></td>
                            <?php elseif($log["kwd_qx"] == 0): ?>
                            <td><?php echo ($log["mkwd"]); ?></td>
                            <?php else: endif; ?>
                    <td><?php echo ($log["smount"]); ?></td>
                    <td><?php echo ($log["smount_re"]); ?></td>
                    <td><?php echo ($log["amount"]); ?></td>
                    <td><?php echo ($log["re_num"]); ?></td>
                    <td><?php echo ($log["cmount"]); ?></td>
                    <td><?php echo ($log["cmount_re"]); ?></td>
                    <td><?php echo ($log["price"]); ?></td>
                    <td><?php echo ($adtypes[$log['adtype_id']]); ?></td>
                    <td><?php echo ($log["start_time"]); ?></td>
					<td><?php echo ($log["end_time"]); ?></td>
                    <td><?php if($log["status"] == 1): ?>未审核
                        <?php elseif($log["status"] == 2): ?>
                        在线
                        <?php else: ?>
                        下线<?php endif; ?>
                    </td>
                    <td>
                    <a class="zt" mid="<?php echo ($log["mission_id"]); ?>">
                        <?php if($log["status"] == 1): ?><img xs='1' value="未审核"  class="wo" src="/lhq/Public/images/shen.png"/>
                        <?php elseif($log["status"] == 2): ?>
                            <img xs='2' value="上线" class="wo" src="/lhq/Public/images/xia.png"/>
                        <?php else: ?>
                            <img xs='3' value="下线" class="wo" src="/lhq/Public/images/shang.png"/><?php endif; ?>
                    </a> &nbsp;
					<a href="<?php echo U('missionModify',array('mission_id'=>$log['mission_id']));?>" title= "修改" ><i class="icon-pencil"></i></a>
                            &nbsp;
                    <a data-toggle="modal" mission_id="<?php echo ($log["mission_id"]); ?>" method="del" title= "删除" ><i class="icon-remove"></i></a>
                            &nbsp;
                    </td>
					</tr><?php endforeach; endif; ?>
              </tbody>
            </table>
        </form>
<!--- START 分页模板 -->
        <div style="margin-bottom: 20px">
            <table id="page-table-fenye" cellspacing="0" >
                <tr>
                    <td align="center" nowrap="true">
                        <?php echo ($page); ?>
                    </td>
                </tr>
            </table>
        </div>
        每页25项
<!--- END 分页-->			   
        </div>
    </div>
<script>
    $(function(){
        $(".icon-remove").click(function() {
            var statu = confirm("你确定要删除吗");
            if (statu) {
                var mission_id = $(this).parent().attr('mission_id');
                var method = $(this).parent().attr('method');
                var url = "<?php echo U('Backend/Mission/mission');?>";
                $.post(url, {mission_id: mission_id, method: method},function(e){
                        window.location.reload();
                });
            } else {
                return false;
            }

        })
    });
</script>
<script>

    $(function(){
        $(".zt").find('img').click(function() {
            var statu = confirm("你确定要修改吗");
            if(!statu){
                return false;
            }else{
                var url = "<?php echo U('Mobile/Apiajax/statusAjax');?>";
                var val = $(this).attr('xs');
                var mid = $(this).parent().attr('mid');

                $.post(url, {val: val, mid: mid},function(e){
                    if ( e == 3) {
                        alert('总量为零不能上线');
                    } else if (e == 4) {
                        alert('剩余量为零不能上线');
                    }
                    else {
                        window.location.reload();
                    }
                });

            }
        })
    });
</script>
<script>

    function selectAll() {
        var arryObj = document.getElementsByName("selectedids")
        for ( var i = 0; i < arryObj.length; i++) {

            if (typeof arryObj[i].type != "undefined"
                    && arryObj[i].type == 'checkbox')
                arryObj[i].checked = true;
        }
    }

    function unSelectAll() {
        var arryObj = document.getElementsByName("selectedids");
        for ( var i = 0; i < arryObj.length; i++) {
            if (typeof arryObj[i].type != "undefined"
                    && arryObj[i].type == 'checkbox')
                ;
            arryObj[i].checked = false;
        }
    }

    $(function() {
        var date=$( "#start_date" );
        date.datetimepicker({
            showSecond:true,
            timeFormat: "HH:mm:ss"
        });

    });
    $(function() {
        var date=$( "#end_date" );
        date.datetimepicker({
            showSecond:true,
            timeFormat: "HH:mm:ss"
        });
    });
</script>
<!---操作的确认层，相当于javascript:confirm函数-->
<?php echo ($osadmin_action_confirm); ?>
<!--- END 以下内容不需更改，请保证该TPL页内的标签匹配即可 -->
                    
	
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