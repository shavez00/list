<?php

include_once('core.php');
include('header.php');
	
//if (!isset($_SESSION['login_user'])) header("Location:index.php");

//as page is loaded, if grName and grList is set then load those into Session varible
//if (isset($_REQUEST['grName'])) $_SESSION["grName"] = validator::testInput($_REQUEST["grName"]);
//if (isset($_REQUEST['grList'])) $_SESSION["grList"] = validator::testInput($_REQUEST["grList"]);

//$userId = (int)validator::testInput($_SESSION['login_user']['userId']);

$grDbAccess = new grDbAccess();
//$grListId = $grDbAccess->getGrListId($userId);
//$shareListId = getShareListId($userId);

//foreach loop to generate list of available grocery lists as hyperlinks
//only executed is $_REQUEST is empty and $_SESSION is set
/**if(empty($_REQUEST)) {
  if (isset($_SESSION)) {
  	if(isset($_SESSION['login_user'])) { 
	  	if(!empty($grListId)) {
			  var_dump($grListId);
			echo "</br>";
		  	echo '<div class="container"><div class="row"><div class="one-half column" style="margin-top: 0%"><h4>Which Grocery List would you like to use?</h4>';
			  echo '<table>';
        foreach ($grListId as $grList) {
	       $name = $grList['grName'];
	       $listId = $grList['grListId'];
	       //$name = htmlencode($name);
	       $url = "grocerylist.php?grName=$name&grListId=$listId";
		      echo "<tr><p><td><a href=$url>$name</a></td>";
		      echo "<td><a href=shareList.php?grListId=$listId>share list</a></td></p></tr>";
        } 
        echo '</table></div></div></div><a href="createList.php">Create new list</a>';
      }  else {
	      echo '<a href="createList.php">Create new list</a>';
	    }
    }
  }
  exit;
}*/

//$grListId = NULL;
/**
if (isset($_REQUEST['grListId'])) {
  $grListId = (int)validator::testInput($_REQUEST['grListId']);
  //$_SESSION["grListId"] = $grListId;
  $grName = validator::testInput($_SESSION['grName']);
  //$_SESSION["grName"] = $grName;
} else {
  $grListId = (int)validator::testInput($_SESSION['grListId']);
  $grName = validator::testInput($_SESSION['grName']);
}
*/
$grListId = 1;
$grName = "Shavers List";

$items = $grDbAccess->getGrListItems($grListId);
$count = 0;

echo '<div class="container"><div class="row"><div class="one-half column" style="margin-top: 0%"><h2>Items on grocery list - ' . $grName . '</h2></br><table>';

foreach ($items as $item) {
	$count = $count + 1;
  $itemDesc = $grDbAccess->getItem($item["itemId"]);
  echo '<tr><td><h6>' . $itemDesc["item"] . '</h6></td><td><h6> ' . /* $item["qty"] . ' ' . $itemDesc["measure"] .*/ '</h6></td><td> <h6><a href=removeItem.php?itemId=' . $itemDesc["itemId"] . '> remove</a></h6></td>';
}

echo '</table></div></div></div>';

if (empty($_REQUEST["item"])) {
echo <<<EOT
  
  <div class="container"><div class="row"><div class="one-half column" style="margin-top: 0%">
  <form action="addItem.php" method="post">
    <h2>Add item to list: </h2></br>
    <h5>item name: <input type="text" name="item"></input></br>
   <!---- quantity of items: <input type="number" name="qty"></input></br>
    measure: <input type="text" name="measure"></input></br> ------>
    <br></br></h5>
    <input type="submit" value="Add"></input>
  </form>
  </div></div></div>

EOT;
}