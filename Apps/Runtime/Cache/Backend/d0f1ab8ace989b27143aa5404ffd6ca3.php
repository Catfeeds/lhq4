<?php if (!defined('THINK_PATH')) exit();?><!--<{include file="simple_header.tpl"}>-->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo ($page_title); ?> - <<?php echo (ADMIN_TITLE); ?>></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="/lhq/Public/lib/bootstrap/css/bootstrap.css">
    <?php if($_SESSION['osa_user_info']['template']!= ''): ?><link rel="stylesheet" href="/lhq/Public/stylesheets_<?php echo ($_SESSION['osa_user_info']['template']); ?>/theme.css">
    <?php else: ?>
    <link rel="stylesheet" href="/lhq/Public/stylesheets_default/theme.css"><?php endif; ?>
    <link rel="stylesheet" href="/lhq/Public/lib/font-awesome/css/font-awesome.css">

    <script src="/lhq/Public/lib/jquery-1.8.1.min.js" ></script>
	<script src="/lhq/Public/js/other.js" ></script>

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

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body class="simple_body">

    <div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                </ul>
                <a class="brand" href="<?php echo U(index);?>"><span class="first"></span> <span class="second"><?php echo ($smarty["const"]["COMPANY_NAME"]); ?></span></a>
        </div>
    </div>
<div>
<div class="container-fluid">	
        <div class="row-fluid">
			<div class="http-error">
				
				<?php if($type == success): ?><h1>Yep!</h1>
				<?php elseif($type == error): ?>
				<h1>Oops!</h1>
				<?php else: ?>
				<h1>O~!</h1><?php endif; ?>
				<p class="info"><?php echo ($message_detail); ?></p>
				<h2>返回 <a href="<<?php echo ($smarty["const"]["ADMIN_URL"]); ?>><?php echo ($forward_url); ?>"><?php echo ($forward_title); ?></a></h2>
			</div>
	<div>
		                    
	
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