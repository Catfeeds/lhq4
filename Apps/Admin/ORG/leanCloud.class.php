<?php
/**
 * leanCloud PHP-SDK
**/


namespace Admin\ORG;


class leanCloud {
	static private $delArr = array();

	/* 更新数据 */
	function update($path = false, $data = array()){
		if(!$path || empty($data)) return false;
		$data = is_array($data) ? json_encode($data) : $data;
		return $this->http("classes/$path", $data, 'PUT');
	}

	/* query 升级 cql */
	function cql($cql = false){
		$results = $this->query($cql);
		return json_decode($results['content'], true);
	}

	/* Cloud Query Language 快捷查询 */
	function query($cql = false){
		if(!$cql) return false;
		$data = is_array($cql) ? http_build_query($cql) : 'cql='. urlencode($cql);
		$url = "cloudQuery?$data";
		return $this->http($url);
	}


	/* 启用用户 */
	function onUser($objIds = false){
		if(!$objIds) return false;

		$objIds = is_array($objIds) ? $objIds : explode(',', $objIds);

		$doArr = array();
		foreach ($objIds as $objId){
			$doArr[] = array(
					"method" => "PUT",
					"path" => "/1.1/classes/LazyUser/$objId",
					"body" => array(
							'other' => ''
					)
			);
		}
		return $this->http('batch', array('requests' => $doArr), 'POST');
	}

	/* 禁用用户 */
	function delUser($objIds = false){
		if(!$objIds) return false;

		$objIds = is_array($objIds) ? $objIds : explode(',', $objIds);

		$doArr = array();
		foreach ($objIds as $objId){
			$doArr[] = array(
					"method" => "PUT",
					"path" => "/1.1/classes/LazyUser/$objId",
		            "body" => array(
		            		'other' => 'disabled'
		            )
			);
		}
		return $this->http('batch', array('requests' => $doArr), 'POST');
	}

	/* 删除对象 */
	function del($className = false, $objIds = false, $del = true){
		if((!$objIds || !$className) && empty(self::$delArr)) return false;

		$objIds = is_array($objIds) ? $objIds : explode(',', $objIds);

		if(is_array($objIds) && !empty($objIds))
			foreach ($objIds as $objId){
				self::$delArr[] = array(
						"method" => "DELETE",
						"path" => "/1.1/classes/$className/$objId"
				);
			}

		if($del){
			$json = $this->http('batch', array('requests' => self::$delArr), 'POST');
			self::$delArr = array();
			return $json;
		}
	}


	/* HTPP 请求 */
	function http($url, $data = array(), $type = 'GET'){
		$headers = array(
				"X-LC-Id: ". C('LEANCLOUD_ID'),
				"X-LC-Key: ". C('LEANCLOUD_KEY'),
				"Content-Type: application/json",
		);

		// $url = "https://leancloud.cn:443/1.1/classes/LazyUser?limit=10&&order=-updatedAt&&";
		$url = "https://leancloud.cn:443/1.1/$url";

		$data = is_array($data) ? json_encode($data) : $data;

		$res = httpGet($url, $data, $headers, $type);

		return $res;
	}


	/* 删除对象 */
	function formtDateArr(&$arr = array()){
		if(!is_array($arr) || empty($arr)) return;

		if(isset($arr['createdAt']) || isset($arr['createdAt'])){
			if(isset($arr['createdAt'])) $arr['createdAt'] = strtotime($arr['createdAt']);
			if(isset($arr['updatedAt'])) $arr['updatedAt'] = strtotime($arr['updatedAt']);
			return;
		}
		
		foreach($arr as &$v){
			if(isset($v['createdAt'])) $v['createdAt'] = strtotime($v['createdAt']);
			if(isset($v['updatedAt'])) $v['updatedAt'] = strtotime($v['updatedAt']);
		}
	}

	/* 获取用户信息 */
	function users($userIds, $key = false){
		$userIds = is_array($userIds) ? $userIds : explode(',', $userIds);
		if($key){
			$temp = array();
			foreach($userIds as $v){
				$temp[] = $v[$key];
			}
			$userIds = $temp;
		}

		$userIds = implode("','", array_unique($userIds));
		$cql = "
			select count(*), objectId, cityNo, nickName, userId, sexType, signature
			from LazyUser
			where userId in ('$userIds')
		";
		$users = $this->cql($cql);

		if(isset($users['results'])){
			$temp = array();
			foreach($users['results'] as $v){
				$temp[$v['userId']] = $v;
			}
			$users = $temp;
		}
		return $users;
	}

	/* 获取文件信息 */
	function files($filesIds, $key = false){
		$filesIds = is_array($filesIds) ? $filesIds : explode(',', $filesIds);
		if($key){
			$temp = array();
			foreach($filesIds as $v){
				$temp[] = $v[$key]['objectId'];
			}
			$filesIds = $temp;
		}

		$filesIds = implode("','", array_unique($filesIds));
		$cql = "
			select count(*), objectId, name, url, mime_type
			from _File
			where objectId in ('$filesIds')
		";
		$files = $this->cql($cql);

		if(isset($files['results'])){
			$temp = array();
			foreach($files['results'] as $v){
				$temp[$v['objectId']] = $v;
			}
			$files = $temp;
		}
		return $files;
	}

}















