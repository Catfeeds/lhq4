<?php
namespace Backend\Controller;
use Think\Controller;
class FlowReportFormController extends ComController{
	public function index(){
		$time = date('Y-m-d',time());
		$start_time = $time.' 00:00 00';  //获取单天时间
		$end_time = $time.' 23:59:59';  //获取单天时间
		$arr['start_time'] =array('egt',$start_time);
        $arr['end_time'] = array('elt',$end_time);
        //查询单天的下单量
		$flowCount = D('mission')->field('sum(amount) as amount')
		->where($arr)
		->find();

		$flow = D('mission')->field('app_id,adtype_id')
		->where($arr)
		->select();

		foreach ($flow as $k => $v) {
			if($v['adtype_id'] == '1' || $v['adtype_id'] == '2'){  //回调产品
				$callback[] = $v['app_id'];
			}
			if ($v['adtype_id'] == '5') {                          //排重产品
				$query[] = $v['app_id'];
			}
			if ($v['adtype_id'] == '3' || $v['adtype_id'] == '6') { //点击产品
				$click[] = $v['app_id'];
			}
			if ($v['adtype_id'] == '4' || $v['adtype_id'] == '7' || $v['adtype_id'] == '8' || $v['adtype_id'] == '9') {                                        //激活产品
				$active[] = $v['app_id'];
			}
		}
		//查询回调产品当天实际消耗流量
		if (!empty($callback)) {
			$where['app_id'] = array('in',$callback);
            $where['time'] = array(array('egt',$start_time),array('elt',$end_time));
            $where['own_result'] = '{"message":"success","success":true}';
          
        	$callbackNum = D('ProviderLog')->field('count(*) as sum')
        	->where($where)
        	->find();
		}
		//查询排重产品当天实际消耗流量
		if (!empty($query)) {
			$where['app_id'] = array('in',$query);
            $where['rtime'] = array(array('egt',$start_time),array('elt',$end_time));
          
        	$queryNum = D('RowRepeat')->field('count(*) as sum')
        	->where($where)
        	->find();
		}
		//查询点击产品当天实际消耗流量
		if (!empty($click)) {
			$where['app_id'] = array('in',$click);
            $where['time'] = array(array('egt',$start_time),array('elt',$end_time));
            $where['own_result'] = '{"message":"success","success":true}';
          
        	$clickNum = D('ChannelLog')->field('count(*) as sum')
        	->where($where)
        	->find();
		}
		//查询激活产品当天实际消耗流量
		if (!empty($active)) {
			$where['app_id'] = array('in',$active);
            $where['time'] = array(array('egt',$start_time),array('elt',$end_time));
            $where['own_result'] = '{"message":"success","success":true}';
          
        	$activeNum = D('ChannelActiveLog')->field('count(*) as sum')
        	->where($where)
        	->find();
		}
		//计算总量
		$amount = $callbackNum['sum'] + $queryNum['sum'] + $clickNum['sum'] + $activeNum['sum'];

	    $this->assign('flowCount', $flowCount );
	    $this->assign('amount',$amount);
	    $this->assign('time',$time);

		$this->display();
	}
}