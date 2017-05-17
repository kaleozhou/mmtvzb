<?php
/*
* Author: [ Copy Lian ]
* Date: [ 2015.05.08 ]
* Description [ 视频设置 ]
*/
namespace Zzcfadmin\Controller;

class VideosetController extends CommonController
{

	/**
	 * [index 视频设置]
	 */
	function index(){
		if(IS_POST){
	        $Videoset = D('Videoset');
	        if($data = $Videoset->create()){
	        	
	        	//key重新索引解决冲突
				$Videoset->thumb = array_values($Videoset->thumb);

				//设置图片地址
				$pic_arr = $Videoset->thumb;

				$Videoset->thumb = serialize($Videoset->thumb);

				//保存
	            if($Videoset->save()){
			        //在本地写入邮件配置文件
			        $content = "<?php\n";
			        $content .= "/*\n";
			        $content .= "*Author:CopyLian\n";
			        $content .= "*Date:".date("Y-m-d H:i:s")."\n";
			        $content .= "*/\n";
			        $content .= "return array(\n";
			        $content .= "\t'VIDEO_ROOMURL'=>'".$data['roomurl']."', // 直播房间号\n";
			        $content .= "\t'VIDEO_TYPE'=>'".$data['type']."', // 直播类型：1-YY直播，2-乐视云直播，3-奥点云直播，4-GenSee直播\n";
			        $content .= "\t'VIDEO_THUMB_PIC'=>'".__ROOT__.$pic_arr[0]['thumb']."', // 视频默认缩略图片地址\n";
			        $content .= "\t'VIDEO_PHOTO_PIC'=>'".__ROOT__.$pic_arr[0]['photo']."', // 视频默认原始图片地址\n";
			        $content .= "\t'VIDEO_STATUS'=>".$data['status'].", // 状态\n";
			        $content .= ");\n";
			        $content .= "?>";
			        $ok = file_put_contents(CONF_PATH.'video.php', $content);
			        if($ok){
			        	$this->success(L('_SAVE_SUCCESS_'));
			        } else {
			        	$this->error(L('_SAVE_ERROR_'));
			        }
	            } else {
	                  $this->error(L('_SAVE_ERROR_'));
	            }
	        } else {
	            $this->error($Videoset->getError());
	        }
	    } else {

	        //获取数据
	        $Videoset = M('Videoset');
	        $data = $Videoset->find();
	        //处理图片解析
			if(!empty($data)){
				$data['thumb'] = unserialize($data['thumb']);
			}
			$this->data = $data;
	        $this->display();
	    }
	}
}
?>