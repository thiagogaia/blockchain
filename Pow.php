<?php

/**
 *  prova de trampo
 */
class Pow
{
	
	public static function hash($msg)
	{
		return hash('sha256', $msg);
	}

	public static function isValid($msg, $nonce) {
		$zeros = '00000';
		return strpos(hash('sha256', $msg.$nonce), $zeros) === 0;
	}

	public static function nonce($msg) {
		$nonce = 0;
		$zeros = '0000';
		while(strpos(hash('sha256', $msg . $nonce), $zeros) !== 0) {
			$nonce++;
		}
		return $nonce;
	}
}

/*$nonce = '82908';
$msg = 'fala comigo bb'.$nonce;
print Pow::hash($msg);*/
$msg = 'Mensagem criptografada48270';
print hash('sha256', $msg);
// print Pow::nonce($msg);