<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.13 ]
* Description [ 视频接口 ]
*/
namespace Zhibo\Controller;
class ApiController extends CommonController {

    public function getvedio()
    {
        if(IS_GET){
            $type = I('get.type');
            if(empty($type) || $type != 'vedio'){
                $data['vedio'] = "";
                $data['info'] = '参数错误';
                $data['status'] = 0;
            } else {
                $myjson = "";
                if (C('VIDEO_STATUS') == 1){
                    if(C('VIDEO_TYPE') == 2){
                        //$myjson .= '<script type="text/javascript" charset="utf-8" src="http://yuntv.letv.com/player/live/blive.js"></script>';
                    } elseif(C('VIDEO_TYPE') == 3){
                       // $myjson .= '<script type="text/javascript" src="http://cdn.aodianyun.com/lss/aodianplay/player.js"></script>';
                    } elseif(C('VIDEO_TYPE') == 4){
                       // $myjson .= '<script type="text/javascript" src="http://static.gensee.com/webcast/static/sdk/js/gssdk.js"></script>';
                    }
                }
                $myjson .= '<script type="text/javascript">$(function(){';
                $myjson .= getvideo();
                $myjson .= "})</script>";
                $data['vedio'] = $myjson;
                $data['info'] = '视频信息';
                $data['status'] = 1;
            }
            $this->ajaxReturn($data);
        }
    }
}