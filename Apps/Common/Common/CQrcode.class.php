<?php

/**
 * Created by PhpStorm.
 * User: 小宇
 * Date: 2018/5/21
 * Time: 11:37
 */

use Think\Controller;

class CQrcode extends Think\Controller
{
    public function createCode($member_id, $url)
    {
        vendor('phpqrcode.php');
        if (!empty($member_id) && !empty($url)) {
            $c_url = $url . $member_id;
            $error = '二维码图片无法生成';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 4;
            // 二维码名称
            $qrcode = 'qrcode' . $member_id . ".jpg";
            $filename = "./Public/qrcode/".$qrcode;
            QRcode::png($c_url, $filename, $error, $size);
            //保存地址
            if (!is_dir($filename)) {
                mkdir($filename);
            }
            if (!file_exists($filename)) {
                QRcode::png($url, $filename, $error, $size);
            }
            //将二维码图片名称存入数据库
            $update_data = array('qrcode' => $qrcode);
            $result = Member::updateMember($member_id, $update_data);
            if (!$result) {
                echo json_encode(array("message" => "二维码存入失败", "success" => false), JSON_UNESCAPED_UNICODE);
            }
            echo json_encode(array("message" => "生成二维码成功", "success" => true), JSON_UNESCAPED_UNICODE);
        } else {
            //缺少参数
            echo json_encode(array("message" => "部分参数为空", "success" => false), JSON_UNESCAPED_UNICODE);
        }
    }
}