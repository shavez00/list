<?php

include_once("core.php");

//if (!isset($_SESSION['login_user'])) header("Location:index.php");

if (empty($_REQUEST['item']) && empty($_REQUEST["itemName"])) header("Location:index.php");

/*if (isset($_REQUEST['newGrName'])) {
  $grName = validator::testInput($_REQUEST["newGrName"]);
  header("Location:createList.php?grName=" . $grName);
  exit;
}*/
	
//$userId = (int)validator::testInput($_SESSION['login_user']['userId']);

$qty = validator::testInput($_REQUEST["qty"]);

if (isset($_REQUEST["itemId"])) $itemId = (int)validator::testInput($_REQUEST["itemId"]);

$grDbAccess = new grDbAccess();

$grListId = 1; //(int)$_SESSION["grListId"];

if (isset($_REQUEST["item"]))$items = $grDbAccess->setItem($_REQUEST);

if (isset($items) && is_array($items)) {
	include_once("header.php");
  foreach ($items as $item) {
	       $name = $item['item'];
	       $itemId = $item['itemId'];
	       $url = "addItem.php?itemName=" . urlencode($name) . "&itemId=$itemId&qty=" . urlencode($qty);
		      echo "</br><a href=$url>$name</a></br>";
        }
} elseif(isset($items)) {
	$grDbAccess->addItemToList($grListId, $items, $qty);
	 header("Location:index.php?additem=true");
}

//if multiple items exist with item name then the user selects
//the url with the correct i item which sets "itemName" used below
if (!empty($_REQUEST["itemName"])) {
	//replaced $_SESSION["grListId"] with $grListId
	$result = $grDbAccess->addItemToList($grListId ,$_REQUEST["itemId"], $qty);
	header("Location:index.php?additem=true");
}
?>