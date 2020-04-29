<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/20
 * Time: 22:13
 */

namespace app\common\tools\qrcode;

/**
 * 二维码工具
 */
class QrcodeTool
{
    /**
     * 生成一个二维码
     * $information:二维码中包含的信息
     * $logo:logo图片链接
     */
    public static function createQrcode($information="hello word",$logo=null){
        $string=createRandomStr();
        $qrCode= "../runtime/" . $string . ".png";
        \PHPQRCode\QRcode::png($information, $qrCode, 'L', 6, 1);
        $QR = imagecreatefromstring(file_get_contents($qrCode));
        if($logo)
        {
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 6;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        @unlink($qrCode);
        header('Content-Type: image/jpeg');
        imagejpeg($QR);exit;
    }
}