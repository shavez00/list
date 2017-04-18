<?php
class grDbAccess implements grDbInterface {
	private $con = NULL;
	
	public function __construct() {
	    try {
	      //create our pdo object
        $this->con = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        //set how pdo will handle errors
        $this->con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      } catch (PDOException $e) {
        echo "Error creating PDO connection, at line " . __LINE__. " in file " . __FILE__ . "</br>";
        echo $e->getMessage() . "</br>";
        exit;
      }
  }

  public function getGrListId($userId, $grName = NULL) {
	  if ($userId!==NULL) try {
            //this would be our query.
            $sql = "SELECT * FROM groceryList WHERE userId = :userId";
            if ($grName!==NULL) $sql = $sql . " AND grName = :grName";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "userId", $userId, PDO::PARAM_INT );
            if ($grName!==NULL) $stmt->bindValue( "grName", $grName, PDO::PARAM_STR );
            $stmt->execute();
            if ($grName==Null) {
              $grList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
	            $grList = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return $grList;
        } catch (PDOException $e) {
          echo "Error with getGrListId method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }
        throw new Exception("No userId or Name given in getGrListId method!");
  }

  public function setGrListId($userId, $grName = NULL) {
	  if (empty($this->getGrListId($userId, $grName))) try {
		  $sql = "INSERT INTO groceryList(userId, grName) VALUES(:userId, :grName)";
            
       $stmt = $this->con->prepare( $sql );
       //$stmt->bindValue( "username", $this->username, PDO::PARAM_STR );
       $stmt->bindValue( "userId", $userId, PDO::PARAM_INT );
       $stmt->bindValue( "grName", $grName, PDO::PARAM_STR );
       $stmt->execute();
       $result = $this->con->lastInsertId();
       return $result;
      } catch( PDOException $e ) {
	      echo "Error in the setGrListId method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
        echo $e->getMessage() . "</br>";
        exit;
      }
  return $this->getGrListId($userId, $grName);
  }
  
  public function getGrListItems($grListId) {
	  try {
		        $sql=NULL;
		        $stmt=NULL;
            //this would be our query.
            $sql = "SELECT * FROM grListANDItemsIntersection WHERE grListId = :grListId";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "grListId", $grListId, PDO::PARAM_STR );
            $stmt->execute();
            $grListItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $grListItems;
        } catch (PDOException $e) {
          echo "Error with getGrListItems method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }
  }

  public function setItem(array $itemArray, $flag=NULL) {
	  if ($itemArray["item"] == NULL) throw new Exception("Item name needs to be set!");
	  if ($flag!==NULL) try {
		        $sql = "INSERT INTO items(item, measure) VALUES(:item, :measure)";
            
            $stmt = $this->con->prepare( $sql );
            $stmt->bindValue( "item", $itemArray["item"], PDO::PARAM_STR );
            $stmt->bindValue( "measure", $itemArray["measure"], PDO::PARAM_STR );
            $stmt->execute();
            $result = $this->con->lastInsertId();
            return $result;
          } catch( PDOException $e ) {
	          echo "Error in the setItem method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
            echo $e->getMessage() . "</br>";
            exit;
          }
    try {
            //this would be our query.
            $sql = "SELECT * FROM items WHERE item LIKE :item";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "item", "%" . $itemArray["item"] . "%", PDO::PARAM_STR );
            $stmt->execute();
            $existingItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          echo "Error with getGrListId method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }
        if (!empty($existingItem)) {
	        return $existingItem;
	      } else {
		      try {
		        $sql = "INSERT INTO items(item, measure) VALUES(:item, :measure)";
            
            $stmt = $this->con->prepare( $sql );
            $stmt->bindValue( "item", $itemArray["item"], PDO::PARAM_STR );
            $stmt->bindValue( "measure", $itemArray["measure"], PDO::PARAM_STR );
            $stmt->execute();
            $result = $this->con->lastInsertId();
            return $result;
          } catch( PDOException $e ) {
	          echo "Error in the setItem method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
            echo $e->getMessage() . "</br>";
            exit;
          }
	      }
  }

  public function addItemToList($grListId, $itemId, $qty=NULL) {
	   try {
		        //query to check if item is already on list
 
            $sql = "SELECT * FROM  grListANDItemsIntersection WHERE grListId = :grListId";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter
            $stmt->bindValue( "grListId", $grListId, PDO::PARAM_STR );
            $stmt->execute();
            $itemArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          echo "Error with addItemToList method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }
		    if (!empty($itemArray)) foreach ($itemArray as $existingItemId) {
			    if ($existingItemId["itemId"]==$itemId) return FALSE;
		    }
    try {
            $sql = "INSERT INTO grListANDItemsIntersection(grListId, itemId, qty) VALUES(:grListId, :itemId, :qty)";
            
            $stmt = $this->con->prepare( $sql );
            $stmt->bindValue( "grListId", $grListId, PDO::PARAM_INT );
            $stmt->bindValue( "itemId", $itemId, PDO::PARAM_INT );
            $stmt->bindValue( "qty", $qty, PDO::PARAM_INT );
            $stmt->execute();
            $result = $this->con->lastInsertId();
            return $result;
          } catch( PDOException $e ) {
	          echo "Error in the addItemToList method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
            echo $e->getMessage() . "</br>";
            exit;
          }
  }

  public function removeItemFromList($grListId, $itemId) {
	   try {
            //this would be our query.
            $sql = "SELECT * FROM  grListANDItemsIntersection WHERE grListId = :grListId";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "grListId", $grListId, PDO::PARAM_STR );
            $stmt->execute();
            $itemArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          echo "Error with removeItemFromList method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }

		    if (!empty($itemArray)) {
			    foreach ($itemArray as $item) {
            if(in_array($itemId, $item)) $result = TRUE;
          }
		    }
		if ($result) {
      try {
            $sql = "DELETE FROM grListANDItemsIntersection WHERE grListId = :grListId AND itemId = :itemId";
            
            $stmt = $this->con->prepare( $sql );
            $stmt->bindValue( "grListId", $grListId, PDO::PARAM_INT );
            $stmt->bindValue( "itemId", $itemId, PDO::PARAM_INT );
            $stmt->execute();
            return TRUE;
          } catch( PDOException $e ) {
	          echo "Error in the removeItemFromListItem method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
            echo $e->getMessage() . "</br>";
            exit;
          }
      }
      return FALSE;
  }

  public function getItem($itemId) {
	  if ($itemId == NULL) throw new Exception("ItemId needs to be set! for getItem method");
    try {
            //this would be our query.
            $sql = "SELECT * FROM items WHERE itemId = :itemId";
            //if (isset($itemArray["measure"])) if ($itemArray["measure"] != NULL) $sql = $sql . " AND measure = :measure";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "itemId", $itemId, PDO::PARAM_INT );
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          echo "Error with getItem method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }
	  return $item;
  }

  public function getUserId($email) 	{
	  $item = NULL;
	  if ($email == NULL) throw new Exception("email needs to be set! for getUserId method");
    try {
            //this would be our query.
            $sql = "SELECT * FROM users WHERE email = :email";
            //if (isset($itemArray["measure"])) if ($itemArray["measure"] != NULL) $sql = $sql . " AND measure = :measure";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "email", $email, PDO::PARAM_STR );
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          echo "Error with getUserId method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }
      return (int)$item["userId"];
  }

  public function shareGrList($grListId, $userId, $sharedWithId) {
	  $grListCheck = $this->getGrListId($userId);
	  if (!empty($grListCheck)) foreach ($grListCheck as $grList) {
		if ($grList["grListId"] == $grListId) {
			  try {
		        $sql = "UPDATE groceryList SET sharedWithId = :sharedWithId WHERE grListId = :grListId";
            
            $stmt = $this->con->prepare( $sql );
            $stmt->bindValue( "sharedWithId", $sharedWithId, PDO::PARAM_INT );
            $stmt->bindValue( "grListId", $grListId, PDO::PARAM_INT );
            $result = $stmt->execute();
            if ($result == TRUE) return TRUE;
          } catch( PDOException $e ) {
	          echo "Error in the shareGrList method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
            echo $e->getMessage() . "</br>";
            exit;
          }
		  }
	  }
	  return FALSE;
  }
  
  public function getShareGrListId($userId) {
	  $result = FALSE;
	  if ($userId!==NULL) try {
            //this would be our query.
            $sql = "SELECT * FROM groceryList WHERE sharedWithId = :sharedWithId";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "sharedWithId", $userId, PDO::PARAM_INT );
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
          echo "Error with getGrListId method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
          echo $e->getMessage() . "</br>";
          exit;
        }
	  return $result;
  }
}

?>