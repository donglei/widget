<?php

class WeixinController extends \BaseController {
	
	/**
	 * 验证URL有效性
	 * @param int $id  useraccount id
	 */
	public function getCallback($id)
	{
		$postdata = file_get_contents("php://input");
		Log::info('url::getCallback', [Input::all(), 'id' => $id, 'postdata' => $postdata]);
		//验证URL有效性
		if (Input::has('echostr')) {
			if ($this->checkSignature($id)) {
				return Input::get('echostr');
			}
			else {
				return 'not match';
			}
		}
		return 'not match';
	}
	
	/**
	 * 微信回调
	 * @param unknown $id
	 * @return string
	 */
	public function postCallback($id)
	{
		$postdata = file_get_contents("php://input");
		/*$postdata = "<xml><ToUserName><![CDATA[gh_0aff060c6c70]]></ToUserName>
<FromUserName><![CDATA[oO62GuLyHkHIv2Rh5EHOA6rsoRtM]]></FromUserName>
<CreateTime>1403689705</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[]]></EventKey>
</xml>";*/
		Log::info('url::postCallback::request', [Input::all(), 'id' => $id, 'postdata' => $postdata]);
		//验证URL有效性
		if (false && !$this->checkSignature($id)) {
			Log::info('url::postCallback::checkSignature', ['checkSignature failed', $id]);
			return 'failed';
		}
		
		$postObj = simplexml_load_string($postdata, 'SimpleXMLElement', LIBXML_NOCDATA);
		$msgType = $postObj->MsgType;
		//通过MessageType 分发处理微信信息内容
		$weixinRepository = new WeixinRepository($id, $postObj);
		
		//设置参数
		//$weixinRepository->id = $id;
		//$weixinRepository->postXmlObj  =  $postObj;
		
		$msgType = strtolower($msgType);
		//处理各种小心内容
		if(in_array($msgType, get_class_methods($weixinRepository)))
		{
			$response =  $weixinRepository->{$msgType}();
			Log::info('url::postCallback::response', ['id' => $id, 'response' => $response]);
			return $response;
		}
		return "";
	}
	/**
	 * 验证URL有效性
	 * @param int $id
	 * @return boolean
	 */
	private function checkSignature($id)
	{
		$signature = Input::get("signature");
		$timestamp = Input::get("timestamp");
		$nonce = Input::get("nonce");
		$account = Useraccount::find($id);
		
		if (empty($account)) {
			return false;
		}
		
		$token = $account['token'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
	
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
}
