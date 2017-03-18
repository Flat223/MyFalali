<?php
class CheckVerifycodeServ{
    public function checkverify($mobile,$mbverifycode,$usage){
        $url = "http://www.ichuk.com/?api/checksmsverifycode/e75ce5d42105d8e581327164f8e860/1";
        $checkpost['stage'] = "实验圈";
        $checkpost['mobile'] = $mobile;
        $checkpost['platform'] = 'WEB';
        $checkpost['usage'] = $usage;
        $checkpost['code'] = $mbverifycode;
        $chcekResult = $this->REQUEST_POST($url,$checkpost);
        $chcekResult= json_decode($chcekResult,true);
        return $chcekResult;
    }

    public function REQUEST_POST($url='', $postdata='', $options=array()){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        if (!empty($options)){
            curl_setopt_array($ch, $options);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }



}
?>