<?php
require_once('JSONArchiveApi.php');
$pathName = '193204.json';
$resultArchive = '';
try {
	$testContents = JSONArchiveApi::readContentsJSON($pathName);
	JSONArchiveApi::checkStructure();
	$resultArchive .= "193204.json: read, structure good";
}
catch (Exception $e)
{
	$resultArchive .= $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<title>TestAAO1 Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--<link rel="stylesheet" href=".css">-->
<body>
<p>Welcome to the Test of JSON Archive API</p>
<p>.... resultArchive:<?php echo $resultArchive; ?></p>
</body>
</html>
