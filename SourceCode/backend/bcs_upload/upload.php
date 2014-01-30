

<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="text" name="filename" /> 
<input type="file" name="file"/> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>



<?php

if(empty($_POST['submit'])) die();

require_once 'bcs.class.php';

$baidu_bcs = new BaiduBCS();

//create_object_by_content($bucket, $object, $content, $opt = NULL)
//$object:以"/"开头的object名，不超过255字节
$bucket = 'xuezhang';
$object = '/'.trim($_POST['filename']);
$fileUpload = $_FILES['file']["tmp_name"];
$opt = array ();
$opt ['acl'] = "public-read";
$response = $baidu_bcs->create_object ( $bucket, $object, $fileUpload );
printResponse ( $response );


function printResponse($response) {
	if($response->isOK ()) echo 'Image Url: http://bcs.duapp.com/xuezhang/'.$object;
	else echo 'Failed';
}
?>

</body>
</html>