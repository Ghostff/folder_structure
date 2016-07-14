<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require 'DirStruct.php';

$dir = new DirStruct;
echo $dir->structure('Folder');

?>