<?php

class Portal{

	private static $API_SECRET = 'CH5RQ7kOvPMwkNnPwEkQoQtNe7wjyisoJxNy0lt2oy7cFEcXJPWbghbr6mcrDxg8qmgAGODUGQCxTpvAZcDLPVzMimfJU6q1wyjA';

	function requestApi($url, $data = array()){


   	 		$curl = curl_init();

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
				'secret' => self::$API_SECRET,
				'data' => $data)));
			curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 120);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);


			// Send the request
			$result = curl_exec($curl);

			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$body = substr($result, $header_size);
			
            curl_close($curl);

			return $body;
	}

	public function validate_ip($ip){
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false){
            return false;
        }
        return true;
    }

    public function get_ip_address() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach($ip_keys as $key){
            if(array_key_exists($key, $_SERVER) === true){
                foreach(explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip);
                    if($this->validate_ip($ip)){
                        return $ip;
                    }
                }
            }
        }
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
    }
}
