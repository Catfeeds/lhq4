<?php
/**
 * Created by PhpStorm.
 * User: z9944
 * Date: 2018/5/10
 * Time: 11:20
 */
namespace Common\Model;
use Think\Model;
class AlterIdfaModel extends Model {

    /**
     * 插入初始idfa
     * @param $identify
     * @param $phone
     * @return mixed
     */
    public function addAlterIdfa($identify,$phone)
    {
        if (!empty($identify) && !empty($phone)) {
            $data['phone'] = $phone;
            $data['identify'] = $identify;
            $data['time'] = date('Y-m-d H:i:s',time());
            $re = D('AlterIdfa')->add($data);
            if ($re) {
                return $re;
            }
        }
    }

    public function saveIdfa($idfa,$phone)
    {
        if (!empty($idfa) && !empty($phone)) {
            $where['phone'] = $phone;
            $data['idfa'] = $idfa;
            $re = D('AlterIdfa')->where($where)->save($data);
            if ($re) {
                return $re;
            }
        }
    }
}