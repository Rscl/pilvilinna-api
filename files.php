<?php
include 'vendor/autoload.php';
include 'cors.inc';
include 'db.inc';
include 'Token.php';

$user = Authenticate();
if($user == NULL)
{
    http_response_code(401);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "GET")
{
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM files WHERE username = '$user' AND parent IS NULL";
    $result = $conn->query($sql);
    $files = array();
    while($row = mysqli_fetch_assoc($result))
    {
        $row["children"] = fetch_children($row, $user);
        $files[] = $row;
        
    }
    echo json_encode($files);
    exit();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $metadata = json_decode($_POST['metadata'], true);
    foreach($_FILES as $uploadedFile)
    {
        if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $uploadedFile['tmp_name']);
            $newname = generateRandomFilename($uploadedFile['name']);
            $destinationPath = getcwd() . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $newname;
            if (move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
                //echo 'File successfully uploaded to ' . $destinationPath;
                if(is_null($metadata["folder"]))
                    $folder = "NULL";
                else
                    $folder = $metadata["folder"]["id"];
                if(empty($metadata["note"]))
                {
                    $metadata["note"] = "<b>tiedosto ladattu:</b> ".date("H:i:s d.m.Y");
                }
                $sql = "INSERT INTO `files`(parent, username, name, location, type, note) VALUES(".$folder.", '".$user."', '".$uploadedFile["name"]."', '/files/".$newname."', '".$mime_type."', '".$metadata["note"]."')";
                if($conn->query($sql)=== TRUE)
                {
                    echo json_encode(array("message" => "Tiedosto tuotu onnistuneesti!"));
                }
                else
                {
                    http_response_code(500);
                    echo json_encode(array("message" => "Virhe tuotaessa uutta tiedostoa.",
                    "code" => $conn->errno,
                    "details" => $conn->error));
                }
                $conn->close();
            } else {
                echo 'There was an error moving the uploaded file.';
            }
        } else {
            echo 'Upload error: ' . $uploadedFile['error'];
        }
    }
    
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE")
{
    $json = file_get_contents("php://input");
    $data = json_decode($json);
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT * FROM files WHERE id=".$data->id;
    $result = $conn->query($sql);
    $item = $result->fetch_assoc();
    if(file_exists(".".$data->location))
        unlink(".".$data->location);
    $sql = "DELETE FROM files WHERE `files`.`id` = ".$data->id." AND `files`.`type`!='folder'";
    if($conn->query($sql)===TRUE)
    {
        echo json_encode(array("message" => "Tiedosto poistettu onnistuneesti!"));
    }
    else
    {
        http_response_code(500);
        echo json_encode(array("message" => "Virhe poistettaessa tiedostoa.",
        "code" => $conn->errno,
        "details" => $conn->error));
    }
}

function fetch_children($parent, $user)
{
    include 'db.inc';
    $id = $parent['id'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM files WHERE username = '$user' AND parent = '$id'";
    $result = $conn->query($sql);
    $children = array();
    while($row = mysqli_fetch_assoc($result))
    {
        $row["children"] = fetch_children($row, $user);
        $children[] = $row;
        
    }
    return $children;
}

function generateRandomFilename($filename, $length = 15) {
    // Get file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // Generate random string
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    // Return random string with original file extension
    return $randomString . '.' . $extension;
}
?>