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
<link rel="stylesheet" href="/lhq/Public/mobile/css/lanrenzhijia.css" >
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
</script>
<style type="text/css">
    #spot input{
        width:100px;
        margin-right:5px;
    }
    table th{
        width:100px;
        margin-right:5px;
    }
</style>
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">修改任务关系</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
        <form id="tab" method="post" action="" onsubmit="return check()">
            <label>计划名称</label>
      			<input type="text" name="mission_name" value="<?php echo ($mission["mission_name"]); ?>" class="input-xlarge" required="true" autofocus="true" >
            <label>关键词</label>
            <input type="text" name="mkwd" value="<?php echo ($mission["mkwd"]); ?>" class="input-xlarge"  autofocus="true">
            <label>总量</label>
                <input type="text" name="smount" value="<?php echo ($mission["smount"]); ?>" class="input-xlarge" required="true" autofocus="true">
            <label>用户总数量</label>
                <input type="text" name="amount" value="<?php echo ($mission["amount"]); ?>" class="input-xlarge" required="true" autofocus="true" >
            <label>渠道总数量</label>
                <input type="text" name="cmount" value="<?php echo ($mission["cmount"]); ?>" class="input-xlarge" required="true" autofocus="true" >
            
            <label  id='kwd_qx'> 关键词权限 &nbsp;&nbsp;&nbsp;
                <input  type="radio" name="kwd_qx" value="1" required="true"
                <?php if($mission["kwd_qx"] == '1'): ?>checked="checked"<?php endif; ?>
                />开&nbsp;&nbsp;&nbsp;
                <input  type="radio" name="kwd_qx" value="0" required="true"
                <?php if($mission["kwd_qx"] == '0'): ?>checked="checked"<?php endif; ?>
                />关
            </label>
            <div id="gjc" >
                <table>
                    <tr style="margin-bottom:10px">
                        <th >关键字</th>
                        <th >渠道数量</th>
                        <th >渠道已完成数量</th>
                        <th >零花钱数量</th>
                        <th >零花钱已完成数量</th>
                        <th><input style="width:20px ; color: green;background-color: white; border: 1px solid green" type="button" name="" id='add' value="+"></th>
                    </tr>

                    <tbody id="spot">
                        <?php if(is_array($kwdArrs)): foreach($kwdArrs as $key=>$kwdArr): ?><tr>
                               <!--  <td><input type="hidden" name="id[]" value="<?php echo ($kwdArr["id"]); ?>"></td> -->
                                <td><input type="text" name="kwd[]" value="<?php echo ($kwdArr["kkwd"]); ?>"></td>
                             <!--   <input type="text" name="channels[]" style='width: 10%;margin-right: 10px;' value="<?php echo ($kwdArr["chan_id"]); ?>"> -->
                                <td><input type="text" name="mount[]" value="<?php echo ($kwdArr["mount"]); ?>"></td>
                                <td><input type="text" name="rmount"  value="<?php echo ($kwdArr['mount']-$kwdArr['mount_re']); ?>" readonly="readonly"></td>
                                <td><input type="text" name="lmount[]" value="<?php echo ($kwdArr["lmount"]); ?>"></td>
                                <td><input type="text" name="lrmount" value="<?php echo ($kwdArr['lmount']-$kwdArr['lmount_re']); ?>" readonly="readonly"></td>
                                <td><input type="button" class="remove" value="-" style="width:20px ;color: green;background-color: white; border: 1px solid green;"/></td>
                            </tr><?php endforeach; endif; ?> 
                    </tbody>
                </table>
            </div>            
		    <label>出货价</label>
                <input type="text" name="price" value="<?php echo ($mission["price"]); ?>" class="input-xlarge" required="true" autofocus="true" >
            <label>标签</label>
                <input type="text" id="label" name="label" value=" <?php echo ($mission["label_name"]); ?>" class="input-xlarge" required="true" autofocus="true" >
                <a class="btnn btnp  theme-login" href="javascript:;">添加标签</a>
                <div class="theme-popover">
                    <div class="theme-poptit">
                        <a href="javascript:;" title="关闭" class="close">×</a>
                        <h3>请选择标签</h3>
                    </div>
                    <div action="<?php echo U('missionAdd');?>" method="get">
                       <?php if(is_array($labels)): foreach($labels as $key=>$label): ?><label><input name="label_name" type="checkbox" value="<?php echo ($label["label_name"]); ?>"/><?php echo ($label["label_name"]); ?> </label><?php endforeach; endif; ?>
                    </div>
                    <div class="btn-toolbar ">
                        <button type="button" class="btnprimary" onclick="fun()"><strong>确定</strong></button>
                    </div>
                </div>
<!--             <label>渠道回调比例</label>
                <input type="text" name="scale" value="<?php echo ($mission["scale"]); ?>" class="input-xlarge" required="true" autofocus="true" >
            <label>选择渠道商</label>
                <select name="channel_id">
                    <?php if(is_array($channels)): foreach($channels as $channel_id=>$channel): ?><option value="<?php echo ($channel_id); ?>"
                        <?php if($mission['channel_id'] == $channel_id): ?>selected="selected"<?php endif; ?>
                        ><?php echo ($channel); ?></option><?php endforeach; endif; ?>
                </select>
-->                
            <label>选择广告</label>
                <select name="app_id" id="ProviderAppid" required="true">
                    <?php if(is_array($apps)): foreach($apps as $app_id=>$app): if($app_id == 0): ?><option value="">请选择广告名称</option>
                        <?php else: ?>
                            <option value="<?php echo ($app_id); ?>"
                            <?php if($mission['app_id'] == $app_id): ?>selected="selected"<?php endif; ?>
                            ><?php echo ($app); ?></option><?php endif; endforeach; endif; ?>
                </select> 
            <label>广告类型</label>
                <select name="adtype_id" id="DropDownAdtypeid">
                    <?php if(is_array($adtypes)): foreach($adtypes as $adtype_id=>$adtype): ?><option value="<?php echo ($adtype_id); ?>"
                            <?php if($mission['adtype_id'] == $adtype_id): ?>selected="selected"<?php endif; ?>
                        ><?php echo ($adtype); ?></option><?php endforeach; endif; ?>
                </select>
			<label>销售开始时间</label>
                <input type="text" id="start_date" name="start_time" value="<?php echo ($mission["start_time"]); ?>" placeholder="起始时间" >
            <label>销售结束时间</label>
                <input type="text" id="end_date" name="end_time" value="<?php echo ($mission["end_time"]); ?>" placeholder="结束时间" >
            <label>任务步骤</label>
                <textarea name="des" rows="5" class="input-xlarge"><?php echo ($mission["des"]); ?></textarea>
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
                <input type="hidden" name="httpref" value="<?php echo ($_SERVER['HTTP_REFERER']); ?>">
            <div class="btn-toolbar">
		        <button type="submit" class="btn btn-primary"><strong>保存并提交审核</strong></button>
	        </div>
		</form>
        </div>
    </div>
</div>	
<!---操作的确认层，相当于javascript:confirm函数-->
<?php echo ($osadmin_action_confirm); ?>
<script>
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
    $(document).ready(function(){
        $('#DropDownAdtypeid').attr("disabled",true);
        $a=$('#kwd_qx input[name="kwd_qx"]:checked').val();
        if($a==0){
          $("#gjc").hide();
        }else if($a==1){
          $("#gjc").show();
        }
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
    //关键词div模块显示控制
    $('#kwd_qx').change(function () {
        $a=$('#kwd_qx input[name="kwd_qx"]:checked').val();
        if($a==1){
            $('#gjc').show();
        }else if($a==0){
            $('#gjc').hide();
        }
    });
    //添加关键词列
    $("input#add").click(function(){     
        addSpot();
    });
    //添加关键词列方法
    function addSpot() {
        $('#spot').append(
            '<tr>' +
            '<td><input type="text" name="kwd[]"> </td>' +
      <!--  '<td><input type="text" name="channels[]"> </td> ' +  -->
            '<td><input type="text" name="mount[]"> </td> ' +
            '<td><input type="text" name="rmount" readonly="readonly"> </td>' +
            '<td><input type="text" name="lmount[]"> </td> ' +
            '<td><input type="text" name="lrmount" readonly="readonly"> </td>' +
            '<td><input type="button" class="remove" value="-" style="width:20px ;color: green;background-color: white; border: 1px solid green;"/></td>' +
            '</tr>')
        .find("input.remove").click(function(){
            $(this).parent().parent().remove();
        });
    };

    //移除关键词的某列
    $('input.remove').click(function(){
        $(this).parent().parent().remove();
    });

    //提交表单判断
    function check(){ 
        var r = true;
        var start_date = $('#start_date').val();//任务开始时间
        var end_date = $('#end_date').val();//任务结束时间
        //获取现在时间
        var nowDateTime = getNowFormatDate();

        var smount = parseInt($("input[name='smount']").val()); //总量
        var amount = parseInt($("input[name='amount']").val()); //用户总数量
        var cmount = parseInt($("input[name='cmount']").val()); //渠道总数量
        //判断总量和  用户总数量加渠道总数量  是否相等
        if (smount != (amount + cmount)) {
            alert('总量和分量不相等');
            return r = false;
        }; 
        //获取关键词开关
        var kwd_qx=$('#kwd_qx input[name="kwd_qx"]:checked').val();

        if (kwd_qx == 1) {
            if (start_date < nowDateTime && end_date > nowDateTime ) {       
                //此任务是进行中的任务   循环每个tr  修改后的数量 不能少于已完成数量
                $('#spot tr').each(function(){
                    kwd = $(this).children('td').eq(0).children('input').val();      //关键词
                    mount = $(this).children('td').eq(1).children('input').val();    //渠道数量
                    mount_re = $(this).children('td').eq(2).children('input').val(); //渠道完成量
                    lmount = $(this).children('td').eq(3).children('input').val();   //零花钱数量
                    lmount_re = $(this).children('td').eq(4).children('input').val();//零花钱完成量
                    //渠道数量和已完成数量比较
                    if (eval(mount) < eval(mount_re)) {
                        alert("关键词 "+ kwd+" 渠道数不得少于已完成数量");
                        return r = false;
                    };
                    //零花钱数量和已完成数量比较
                    if (eval(lmount) < eval(lmount_re)) {
                        alert("关键词 "+ kwd+" 零花钱数不得少于已完成数量");
                        return r = false;
                    };
                });
            }
            //判断关键词渠道(零花钱)数量和是否等于渠道(零花钱)总数量
            var mount = 0;
            var lmount = 0;
            $('#spot tr').each(function(){
                mount =accAdd(mount,parseInt($(this).children('td').eq(1).children('input').val())); 
                lmount = accAdd(lmount,parseInt($(this).children('td').eq(3).children('input').val()));
            });
            if (eval(mount) != cmount) {
                alert('渠道数量和不等于渠道总量');
                return r = false;
            };
            if (eval(lmount) != amount) {
                alert('零花钱数量和不等于零花钱总量');
                return r = false;
            };

        }
        return r;
    }
    //获取当前时间  格式为yyyy-mm-dd hh:mm:ss
    function getNowFormatDate() {
        var date = new Date();
        var seperator1 = "-";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
                + " " + date.getHours() + seperator2 + date.getMinutes()
                + seperator2 + date.getSeconds();
        return currentdate;
    }
    //加法
    function accAdd(arg1,arg2){
        var r1,r2,m;
        try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
        try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
        m=Math.pow(10,Math.max(r1,r2))
        return (arg1*m+arg2*m)/m
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