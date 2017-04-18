<?php
/*  Users class
*
* The Usera class registers users, logs uses in, and establishes sessions
* and Cookies to maintain the user's logged in status
*
*/

class Users 
{
    private $password = null;
    private $keepli = null;
    private $email = null;
    private $valid = null;
    private $con = NULL;
    private $userId = NULL;
    
    public function __construct( $data = array() ) {
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
        if( isset( $data['password'] ) ) $this->password = stripslashes( strip_tags( $data['password'] ) );
        if( isset( $data['email'] ) ) $this->email = stripslashes( strip_tags( $data['email'] ) );
	      if( isset( $data['keepli'] ) ) $this->keepli = stripslashes( strip_tags( $data['keepli'] ) );
	      if( isset( $data['userId'] ) ) $this->userId = stripslashes( strip_tags( $data['userId'] ) );
    }
    
    public function userLogin() 
	  {
        //success variable will be used to return if the login was successful or not.
        $success = FALSE;
        try {
            //this would be our query.
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            //prepare the statements
            $stmt = $this->con->prepare( $sql );
            $stmt->bindValue( "email", $this->email, PDO::PARAM_STR );
            $stmt->execute();
            $this->valid = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($this->valid) {
              $passVerify = password_verify($this->password, $this->valid['password']);
              if($passVerify) {
	              //valid is true so email exists and password is correct so success is set to true
                $this->sessionEstablish();
                $success = TRUE;
              } else {
	              $success = 2;
              }
            }
            return $success;
        } catch (PDOException $e) {
            echo "Error in the Users object userLogin method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
            echo $e->getMessage() . "</br>";
            exit;
        }
    }
    
    public function register() 
	  {
		    $success = FALSE;
        $this->userLogin();
        if($this->valid) {
            return 2;
            exit;
        }
        try {
            $sql = "INSERT INTO users(password, email) VALUES(:password, :email)";
            
            $stmt = $this->con->prepare( $sql );
            $stmt->bindValue( "password", password_hash($this->password, PASSWORD_DEFAULT), PDO::PARAM_STR );
            $stmt->bindValue( "email", $this->email, PDO::PARAM_STR );
            $stmt->execute();
            $success = $this->userLogin();
            //$this->sessionEstablish();
            return $success;
        } catch( PDOException $e ) {
	          echo "Error in the Users object register method, at line " . __LINE__. " in file " . __FILE__ . "</br>";
            echo $e->getMessage() . "</br>";
            exit;
        }
    }

    private function sessionEstablish() 
	  {
        if (session_status()==1) session_start();
        // Store Session Data
        $_SESSION['login_user'] = $this->valid;
        if ($this->keepli) {
            //setcookie for keeping user logged in between sessions
            setcookie('login_user', $this->email, time() + 3600);
        }
    }

    public static function getUserEmail($username) 
	  {
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            //prepare the statements
            $stmt = $con->prepare( $sql );
            //give value to named parameter :username
            $stmt->bindValue( "username", $username, PDO::PARAM_STR );
            $stmt->execute();
            $email = $stmt->fetchColumn(7);
            $con = null;
            return $email;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return $email;
        }
    }

    public function getUserId() {
	    $result = NULL;
	    $this->userLogin();
	    $result = (int)$this->valid['userId'];
	    return $result;
    }
}