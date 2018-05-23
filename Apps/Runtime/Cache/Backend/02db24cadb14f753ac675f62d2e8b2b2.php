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

<script src="/lhq/Public/ckeditor/ckeditor.js"></script>
<!-- <script src="<<?php echo ($smarty["const"]["ADMIN_URL"]); ?>>/assets/mobile/js/jquery.min.js"></script>-->

<link rel="stylesheet" href="/lhq/Public/mobile/css/lanrenzhijia.css" >
<link href="/lhq/Public/select2/select2.css" rel="stylesheet" >
<!--<script type="text/javascript" src="${resources}/js/bootstrap/select2.min.js"></script>

<script type="text/javascript" src="${resources}/js/bootstrap/custom.js"></script>
<script type="text/javascript" src="${resources}/js/bootstrap/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="${resources}/js/bootstrap/jquery.tagsinput.min.js"></script>-->
<script type="text/javascript" src="/lhq/Public/select2/select2.min.js"></script>
<!--<script src="/lhq/Public/js/jquery.min.js"></script>-->
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

    })
    jQuery(".select2").select2({
        width: '100%'
    });
</script>


<div class="well">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">请填写计划任务</a></li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane active in" id="home">

            <form id="tab" method="post" action="">
                <label>计划名称</label>
                <input type="text" name="mission_name" value="<?php echo ($_POST["mission_name"]); ?>" class="input-xlarge" required="true" autofocus="true" >
                <label>关键字</label>
                <input type="text" name="kwd" value="<?php echo ($_POST["kwd"]); ?>" class="input-xlarge" required="true" autofocus="true" >
                 
                
                 
                <label>出货价</label>
                <input type="text" name="price" value="<?php echo ($_POST["price"]); ?>" class="input-xlarge" required="true" autofocus="true" >
                <label>订购数量</label>
                <input type="text" name="amount" value="<?php echo ($_POST["amount"]); ?>" class="input-xlarge" required="true" autofocus="true" >
                

                <label>标签</label>
                <input type="text" id="label" name="label" value=" " class="input-xlarge" required="true" autofocus="true" >
                <!--<div class="theme-buy">-->
                    <a class="btnn btnp  theme-login" href="javascript:;">添加标签</a>
                <!--</div>-->
                <div class="theme-popover" style="overflow: auto;">
                    <div class="theme-poptit">
                        <a href="javascript:;" title="关闭" class="close">×</a>
                        <h3>请选择标签</h3>
                    </div>
                    <div action="<?php echo U('missionAdd');?>" method="get">
                        <!--<form class="theme-signin" name="loginform" action="" method="post">-->
                            <!--<ol>-->
                                <?php if(is_array($labels)): foreach($labels as $key=>$label): ?><!--<form action="" method="get">-->
                                        <label><input name="label_name" type="checkbox" value="<?php echo ($label["label_name"]); ?>"/><?php echo ($label["label_name"]); ?> </label>
                                    <!--</form>--><?php endforeach; endif; ?>
                            <!--</ol>-->
                        <!--</form>-->
                    </div>
                    <div class="btn-toolbar ">
                        <!--<a type="submit" class="btn btn-primary" value="<?php echo ($_POST["label"]); ?>">确定</a>-->
                        <button type="button" class="btnprimary" onclick="fun()"><strong>确定</strong></button>

                    </div>
                </div>
                <label>渠道回调比例</label>
                <input type="text" name="scale" value="<?php echo ($_POST["scale"]); ?>" class="input-xlarge" required="true" autofocus="true" >

                <label>选择渠道商</label>
                <select name="channel_id">
                      <?php if(is_array($channels)): foreach($channels as $channel_id=>$channel): ?><option value="<?php echo ($channel_id); ?>"><?php echo ($channel); ?></option><?php endforeach; endif; ?>
                </select>
                <label>广告应用名称</label>
            <!--    <input type="text" name="appname" class="input-xlarge"  id="appid" required="true" autofocus="true" >

                <div id="appid1">

                </div>-->
          <!--     <input list="pasts" name="app_id"  id="ProviderAppid" value="">
                <datalist id="pasts" required="true" >
                    <?php if(is_array($apps)): foreach($apps as $app_id=>$app): ?><option value="<?php echo ($app_id); ?>"><?php echo ($app); ?></option><?php endforeach; endif; ?>
                </datalist>
         -->      <select name="app_id" id="ProviderAppid" required="true">
                      <?php if(is_array($apps)): foreach($apps as $app_id=>$app): if($app_id == 0): ?><option value="">请选择广告名称</option>
                              <?php else: ?>
                              <option value="<?php echo ($app_id); ?>"><?php echo ($app); ?></option><?php endif; endforeach; endif; ?>
                </select>
                <label>广告类型</label>
                <!-- <{html_options name="adtype_id" id="DropDownAdtypeid"  options=$adtypes selected=$_POST.adtype_id}> -->
           
                <select name="adtype_id" id="DropDownAdtypeid">
                      <?php if(is_array($adtypes)): foreach($adtypes as $adtype_id=>$adtype): ?><option value="<?php echo ($adtype_id); ?>"><?php echo ($adtype); ?></option><?php endforeach; endif; ?>
                </select>

                <label>销售开始时间</label>
                <input type="text" id="start_date" name="start_time" value="<?php echo ($_POST["start_time"]); ?>" placeholder="起始时间" >

                <label>销售结束时间</label>
                <input type="text" id="end_date" name="end_time" value="<?php echo ($_POST["end_time"]); ?>" placeholder="结束时间" >
                <label>任务步骤</label>
                <textarea name="des" rows="5" class="input-xlarge"><?php echo ($_POST["des"]); ?></textarea>
                <script type="text/javascript">
                    CKEDITOR.replace( 'des',{
                        filebrowserBrowseUrl : '/lhq/Public/ckfinder/ckfinder.html',
                        filebrowserImageBrowseUrl : '/lhq/Public/ckfinder/ckfinder.html?type=Images',
                        filebrowserFlashBrowseUrl : '/lhq/Public/ckfinder/ckfinder.html?type=Flash',
                        filebrowserUploadUrl : '/lhq/Public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                        filebrowserImageUploadUrl : '/lhq/Public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                        filebrowserFlashUploadUrl : '/lhq/Public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
                        extraPlugins: 'codesnippet',codeSnippet_theme: 'Pojoaque'
                    });
                </script>
                <div class="btn-toolbar">
                    <button type="submit" class="btn btn-primary"><strong>保存并提交审核</strong></button>&nbsp;&nbsp;
                    <button type="submit" class="btn btn-primary"><strong><a href="<?php echo U('mission');?>" >取消</a></strong></button>

                </div>

            </form>
        </div>
    </div>
</div>
<script>

 /*   $(function(){
        $("#appid").keyup(function(){
            var name=$("#appid").val();
            // alert(name);//die;
            url = "<?php echo U('Mission/appidAjax');?>";
  $.post(url,{name:name},function(res) {
  //console.log(res);
  var html='<select name="" >';
  for (var i = 0, max = res.length; i < max; i++) {
  html+= '<option value="'+res[i].app_id+'">'+res[i].app_name+'</option>';
  }
  html+= '</select>';
  $("#appid1").html(html);


  },'json');
    })
    });*/
    $(document).ready(function(){
        $('#DropDownAdtypeid').attr("disabled",true); 
        $("#add").hide();
         $("input#add").click(function(){     addSpot();
  });

    });
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
    //菜单联动  广告和广告类型
    $("#ProviderAppid").change(function(){
        // $('#DropDownAdtypeid').empty();
        var appid = $(this).val();

        $('#DropDownAdtypeid').attr("disabled",false);
        $.get("<?php echo U('Mobile/Apiajax/adtypeAjax');?>",{appid:appid},function(res){  
  
            var aa = res;
                if (res) {
                    $('#DropDownAdtypeid').find("option[value="+aa+"]").attr("selected",true);
               $('#DropDownAdtypeid').attr("disabled",true); 
            }else{
                $('#DropDownAdtypeid').find("option[value=0]").attr("selected",true);
                $('#DropDownAdtypeid').attr("disabled",true); 
            }
        },'json');
    });
    
    $('#tab').submit(function(){
        $('#DropDownAdtypeid').attr("disabled",false);
    });

    function fun(){
        $('.theme-popover-mask').fadeOut(100);
        $('.theme-popover').slideUp(200);
        obj = document.getElementsByName("label_name");
        check_val = [];
        for(k in obj){
            if(obj[k].checked)
                check_val.push(obj[k].value);
        }
       var bale= check_val.join();
        $('#label').val(bale);
    };
    $('#kwd_qx').change(function () {
        // body...
       $a=$('#kwd_qx input[name="kwd_qx"]:checked').val();
       if($a==1){
            $('#add').show();
       }else if($a==0){
            $('#add').hide();
       }
    });
    function addSpot() {
        $('div#spots').append(
            '<div class="spot">' +
            '<input type="text" name="kwd[]" style="width: 18%;margin-right: 20px;"> ' +
            '<input type="text" name="mount[]" style="width: 15%">  ' +
            '<input type="button" class="remove" value="-" style="width: 20px; color: green;background-color: white; border: 1px solid green;"/></div>')
        .find("input.remove").click(function(){
            $(this).parent().remove();
            $('input#add').show();
        });
    };
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