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
<script  type="text/javascript" src="/lhq/Public/lib/jquery-1.8.1.min.js"></script>
<link rel="stylesheet" href="/lhq/Public/css/poi.css" />
<link rel="stylesheet" href="/lhq/Public/lib/kindeditor/themes/default/default.css" />
<script src="/lhq/Public/lib/kindeditor/kindeditor.js"></script>
<script src="/lhq/Public/lib/kindeditor/plugins/image/image.js"></script>
<script src="/lhq/Public/lib/kindeditor/lang/zh_CN.js"></script>
<script src="/lhq/Public/js/k.js" type="text/javascript"></script>
<script>var ROOT="/lhq";</script>
<?php echo ($osadmin_action_alert); ?>

<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">修改应用资料</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">

           <form id="tab" method="post" action=""  name="upform" enctype="multipart/form-data" onsubmit="return check()">
           		<label>应用id</label>
				<input type="text" name="app_id" value="<?php echo ($app["app_id"]); ?>" disabled="disabled" class="input-xlarge" required="true" autofocus="true" >
				<label>应用名称</label>
				<input type="text" name="app_name" value="<?php echo ($app["app_name"]); ?>" class="input-xlarge" required="true" autofocus="true" >
               <label>Logo</label>
               <div >
                   <img   src="/lhq/Public<?php echo ($app["img"]); ?>" style="height:60px;width:auto;" class="poi url-image icon-url id__9" />
                   <input   type="hidden" name="img" value="/lhq/Public<?php echo ($app["img"]); ?>" class="icon-url id__9" required="required"/>
                   <div class="xz k-browse-image poi" data-id="9">选择</div>
                   <div class="sc k-upload-image poi" data-id="9">上传</div>
               </div>
               <label style="clear: both">广告商名称</label>
                <select name="provider_id">
                    <?php if(is_array($providers)): foreach($providers as $provider_id=>$provider): ?><option value="<?php echo ($provider_id); ?>"
                        <?php if($app['provider_id'] == $provider_id): ?>selected="selected"<?php endif; ?>
                    ><?php echo ($provider); ?></option><?php endforeach; endif; ?>
                </select>
               <label>广告主广告id</label>
               <input type="text" name="adsid" value="<?php echo ($app["adsid"]); ?>" class="input-xlarge" required="true" autofocus="true" >
               <div >
                   <label style="clear: both">应用id</label>
                   <input type="text" id="bundle" name="appid" value="<?php echo ($app["adsid"]); ?>" class="input-xlarge" required="true" autofocus="true" >
                   <input type="button" value="获取" onclick="getbundle(this)">
                   <input type="text" id="bundleid" name="bundleid" value="<?php echo ($app["bundleid"]); ?>">
               </div>
               <label>广告分类</label>
               <select name="adtype_id" id="DropDownAdtypeId">
                   <?php if(is_array($adtypes)): foreach($adtypes as $adtype_id=>$adtype): ?><option value="<?php echo ($adtype_id); ?>"
                       <?php if($app['adtype_id'] == $adtype_id): ?>selected="selected"<?php endif; ?>
                       ><?php echo ($adtype); ?></option><?php endforeach; endif; ?>
               </select>
				<label>渠道名称</label>
               <input name="chan_id"  id="district_cn" type="text" value="<?php echo ($app["chan_name"]); ?>"  readonly="true" class="sltinput" />
               <input name="chan_id" id="citycategory" type="hidden" value="<?php echo ($app["chan_id"]); ?>" />

               <div style="display:none" id="sel_district">
                   <!--<div class="OpenFloatBoxBg"></div>-->
                   <div class="OpenFloatBox">
                       <div class="title">
                           <h4>请选择渠道名称</h4>
                           <div class="DialogClose" title="关闭"></div>
                       </div>
                       <div class="content" style="margin-left:0px;min-height: 450px;">
                           <div class="txt">
                             <?php if(is_array($channels)): foreach($channels as $key=>$channel): ?><div class="item" id="593">
                                   <?php if(in_array($channel['channel_id'],$chanids)): ?><label title="<<?php echo ($channel["channel_id"]); ?>>" class="titem"><input name="channel_name" type="checkbox" value="<?php echo ($channel["channel_id"]); ?>" title="<?php echo ($channel["channel_name"]); ?>" class="b" checked="checked"/><?php echo ($channel["channel_name"]); ?></label>
                                       <?php else: ?>
                                       <label title="<<?php echo ($channel["channel_id"]); ?>>" class="titem"><input name="channel_name" type="checkbox" value="<?php echo ($channel["channel_id"]); ?>" title="<?php echo ($channel["channel_name"]); ?>" class="b"/><?php echo ($channel["channel_name"]); ?></label><?php endif; ?>
                        <!--           <label title="<?php echo ($channel["channel_id"]); ?>" class="titem"><input name="channel_name" type="checkbox" value="<?php echo ($channel["channel_id"]); ?>" title="<?php echo ($channel["channel_name"]); ?>" class="b"/><?php echo ($channel["channel_name"]); ?></label>
                       -->
                               <div class="sitem"></div>
                               </div><?php endforeach; endif; ?>

                               <div class="clear"></div>
                           </div>
                           <div class="txt">
                               <div class="selecteditem"></div>
                           </div>
                           <div class="txt">
                               <div align="center"><input type="button"  class="but80 Set" value="确定" style="width: 81px"/></div>
                           </div>
                       </div>
                   </div>
               </div>
               <br/><br/>
               <label>配置比例</label>
               <table width="50%">
                   <tr>
                       <td>渠道名称</td>
                       <td>回调比例(请直接填写数字，如50代表50%等)</td>
                   </tr>
                   <tbody id="bili">
                   <?php if(is_array($chanName_cutoff)): foreach($chanName_cutoff as $chanName=>$cutoff): ?><tr>
                       <td><?php echo ($chanName); ?> </td>
                       <td><input type="text" name="cutoff[]" value="<?php echo ($cutoff); ?>" /></td>
                   </tr><?php endforeach; endif; ?>
                   </tbody>
               </table>
               <br/>
                <!--<select name="chan_id">
                    <?php if(is_array($channels)): foreach($channels as $chan_id=>$channel): ?><option value="<?php echo ($chan_id); ?>"
                        <?php if($app['chan_id'] == $chan_id): ?>selected="selected"<?php endif; ?>
                    ><?php echo ($channel); ?></option><?php endforeach; endif; ?>
                </select>-->

               <label>是否排重</label>
               <select name="is_repeat" id="" class="input-xlarge" required="true" autofocus="true">
                   <?php if($app['is_repeat'] == '否'): ?><option value="否" selected="selected">  否</option>
                   <option value="是"> 是 </option>
                   <?php else: ?>
                   <option value="否" > 否 </option>
                   <option value="是" selected="selected"> 是 </option><?php endif; ?>
               </select>

               <label>下载地址</label>
				<input type="text" name="appstore_url" value="<?php echo ($app["appstore_url"]); ?>" class="input-xlarge" required="true" autofocus="true" >
               <label>Url Scheme</label>
               <input type="text" name="url_scheme" value="<?php echo ($app["url_scheme"]); ?>" class="input-xlarge" required="true" autofocus="true" >
				<label>备注</label>
        <textarea name="remark" class="input-xlarge"  autofocus="true" rows="5" cols="10"><?php echo ($app["remark"]); ?></textarea>


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
<script type="text/javascript">
    $(document).ready(function(){
        var QS_city=new Array()
        OpenCategoryLayer(
                "#district_cn",
                "#sel_district",
                "#district_cn",
                "#citycategory",
                QS_city,
                14);
    });
</script>
<script type="text/javascript">
    function check(){
        var temp;
        if ($('#citycategory').val() == null || $('#citycategory').val()== "") {
            alert('请选择渠道');
            return false;
        };
        //判断回调比例配置是否符合规范
        $('#bili input').each(function(){
            if($(this).val() == null || $(this).val()==""){
                alert("请配置回调比例");
                temp = 1;
                return false;
            }

            if (!$.isNumeric($(this).val())) {
                temp = 1;
                alert("回调比例应为数字");
                return false;
            };
            if ($(this).val() > 100 || $(this).val() < 0) {
                temp = 1;
                alert("回调比例是0到100");
                return false;
            };
        });

        if (temp) {
            return false;
        };
        return true;
    }

</script>

<script>
    function getbundle(){
        var appid = document.getElementById('bundle').value;
        var url = "<?php echo U('Backend/App/getBundle');?>";
        $.post(url, {appid: appid},function(e){
            if (e) {
                document.getElementById('bundleid').value=e.replace(/\"/g, "");
            } else {
                alert('获取错误');
            }

        });
    }
</script>
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