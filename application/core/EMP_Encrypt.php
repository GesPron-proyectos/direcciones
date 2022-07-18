<?php
	class encrypt{
		protected $passwd = '';
		protected $method = '';
		protected $funciv = '';
		
		public function encrypt(){
			$this->funciv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
			$this->passwd = 'Cadena de encriptacion para GESPRON';
			$this->method = 'aes-256-cbc';
		}
		
		public function encriptar($cadena){
			return openssl_encrypt($cadena, $this->method, $this->passwd, false, $this->funciv);
		}
		
		public function desencriptar($cadena){
			$result = '';
			$result = openssl_decrypt($cadena, $this->method, $this->passwd, false, $this->funciv);
			return ($result) ? $result : $cadena;
		}
		
		public function desencriptar_obj($data){
			foreach($data as $k => $v){
				$data->$k = $this->desencriptar($v);
			}
			return $data;
		}
		
		public function desencriptar_arr($data){
			foreach($data as $k => $v){
				$data[$k] = $this->desencriptar($v);
			}
			return $data;
		}
		
		function getIV(){
			return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method)));
		}
	}