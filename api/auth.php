<?php if (!defined ('SYSTEM_ROOT')) exit (); ?>
<?php
	function auth_register ()
	{
		// 获取
		$url = API_URL . '/index.php?mod=register';
		$data = array (
			'url' => system_geturl ()
		);
		$ret = json_decode (system_fetch ($url, $data), true);
		
		// 分析
		if ($ret['code'] == 0) {
			option_update ('api_skey', $ret['skey']);
		} else {
			die ('请求skey失败，原因：' . $ret['msg']);
		}
	}

	function auth_getskey ()
	{
		return option_getvalue ('api_skey');
	}

	function auth_getsign ($data)
	{
		// 初始化变量
		$key = file_get_contents (SYSTEM_ROOT . '/public.pem');

		// 签名变量
		$data .= '|-_-|' . auth_getskey () . '|-_-|' . time ();
		$data .= '|-_-|' . md5 ($data);

		// RSA加密
		openssl_public_encrypt ($data, $sdata, $key);

		// 返回
		return base64_encode ($sdata);
	}
?>