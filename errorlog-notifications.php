<?php

	$emailTo = 'your@email.com';
	$replyTo = 'noreply@domain.com';
	$searchPath = '/home/user/public_html';
	$errorlog = 'error_log';

	function rglob($pattern, $flags = 0, $path = ''){
	    if(!$path && ($dir = dirname($pattern)) != '.'):
	        if($dir == '\\' || $dir == '/'):
				$dir = '';
	        endif;
			return rglob(basename($pattern), $flags, $dir . '/');
	    endif;

		$paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
	    $files = glob($path . $pattern, $flags);

		foreach ($paths as $p):
			$files = array_merge($files, rglob($pattern, $flags, $p . '/'));
	    endforeach;
		return $files;
	}

	$rglob = rglob($errorlog, GLOB_MARK, $searchPath);

	foreach($rglob as $grr):
		$x .= filesize($grr).': '.$grr."\n";
	endforeach;
	
	
	if(!empty($x)):
		$address = $emailTo;
		$e_subject = 'Errors on Server';
		$e_body = $x." \r\n\n";
		$e_content = "\r\n\n";
		$e_reply = "";
		$headers = "From: Error Log Checker <".$replyTo."> \r\nReply-To: ".$replyTo." \r\nReturn-Path: ".$replyTo." \r\n";
		$msg = $e_body . $e_content . $e_reply;
		mail($address, $e_subject, $msg, $headers);
	endif;

?>
