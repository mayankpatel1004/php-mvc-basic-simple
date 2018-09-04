<?php
$base_path = dirname(__FILE__);
$site_url = "http://localhost/Dropbox/testing/mayank/";
define('SITE_URL',$site_url);
define('SITE_PATH',$base_path);
define('UPLOAD_URL',SITE_URL."/upload/");
define('CONTACT_UPLOAD_URL',UPLOAD_URL."/contact/");
define('UPLOAD',SITE_PATH."/upload/");
define('CONTACT_UPLOAD',UPLOAD."/contact/");
define('TEMP_UPLOAD',UPLOAD."/temp/");
