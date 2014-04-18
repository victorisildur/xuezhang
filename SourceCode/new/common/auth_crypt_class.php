<?php
class Crypt
{
	private $key;
	
    function __construct()
	{
		if(defined("AUTH_KEY")) $this->key = $this->hexToStr(AUTH_KEY);
		else die('AUTH_KEY is undefined!');
	}
	
	function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
	}
	
	function hexToStr($hex){
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}

    /* Function tripleDESEncrypt use 3DES to encrypt a string
    string is encoded to UTF8 first, encypted, then encoded to base64 if encodedForUrl is true
    $src: string, source string to be encrpyted with 3DES
    $key: string in bytes form, 3DES key
    $encodedForUrl: boolean, if src is URL, set $encodedForUrl to true */
    function tripleDESEncrypt($src, $encodedForUrl)
    {
        if ($src == NULL) return NULL;

        $srcb =iconv("EUC-CN", "UTF-8", $src);
        $srcb= $this->PKCS5Padding($srcb);
        /* Encrypt data */
        $encryptedData = $this->tripleDESEncrypt1($srcb,$this->key);
        if ($encryptedData != NULL) {
            $encryptedData=base64_encode($encryptedData);
            //echo "base64 encoded:" . strlen($encryptedData)."|".bin2hex($encryptedData)."<br>\n";
            if ($encodedForUrl) {
                $encryptedData =urlencode($encryptedData);
            }
        }
        return $encryptedData;
    }

    /* Function tripleDESEncrypt1 use 3DES to encrypt a string
    $src: string, source string to be encrpyted with 3DES
    $key: string in bytes form, 3DES key  */
    function tripleDESEncrypt1($src)
    {
        if ($src == NULL) return NULL;
        $iv =$this->my_mcrypt_create_iv();
        /* Open module, and create IV */
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
        if ($this->key == NULL) $this->key="";
        $key = substr($this->key, 0, mcrypt_enc_get_key_size($td));

        /* Initialize encryption handle */
        if (mcrypt_generic_init($td, $key, $iv) != -1) {
            /* Encrypt data */
            $encryptedData = mcrypt_generic($td, $src);
            $iv_size=mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
            $encryptedData=chr($iv_size).$iv. $encryptedData;
            mcrypt_generic_deinit($td);
            //echo "src is:" . $src. "<br>\n";
            //echo "iv is :" .bin2hex($iv)."<br>\n";
            //echo "key is :" .bin2hex($key)."<br>\n";
        }else {
            $encryptedData=null ;
        }
        mcrypt_module_close($td);
        return $encryptedData;
    }


    function tripleDESDecrypt($src)
    {
        if ($src == NULL) return NULL;
        $src=base64_decode($src);
        $decryptedData = $this->tripleDESDecrypt1($src, $this->key);
        if ($decryptedData != NULL) {
            $decryptedData = iconv("UTF-8", "EUC-CN", $decryptedData);
        }
        return $decryptedData;
    }

    function tripleDESDecrypt1($src)
    {
        if ($src == NULL) return NULL;
        /* Open module, and create IV */
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
        if ($this->key == NULL) return NULL;
        $iv_size=ord($src);
        $iv=substr($src,1,$iv_size);
        $key = substr($this->key, 0, mcrypt_enc_get_key_size($td));

        /* Initialize encryption handle */
        if (mcrypt_generic_init($td, $key, $iv) != -1) {
            /* Encrypt data */
            $srcb=substr($src,$iv_size+1);
            $decryptedData = mdecrypt_generic($td, $srcb);
            mcrypt_generic_deinit($td);
            $decryptedData =$this->PKCS5UnPadding($decryptedData);
        }else {
            $decryptedData=null ;
        }
        mcrypt_module_close($td);

        return $decryptedData;
    }

    function hex2bin($HexStr) {
        return pack('H*', $HexStr);
    }

    /* Windows mycrypt_create _iv has bug, it always return the same value, so
    I create my own */
    function my_mcrypt_create_iv(){
        $size=mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $iv=md5("me".rand().rand().rand());
        $iv=substr($this->hex2bin($iv),0,$size);
        return $iv;
    }

    function PKCS5Padding($Str,$Size=8){
        $strLen=strlen($Str);
        $n=fmod($strLen,$Size);
        $n=$Size-$n;
        return ($Str . str_repeat(chr($n),$n));
    }

    function PKCS5UnPadding($Str,$Size=8){
        $l=strlen($Str);
        $n=ord(substr($Str,-1,1));
        if ($n>$Size) return $Str;
        return substr($Str,0,$l-$n);
    }
}
?>