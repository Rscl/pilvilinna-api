<?php
include 'vendor/autoload.php';
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
    $sql = "SELECT * FROM users WHERE username = '$user'";

    $result = $conn->query($sql);
    if($result->num_rows>0)
    {
        http_response_code(401);
        echo json_encode(array("message" => "Käyttäjätunnus varattu!"));
    }
    else
    {
        $sql = "INSERT INTO users (username, password) VALUES('$user', '$pass')";
        if($conn->query($sql)===TRUE)
        {
            echo json_encode(array("message" => "Käyttäjä luotu onnistuneesti!"));
        }
        else
        {
            http_response_code(500);
            echo json_encode(array("message" => "Virhe tuotaessa uutta tiedostoa.",
                    "code" => $conn->errno,
                    "details" => $conn->error));
        }
    }
    $conn->close();
}

?>