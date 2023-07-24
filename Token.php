<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function Authenticate()
{
    include 'api.inc';
    if(isset($_SERVER["HTTP_X_TOKEN"])){
        $jwt = $_SERVER["HTTP_X_TOKEN"];
        try{
            $token = (array)JWT::decode($jwt, new Key($apikey,$apialg));
        }
        catch(Exception $e)
        {
            http_response_code(401);
            echo json_encode(array("message"=>"Token verification failure."));
            exit();
        }
        $user = $token['sub'];
        return $user;
    }
    return NULL;
}
?>