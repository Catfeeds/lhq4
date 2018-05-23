/*@
 * 简爱 JS 常用函数 合集
 * sc.419@qq.com
 *
 * loadJs(url, fun) -- 加载 JS
 * cache(key, value) -- HTML5本地缓存
 * cookie(key, val, seconds) -- Cookie 操作函数
 * date(time, str) -- 格式化时间戳
 * count(obj) -- length 增强
 * page(obj) -- 创建分页
 * array_merge(arr, ...) -- 返回合并数组 (别名 extend 不修改原数组)
 * String
 *   tpl 解析 {{key}}
 *   face 解析 [key]
 *
 * md5 SparkMD5.hash (暂无)
**/

/* 加载 JS */
function loadJs(url, call){
	var script = document.createElement("script");
	script.src = url;
	script.onload = script.onreadystatechange = function(){
		if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
			if(typeof call == 'function') call();
		}
	};
	document.getElementsByTagName("head")[0].appendChild(script);
}

/**
* 函数用途描述
* @date: 2015年11月13日 上午10:46:50
* @author: 王崇全
* @param: string 要获取的参数名称
* @return:参数值
*/
function getUrlVal(name)
{
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

/* HTML5本地缓存 localStorage 读写函数封装 简爱 2014/11/16 */
function cache(key, value){
	if(typeof key == 'undefined' || typeof localStorage == 'undefined'){
		return null;
	}else if(typeof value == 'undefined'){
		value = localStorage.getItem(key);
		if(/^\s*[\{\[][\S\s]*[\]\}]\s*$/.test(value))
			value = JSON.parse(value);
		return value;
	}else if(value == null){
		localStorage.removeItem(key);
	}else{
		if(typeof value == 'object') value = JSON.stringify(value);
			localStorage.setItem(key, value);
	}
}


/* 模板解析 {{key}} --key -- */
String.prototype.tpl = function(obj) {
	return this.replace(/\{\{\w+\}\}|--\w+--/gi, function(matchs) {
		var returns = obj[matchs.replace(/[\{\}-]/g, "")];
		return (returns + "") == "undefined" ? "": returns;
	});
};


/* 表情解析 [face] */
String.prototype.face = function(obj) {
    return this.replace(/\[.+?\]/gi, function(matchs) {
        var returns = obj[matchs.replace(/[\[\]]/g, "")];
        return (returns + "") == "undefined" ? "": returns;
    });
};


/* Cookie 操作函数 */
function cookie(key, val, seconds) {
	if(typeof val != 'undefined'){
		var exdate = new Date(), seconds = typeof seconds != 'number' ? 604800 : 0;
		exdate.setTime(exdate.getTime() + seconds * 1000);
		document.cookie = key + "=" + escape(val) +
			(!seconds ? "" : ";expires=" + exdate.toGMTString());
	}else if(key){
		if (document.cookie.length > 0) {
			c_start = document.cookie.indexOf(key + "=");
				if (c_start != -1) {
					c_start = c_start + key.length + 1;
					c_end = document.cookie.indexOf(";", c_start);
					if (c_end == -1)
						c_end = document.cookie.length;
					return unescape(document.cookie.substring(c_start, c_end));
				}
		}
		return "";
	}
}


/* 格式化时间戳 */
function _date(time, format){
	var format = format || 'Y-m-d H:i:s', time = time || false;
	return date(format, time);
}
function date(format, time){
	var timestamp = time ? new Date(parseInt(time) * 1000) : new Date();
		format = format || 'Y-m-d H:i:s',
		token = /\\.|[dDjlCNSwzWFmMntLoYyaABgGhHisueIOPTZcrU]/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (value, length) {
			value = String(value);
			length = parseInt(length,10) || 2;
			while (value.length < length)  { value = '0' + value; }
			return value;
		},
		G = timestamp.getHours(),
		i = timestamp.getMinutes(),
		j = timestamp.getDate(),
		n = timestamp.getMonth() + 1,
		o = timestamp.getTimezoneOffset(),
		s = timestamp.getSeconds(),
		u = timestamp.getMilliseconds(),
		w = timestamp.getDay(),
		Y = timestamp.getFullYear(),
		N = (w + 6) % 7 + 1,
		z = (new Date(Y, n - 1, j) - new Date(Y, 0, 1)) / 86400000,
		opts = {
			AmPm: ['am', 'pm', 'AM', 'PM'],
			dayNames: [
				'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday',
				'日', '一', '二', '三', '四', '五', '六'
			],
			monthNames: [
				'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
			]
		}
		flags = {
			// Day
			d: pad(j),
			D: opts.dayNames[w].substr(0, 3),
			j: j,
			l: opts.dayNames[w],
			C: opts.dayNames[w + 7],
			N: N,
			// S: opts.S(j),
			//j < 11 || j > 13 ? ['st', 'nd', 'rd', 'th'][Math.min((j - 1) % 10, 3)] : 'th',
			w: w,
			z: z,
			// Week
			W: N < 5 ? Math.floor((z + N - 1) / 7) + 1 : Math.floor((z + N - 1) / 7) || ((new Date(Y - 1, 0, 1).getDay() + 6) % 7 < 4 ? 53 : 52),
			// Month
			F: opts.monthNames[n - 1],
			m: pad(n),
			M: opts.monthNames[n - 1].substr(0, 3),
			n: n,
			t: '?',
			// Year
			L: '?',
			o: '?',
			Y: Y,
			y: String(Y).substring(2),
			// Time
			a: G < 12 ? opts.AmPm[0] : opts.AmPm[1],
			A: G < 12 ? opts.AmPm[2] : opts.AmPm[3],
			B: '?',
			g: G % 12 || 12,
			G: G,
			h: pad(G % 12 || 12),
			H: pad(G),
			i: pad(i),
			s: pad(s),
			u: u,
			// Timezone
			e: '?',
			I: '?',
			O: (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
			P: '?',
			T: (String(timestamp).match(timezone) || [""]).pop().replace(timezoneClip, ""),
			Z: '?',
			// Full Date/Time
			c: '?',
			r: '?',
			U: Math.floor(timestamp / 1000)
		};
	return format.replace(token, function ($0) {
		return flags.hasOwnProperty($0) ? flags[$0] : $0.substring(1);
	});
}


/* length 增强 针对 奇葩 object */
function count(o){
	var t = typeof o, n = 0;
	if(t == 'string'){
		n = o.length;
	}else if(t == 'object'){
		if(typeof o.length != 'undefined')
			return o.length;
		else
			for(var i in o){n++;}
	}
	return n;
}


/* 创建分页 Bootstrap3 结构 */
function page(i) {
	var o = {
		page: 1, // 当前页
		pages: 0, // 总页数
		total: 0, count: 0, // 总条数 (三选一)
		row: 10, // 未定义总页数 时候需要 每页条数
		prev: '&laquo;', next: '&raquo;',
		start: '首页', end: '尾页',
		href: './?page={{page}}', // 超链接
		on: 'class="active"', // 当前
		addClass: 'pagination-sm', // 添加 class
		// 下面用于 pages 为 0 时
		show: 10,
		off: 'class="disabled"' // 禁用
	};
	o = array_merge(o, i);
	o.total = o.total || o.count;
	if(!o.pages) o.pages = Math.ceil(o.total / o.row);

	var prev = '<li '+ (o.page == 1 ? o.off : '') +'><a href="'+ u(o.page - 1) +'">'+o.prev+'</a></li>',
		next = '<li '+ (o.page == o.pages ? o.off : '') +'><a href="'+ u(o.page + 1) +'">'+o.next+'</a></li>',
		start = end = html = '';


	o.show --;
	startNo = o.page - Math.ceil(o.show / 2);
	startNo = startNo > 0 ? startNo : 1;
	endNo = startNo + o.show;
	endNo = endNo  <= o.pages ? endNo : o.pages;

	if(startNo > 1) start = '<li><a href="'+ u(1) +'" aria-label="Previous"><span aria-hidden="true">'+o.start+'</span></a></li>';
	if(endNo < o.pages) end = '<li><a href="'+ u(o.pages) +'" aria-label="Previous"><span aria-hidden="true">'+o.end+'</span></a></li>';


	for (var p = startNo; p <= endNo; p++) {
		if (o.page == p) {
			html += '<li '+ o.on +'><a href="'+ u(p) +'">'+ p +'</a></li>';
		} else {
			html += '<li><a href="'+ u(p) +'">'+ p +'</a></li>';
		}
	}

	function u(p){return (p > 0 && p <= o.pages ? o.href.tpl({page: p}) : 'javascript:void(0);')}

	return '<ul class="pagination '+ o.addClass +'">' + start + prev + html + next + end + '</ul>';
}


/* 合并数组 */
var extend = array_merge;
function array_merge() {
/* discuss at: http://phpjs.org/functions/array_merge/ */
	var args = Array.prototype.slice.call(arguments),
		argl = args.length,
		arg,
		retObj = {},
		k = '',
		argil = 0,
		j = 0,
		i = 0,
		ct = 0,
		toStr = Object.prototype.toString,
		retArr = true;

	for (i = 0; i < argl; i++) {
		if (toStr.call(args[i]) !== '[object Array]') {
			retArr = false;
			break;
		}
	}

	if (retArr) {
		retArr = [];
		for (i = 0; i < argl; i++) {
			retArr = retArr.concat(args[i]);
		}
		return retArr;
	}

	for (i = 0, ct = 0; i < argl; i++) {
		arg = args[i];
		if (toStr.call(arg) === '[object Array]') {
			for (j = 0, argil = arg.length; j < argil; j++) {
				retObj[ct++] = arg[j];
			}
		} else {
			for (k in arg) {
				if (arg.hasOwnProperty(k)) {
					if (parseInt(k, 10) + '' === k) {
						retObj[ct++] = arg[k];
					} else {
						retObj[k] = arg[k];
					}
				}
			}
		}
	}
	return retObj;
}


/* 合并网址 */
function http_url(url, query){
	var url = url.replace(/#.*$/, '');
	url += ( /\?/.test(url) ? "&" : "?" ) + query;
	return url;
}


/* 新窗口打开链接 */
function openWin(url, id, w, h){
	var w = w || 1024, h = h || 720;
	window.open(url, id, 'width='+w+',height='+h+',fullscreen=no,scrollbars=no,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
}
/* 窗口居中 */
function moveWinCanter(){
	window.moveTo( (window.screen.width - window.innerWidth) / 2,
		((window.screen.height - window.innerHeight) / 2) - 20 );
}

if(jQuery){
	$.fn.serializeJson=function(){
		var serializeObj={};
		$(this.serializeArray()).each(function(){
			serializeObj[this.name]=this.value;
		});
		return serializeObj;
	};
}

function debug(text, type){ return;
	msg(text, type);
}


// success error warning notification information
function msg(text, type){
	if(noty) return noty({
		text: text,
		type: type || '',
		// relax defaultTheme bootstrapTheme
		theme: 'relax',
		layout: 'topCenter',
		/*
		animation : {
			open : 'animated bounceInDown',
			close : 'animated bounceOutDown',
			easing : 'swing',
			speed : 0,
			fadeSpeed: 'fast',
		},
		*/
		modal: (type && type == 'error' ? true : false),
		timeout: 1500
	});
}






/* 通过 keyup 扩展的 change 需 jQuery 1.9+*/
function change(domStr, _call, secnds){
	if(!domStr || typeof _call != 'function' || jQuery.fn.jquery < 1.9) return;

	var secnds = secnds || 700, _call = _call;

	$(document)
		.on('keyup', domStr, function(){
			var newVal = $.trim(this.value);

			if(newVal != this.trimVal){
				this._timeOut && clearTimeout(this._timeOut);
				this.trimVal = newVal;
				var _this = this;

				this._timeOut = setTimeout(function(){
					_call(_this.value, _this);
				}, secnds);
			}
		});
}


