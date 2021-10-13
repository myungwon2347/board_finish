<?php	
namespace util; 

use \Firebase\JWT\JWT;

class JWT_S
{
	// 문자열 반환
	public static function encode($value)
	{
		$key = $GLOBALS['SITE']['SECURITY_KEY'];

		$token = array(
		    "iss" => "create.fitsoft",
		    "aud" => "create.fitsoft",
		    "iat" => 1356999524,
			"nbf" => 1357000000,
			"value" => $value
		);
        // $key = strrev($GLOBALS['SITE']['SECURITY_KEY']) . $key . $GLOBALS['SITE']['SECURITY_KEY'];

		return JWT::encode($token, $key);
	}

	// 배열 반환
	public static function decode($jwt)
	{
		$key = $GLOBALS['SITE']['SECURITY_KEY'];

		// JWT::$leeway = 60; // $leeway in seconds
		$result = JWT::decode($jwt, $key, array('HS256'));
		// return json_decode(json_encode($result), true);
		return (json_decode(json_encode($result), true))['value'];
	}

	/*use \Firebase\JWT\JWT;

	$privateKey = <<<EOD
	-----BEGIN RSA PRIVATE KEY-----
	MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
	vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
	5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
	AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
	bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
	Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
	cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
	5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
	ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
	k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
	qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
	eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
	B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=
	-----END RSA PRIVATE KEY-----
	EOD;

	$publicKey = <<<EOD
	-----BEGIN PUBLIC KEY-----
	MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC8kGa1pSjbSYZVebtTRBLxBz5H
	4i2p/llLCrEeQhta5kaQu/RnvuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t
	0tyazyZ8JXw+KgXTxldMPEL95+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4
	ehde/zUxo6UvS7UrBQIDAQAB
	-----END PUBLIC KEY-----
	EOD;

	$token = array(
	    "iss" => "example.org",
	    "aud" => "example.com",
	    "iat" => 1356999524,
	    "nbf" => 1357000000
	);

	$jwt = JWT::encode($token, $privateKey, 'RS256');
	echo "Encode:\n" . print_r($jwt, true) . "\n";

	$decoded = JWT::decode($jwt, $publicKey, array('RS256'));

	/*
	 NOTE: This will now be an object instead of an associative array. To get
	 an associative array, you will need to cast it as such:

	$decoded_array = (array) $decoded;
	echo "Decode:\n" . print_r($decoded_array, true) . "\n";*/
}