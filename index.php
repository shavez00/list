<?php

include_once('core.php');
include('header.php');

$grDbAccess = new grDbAccess();

$grListId = 1;
$grName = "Shavers List";

$items = $grDbAccess->getGrListItems($grListId);
$count = 0;

//var_dump($items);

//exit;

echo '<div class="container"><div class="row"><div class="one-half column" style="margin-top: 0%"><h2>Items on grocery list - ' . $grName . '</h2></br><table>';

if (empty($_REQUEST["item"])) {
echo <<<EOT
  
  <div class="container"><div class="row"><div class="one-half column" style="margin-top: 0%">
  <form action="addItem.php" method="post">
    <h2>Add item to list: </h2></br>
    <h5>item name: <input type="text" name="item"></input></br>
   <!---- quantity of items: <input type="number" name="qty"></input></br>
    measure: <input type="text" name="measure"></input></br> ------>
    </h5>
    <input type="submit" value="Add"></input>
  </form>
  </div></div></div>

EOT;
}

foreach ($items as $item) {
    $count = $count + 1;
    //var_dump($item);
    //exit;
    $itemDesc = $grDbAccess->getItem($item["itemId"]);
    echo '<tr><td><h6>' . $itemDesc["item"] . '</h6></td><td><h6> ' . /* $item["qty"] . ' ' . $itemDesc["measure"] .*/ '</h6></td><td> <h6><a href=removeItem.php?itemId=' . $itemDesc["itemId"] . '> remove</a></h6></td>';
}

echo '</table></div></div></div>';