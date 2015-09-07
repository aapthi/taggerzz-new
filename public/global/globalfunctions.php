<?
	function uniqueCode(){
		return $result="1111";
	}
	
	function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; } 
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
	}
	
	function my_number_encrypt($data, $key, $base64_safe=true, $shrink=true) {
        if ($shrink) $data = base_convert($data, 10, 36);
        $data = @mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $data, MCRYPT_MODE_STREAM);
        if ($base64_safe) $data = str_replace('=', '', base64_encode($data));
        return $data;
	}

	function my_number_decrypt($data, $key, $base64_safe=true, $expand=true) {
			if ($base64_safe) $data = base64_decode($data.'==');
			$data = @mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $data, MCRYPT_MODE_STREAM);
			if ($expand) $data = base_convert($data, 36, 10);
			return $data;
	}
?>