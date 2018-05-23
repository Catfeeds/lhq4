/**
* 获取url中指定的参数值
* @date: 2015年11月16日 上午11:46:22
* @author: 王崇全
* @param: string 参数名
* @return: string 相应的参数值
*/
function getUrlParam(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
	var r = window.location.search.substr(1).match(reg);  //匹配目标参数
	if (r!=null) return unescape(r[2]); return null; //返回参数值
}

/**
* 判断一个数组是否为二维数组
* @date: 2015年11月19日 上午09:15:22
* @author: 王崇全
* @param: arr 数组
* @return: 布尔 
*/
function multiarr(arr){

	for (i=0,len=arr.length;i<len;i++){
		if( (arr[i] instanceof Object) || (arr[i] instanceof Array) ){
			return true;
		}
	}		
	return false;
}