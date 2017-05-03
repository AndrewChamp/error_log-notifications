<?php

	class errorlog{

		private $email = 'me@andrewchamp.com';
		private $reply = 'noreply@honeysheastudios.com';
		private $subject = 'HoneyShea Studios Error Log Alert';
		private $path = '/home/honeysheastudios/public_html';
		private $errorlog = 'error_log';
	
	
		public function __construct(){
			$this->logs();
		}
		
		
		private function rglob($pattern, $flags=0, $path=''){
			if(!$path && ($dir = dirname($pattern)) != '.'):
				if($dir == '\\' || $dir == '/'):
					$dir = '';
				endif;
				return $this->rglob(basename($pattern), $flags, $dir.'/');
			endif;

			$paths = glob($path.'*', GLOB_ONLYDIR | GLOB_NOSORT);
			$files = glob($path.$pattern, $flags);

			foreach ($paths as $path):
				$files = array_merge($files, $this->rglob($pattern, $flags, $path.'/'));
			endforeach;
			return $files;
		}
		
		
		/*
		private function rglob($pattern, $flags=0, $path){
			$files = glob($pattern, $flags); 
			foreach(glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir):
				$files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
			endforeach;
			
			return $files;
		}
		*/
		

		private function logs(){
			$rglob = $this->rglob($this->errorlog, GLOB_MARK, $this->path);

			foreach($rglob as $grr):
				$x .= filesize($grr).': '.$grr."\n";
			endforeach;

			if(!empty($x)):
				$msg = $x." \r\n\n";
				$headers = "From: ".$this->subject." <".$this->reply.">\r\n Reply-To: ".$this->reply."\r\n Return-Path: ".$this->reply."\r\n MIME-Version: 1.0\r\n Content-type: text/html; charset=UTF-8\r\n";
				mail($this->email, $this->subject, $msg, $headers);
			endif;
		}
	
	}
	
	
	#$errorlog = new errorlog();
	
	

	function rglob($pattern, $flags = 0, $path=''){
	    if(!$path && ($dir = dirname($pattern)) != '.'):
	        if($dir == '\\' || $dir == '/'):
				$dir = '';
	        endif;
			return rglob(basename($pattern), $flags, $dir.'/');
	    endif;

		$paths = glob($path.'*', GLOB_ONLYDIR | GLOB_NOSORT);
	    $files = glob($path.$pattern, $flags);

		foreach ($paths as $p):
			$files = array_merge($files, rglob($pattern, $flags, $p . '/'));
	    endforeach;
		return $files;
	}

	$rglob = rglob('error_log', GLOB_MARK, '/home/honeysheastudios/public_html');

	foreach($rglob as $grr):
		if(filesize($grr) > 0):
			$x .= filesize($grr).': '.$grr."\n";
		endif;
	endforeach;

	if(!empty($x)):
		$msg = $x."\r\n\n";
		$headers = "From: HoneyShea Studios Error Log Alert <noreply@honeysheastudios.com> \r\nReply-To: noreply@honeysheastudios.com \r\nReturn-Path: noreply@honeysheastudios.com \r\n";
		mail('andrew@honeysheastudios.com', 'HoneyShea Studios Error Log Alert', $msg, $headers);
	endif;

	

?>
