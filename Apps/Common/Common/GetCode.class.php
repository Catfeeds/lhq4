<?php
namespace Common\Common;

/*
 * 进制转换--用于生成卡号
 */
class GetCode {
	//密码字典
	protected static $dic = array(
0=>'0',    1=>'1',    2=>'2',    3=>'3',    4=>'4',    5=>'5',    6=>'6',    7=>'7',    8=>'8',    9=>'9'
);
	//ID
	//private $ids;
	//长度
	//private $format = 8;

	public  static function encodeID($int, $format=8) {
		$dics =self::$dic;
		//print_r($dics);exit;
		$dnum = 36; //进制数
		$arr = array ();
		$loop = true;
		while ($loop) {
			$arr[] = $dics[bcmod($int, $dnum)];
			$int = bcdiv($int, $dnum, 0);
			//echo $int.'<br/>';
			if ($int == '0') {
				$loop = false;
			}
		}
		if (count($arr) < $format)
			$arr = array_pad($arr, $format, $dics[0]);

		return implode('', array_reverse($arr));
	}

	public function decodeID($ids) {
		$dics = $this->dic;
		$dnum = 36; //进制数
		//键值交换
		$dedic = array_flip($dics);
		//去零
		$id = ltrim($ids, $dics[0]);
		//反转
		$id = strrev($id);
		$v = 0;
		for ($i = 0, $j = strlen($id); $i < $j; $i++) {
			$v = bcadd(bcmul($dedic[$id {
				$i }
			], bcpow($dnum, $i, 0), 0), $v, 0);
		}
		return $v;
	}

    function build_order_no($id) {
        $pre = sprintf('%02d', $id / 14000000);        // 每1400万的前缀
       // dump($pre);
        $tempcode = sprintf('%09d', sin(($id % 14000000 + 1) / 10000000.0) * 123456789);    // 这里乘以 123456789 一是一看就知道是9位长度，二则是产生的数字比较乱便于隐蔽
        $seq = '371482506';        // 这里定义 0-8 九个数字用于打乱得到的code
        $code = '';
       // dump($tempcode);
        for ($i = 0; $i < 9; $i++) $code .= $tempcode[ $seq[$i] ];
        return $pre.$code;
    }

}
?>