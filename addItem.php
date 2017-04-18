<?php

include_once("core.php");

if (empty($_REQUEST['item']) && empty($_REQUEST["itemName"])) header("Location:index.php");

$qty = 1;

if (isset($_REQUEST["itemId"])) $itemId = (int)validator::testInput($_REQUEST["itemId"]);

$grDbAccess = new grDbAccess();

$grListId = 1;

if (isset($_REQUEST["item"])) {
	$newItem = validator::testInput($_REQUEST["item"]);
	$itemArray = [ "item" => $newItem];
	/**var_dump($itemArray);
	exit;*/
  $items = $grDbAccess->setItem($itemArray);
}

if (isset($items) && is_array($items)) {
    include_once("header.php");
    $i = 0;
    foreach ($items as $item) {
        $name = $item['item'];
        $itemId = $item['itemId'];
        $url = "addItem.php?itemName=" . urlencode($name) . "&itemId=$itemId&qty=" . urlencode($qty);
        echo "</br><a href=$url>$name</a></br>";
        if (strtolower($name) == strtolower($newItem)) $i = 1;
    }
    if ($i == 0) echo "</br><a href=addItem.php?itemName=" . urlencode($newItem) . "&newItem=true>$newItem</a></br>";
} elseif(isset($items)) {
	$grDbAccess->addItemToList($grListId, $items, $qty);
	 header("Location:index.php?additem=true");
}

//if multiple items exist with item name then the user selects
//the url with the correct i item which sets "itemName" used below
if (!empty($_REQUEST["itemName"]) && empty($_REQUEST["newItem"])) {
	//replaced $_SESSION["grListId"] with $grListId
	$result = $grDbAccess->addItemToList($grListId ,$_REQUEST["itemId"], $qty);
	header("Location:index.php?additem=true");
}

if (!empty($_REQUEST["newItem"])) {
	$newItem = validator::testInput($_REQUEST["itemName"]);
	$itemArray = [ "item" => $newItem, "measure" => " "];
  $itemId = $grDbAccess->setItem($itemArray, 1);
  $result = $grDbAccess->addItemToList($grListId ,$itemId, $qty);
  var_dump($result);
	header("Location:index.php?additem=true");
}
?>