<?php

class UserQuery {

  private $mysqli;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function getUserCount() {

    $query = "SELECT count(*) FROM user";

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->store_result();

    while($stmt->fetch()) {
      $total = $total;
    }

    $stmt->close();

    if($this->mysqli->error) {
      return null;
    } else {
      return $total;
    }
  }

  function addUser($first_name, $last_name, $email, $utype_id, $ustatus_id) {

    $query = "INSERT INTO user
              (first_name, last_name, email, utype_id, ustatus_id)
              VALUES (?,?,?,?,?)";


      if(!($stmt = $this->mysqli->prepare($query))){
      	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
      }

      $stmt->bind_param("sssii",$first_name, $last_name, $email, $utype_id, $ustatus_id);
      $stmt->execute();

      if ($this->mysqli->error) {
        return $this->mysqli->error;
      } else {
        return null;
      }
  }

  // BUG - Joining ustatus fails for no apparent reason
  // Returns an array of artists, supports limits for pagination
  function getUser($user_id) {

    $query = "SELECT user.user_id, user.utype_id, utype.description,
                     user.ustatus_id, user.first_name,
                     user.last_name, user.email
              FROM user
              INNER JOIN utype ON user.utype_id = utype.utype_id
              WHERE user_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i",$user_id);

    $stmt->execute();
    $stmt->bind_result($user_id, $utype_id, $utype_description, $ustatus_id,
                       $first_name, $last_name, $email);
    $stmt->store_result();

    while($stmt->fetch()) {
      $result = array('user_id' => $user_id,
                        'utype_id' => $utype_id,
                        'utype_description' => $utype_description,
                        'ustatus_id' => $ustatus_id,
                        'ustatus_description' => 'broken',
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email);
    }
    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }


  // BUG: Inner Join on ustatus fails for no apparent reason.  Schema problem?

  // Returns an array of artists, supports limits for pagination
  function getUsers($offset = 0, $limit = 0, $order_by = "last_name") {

    $query = "SELECT user.user_id, user.utype_id, utype.description,
                     user.ustatus_id, user.first_name,
                     user.last_name, user.email
              FROM user
              INNER JOIN utype ON user.utype_id = utype.utype_id
              ORDER BY ? ASC ";

    // Support windowing for pagination
    if($limit > 0 || $offset > 0) {
      $query .= " LIMIT ?,? ";
    }

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    if($limit > 0 || $offset > 0) {
      $stmt->bind_param("sii",$order_by,$offset,$limit);
    } else {
      $stmt->bind_param("s",$order_by);
    }
    $stmt->execute();
    $stmt->bind_result($user_id, $utype_id, $utype_description, $ustatus_id,
                       $first_name, $last_name, $email);
    $stmt->store_result();

    $result = array();
    while($stmt->fetch()) {
    	$result[] = array('user_id' => $user_id,
                        'utype_id' => $utype_id,
                        'utype_description' => $utype_description,
                        'ustatus_id' => $ustatus_id,
                        'ustatus_description' => 'broken',
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'fines' => "0.00");
    }
    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  function deleteUser($user_id) {

    $query = "DELETE FROM user WHERE user_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i",$user_id);
    $stmt->execute();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

  function updateUser($user_id, $first_name, $last_name, $email, $utype_id, $ustatus_id) {

    $query = "UPDATE user SET
              utype_id = ?,
              ustatus_id = ?,
              first_name = ?,
              last_name = ?,
              email = ?
              WHERE user_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("iisssi",$utype_id, $ustatus_id, $first_name, $last_name, $email, $user_id);
    $stmt->execute();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }
}
?>
