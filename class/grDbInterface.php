<?php

interface grDbInterface {
  public function getGrListId($userId, $name);
  public function setGrListId($userId, $name);
  public function getGrListItems($grListId);
  public function getItem($itemId);
  public function setItem(array $item);
  public function addItemToList($grListId, $itemId, $qty=NULL);
  public function removeItemFromList($grListId, $itemId);
  public function getUserId($email);
  public function shareGrList($grListId, $userId, $sharedWithId);
  public function getShareGrListId($userId);
}

?>