<?php
include 'vendor/autoload.php';
include 'cors.inc';
include 'db.inc';
include 'Token.php';

/* Example request:
{
    "parent": 0, //Yläkansio, jos menossa juureen niin silloin null
    "name": "nimi",
    "note": "muistiinpanoja, yms..."
}
*/

$user = Authenticate();
if($user == NULL)
{
    http_response_code(401);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $json = file_get_contents("php://input");
    $data = json_decode($json);
    $conn = new mysqli($servername, $username, $password, $dbname);

    if(is_null($data->parent))
    {
        $parent = "NULL";
    }
    else
        $parent = $data->parent;

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    print_r($data);
    $sql = "INSERT INTO `files` (`id`, `parent`, `username`, `name`, `location`, `type`, `note`) VALUES (NULL, ".$parent.", '".$user."', '".$data->name."', NULL, 'folder', '".$data->note."');";
    if($conn->query($sql)=== TRUE)
    {
        echo json_encode(array("message" => "Kansio luotu onnistuneesti!"));
    }
    else
    {
        http_response_code(500);
        echo json_encode(array("message" => "Virhe luodessa uutta kansiota.",
        "code" => $conn->errno,
        "details" => $conn->error));
    }
}
/*
{
    "id": 123
}
*/
if($_SERVER["REQUEST_METHOD"] === "DELETE")
{
    $json = file_get_contents("php://input");
    $data = json_decode($json);
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "DELETE FROM files WHERE `files`.`id` = ".$data->id." AND `files`.`type`='folder'";
    if($conn->query($sql)===TRUE)
    {
        echo json_encode(array("message" => "Kansio poistettu onnistuneesti!"));
    }
    else
    {
        http_response_code(500);
        echo json_encode(array("message" => "Virhe poistettaessa kansiota.",
        "code" => $conn->errno,
        "details" => $conn->error));
    }
}
?>