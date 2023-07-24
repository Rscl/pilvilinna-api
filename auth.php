<?php
include 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JTW\Key;
include 'cors.inc';
include 'db.inc';
include 'api.inc';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $json = file_get_contents("php://input");
  $data = json_decode($json);
  $user = $data->username;
  $pass = $data->password;

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  // Vulnerable to SQL injection
  $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Success! Logged in
    $payload = [
      'iss' => 'api.pilvilinna.kyberlinna.fi',
      'aud' => 'api.pilvilinna.kyberlinna.fi',
      'sub' => $user
    ];
    $jwt = JWT::encode($payload, $apikey, $apialg);
    echo json_encode(array("token" => $jwt, "message" => "Käyttäjä kirjauttu sisään."));
    $conn->close();
  } else {
    // Failure! Could not log in
    http_response_code(400);
    echo json_encode(array("message" => "Väärä käyttäjätunnus tai salasana."));
  }
}
else
{
    http_response_code(405);
    echo json_encode(array("message" => "Väärä kutsun tyyppi"));
}
?>
