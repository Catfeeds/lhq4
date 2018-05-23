<{include file ="header.tpl"}>
<{include file ="navibar.tpl"}>
<{include file ="sidebar.tpl"}>
<!-- TPLSTART 以上内容不需更改，保证该TPL页内的标签匹配即可 -->

<div style="border:0px;padding-bottom:5px;height:auto">
	<form action="" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label> 选择起始时间 </label>
		<input type="text" id="start_date" name="start_date" value="<{$_GET.start_date}>" placeholder="起始时间" >
	</div>
	<div style="float:left;margin-right:5px">
		<label>选择结束时间</label>
		<input type="text" id="end_date" name="end_date" value="<{$_GET.end_date}>" placeholder="结束时间" > 
	</div>
    <div style="float:left;margin-right:5px">
        <label>广告应用:</label>
        <!--<{html_options name="app_id" id="DropDownApps"  options=$apps selected=$_GET.app_id}> -->
        <input type="text" name="app_name" value="<{$_GET.app_name}>" placeholder="输入应用名称" >

    </div>
	<div style="float:left;margin-right:5px">
		<label>渠道商:</label>
		<{html_options name="chan_id" id="DropDownChannels"  options=$channels selected=$_GET.chan_id}>
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary"><strong>检索</strong></button>
	</div>
	<div style="clear:both;"></div>
	</form>
</div>

<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">渠道报表</a>
	<div id="page-stats" class="block-body collapse in">
	<table class="table table-striped">
			<thead>
			<tr>
				<th>广告主</th>
				<th>广告应用</th>
				<th>渠道商</th>
				<th>渠道点击数</th>
				<th>渠道激活数</th>
				<th>渠道转化率</th>
			</tr>
			</thead>
			<tbody>							  
			<{foreach name=clog from=$clogsa item=clog}>
				 
				<tr>
				 
				<td><{$providers[$clog.provider_id]}></td>
				
				<td><{$apps[$clog.app_id]}></td>
				 
				<td><{$channels[$clog.chan_id]}></td>
				

				<td><{$clog.count}></td>
				
				<{foreach name=callback from=$callbacks item=callback}>
					<{if $callback.app_id eq $clog.app_id and $callback.chan_id eq $clog.chan_id}>
				 
				<td><{$callback.count}></td>
				
				<{if $clog.count eq "0" or $clog.count eq ""}>
				
				<td>0</td>
				<{else}>
				
				<td><{$callback.count/$clog.count*100}>%</td>
				
				<{/if}>
				<{/if}>
				<{/foreach}>
			
				</tr>
			<{/foreach}>
		  </tbody>
		</table>		
		<!--- START 分页模板 --->
               <{$page_html}>
			   <!--- END --->	
			    每页25项					
	</div>
</div>
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
</script>
<!-- TPLEND 以下内容不需更改，请保证该TPL页内的标签匹配即可 -->
<{include file="footer.tpl" }>