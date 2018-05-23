<?php

namespace Admin\Model;
use Think\Model;

/**
 *
 */

class PeriodsModel extends Model {

	protected $tableName = 'periods';


	// 获取云购表 ID (没有就新增)
	public function getPeriodsId($goodsId = 0, $qishu = 0){
		$pid = $this->where(array(
			'goodsId' => $goodsId,
			'qishu' => $qishu,
		))->getField('id');
		
		if($pid) return $pid;
		
		$data = M('goods')->where(array(
				'id' => $goodsId,
				// 'qishu' => $qishu,
				'status' => 1,
		))->find();

		if($data && $qishu < $data['maxqishu']){
			$insertData = array(
					'goodsId' => $data['id'],
					'title' => $data['title'],
					'image' => $data['image'],
					'type' => $data['typeid'],
					'fenshu' => $data['fenshu'],
					'limit_buy' => $data['limit_buy'],
					'price' => $data['price'],
					'qishu' => $qishu,
					'total' => $data['originprice'],
					'status' => 3,
					'creatDate' => time(),
			);


			if($this->create($insertData)){
				$pid = $this->add();
				
				if(!buildPcode($pid)){
					return false;
				}
				
				return $pid;
				
			}else{
				return false;
			}
		}elseif($qishu >= $data['maxqishu']){
			if($qishu > $data['maxqishu']){
				M('goods')->save(array(
					'id' => $goodsId,
					'status' => 0,
				));
			}
			return false;
		}
		
		
		$this->addAll();
	}
	
	
	
	
	
	
	
}