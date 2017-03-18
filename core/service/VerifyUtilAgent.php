<?php

/**
 * 获取验证码
 * Class VerifyUtilAgent
 */
class VerifyUtilAgent {
	public function __construct() {

	}

	public function getVerify($type = 1, $length = 4, $pixel = 20, $line = 5, $sess_name = "verify") {
		if (!isset($_SESSION)) {
			session_start();
		}
		$width = 80;
		$height = 30;
		date_default_timezone_set("PRC");
		$image = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($image, 255, 255, 255);
		$black = imagecolorallocate($image, 0, 0, 0);
		imagefilledrectangle($image, 1, 1, $width - 2, $height - 2, $white);
		$strUtil = new StringUtil();
		$chars = $strUtil->getRandomString($type, $length);
		$_SESSION[$sess_name] = $chars;
		$fontFiles = '/css/public/fonts/arial.ttf';
		for ($i = 0; $i <= $length; $i++) {
//			$size = mt_rand(14, 18);
			$size = 18;
			$angle = mt_rand(-15, 15);
			$x = 5 + $i * $size;
			$y = mt_rand(20, 26);
			$color = imagecolorallocate($image, mt_rand(0, 90), mt_rand(0, 200), mt_rand(0, 180));
			$text = mb_substr($chars, $i, 1, 'utf-8');
			imagettftext($image, $size, $angle, $x, $y, $color, $fontFiles, $text);
		}
		if ($pixel) {
			for ($i = 0; $i <= $pixel; $i++) {
				imagesetpixel($image, mt_rand(0, $width - 1), mt_rand(0, $height - 1), $black);
			}
		}
		if ($line) {
			for ($i = 0; $i <= $line; $i++) {
				$color = imagecolorallocate($image, mt_rand(50, 90), mt_rand(80, 200), mt_rand(90, 180));
				imageline($image, mt_rand(0, $width - 1), mt_rand(0, $height - 1), mt_rand(0, $width - 1), mt_rand(0, $height - 1), $color);
			}
		}
		header('content-type:images/gif');
		imagegif($image);
		imagedestroy($image);
	}
}