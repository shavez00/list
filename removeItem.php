<?php
include_once("core.php");

//if (!isset($_SESSION['login_user'])) header("Location:index.php");

$itemId = (int)validator::testInput($_REQUEST['itemId']);
//$grListId = (int)validator::testInput($_SESSION['grListId']);

$grDbAccess = new grDbAccess();

$grListId = 1;
$result = $grDbAccess->removeItemFromList($grListId, $itemId);

header("Location:index.php?remove=true");

?>