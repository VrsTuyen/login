<?php
class MD5
{
  private $string;

  public function setString($string)
  {
    $this->string = $string;
  }
  public function encrypt()
  {
    $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
    $strEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $this->string, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
    return $strEncoded;

  }
  function decrypt($string)
  {
    $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
    $strDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
    return ($strDecoded);
  }
}
?>