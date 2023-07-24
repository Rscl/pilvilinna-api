<?php
include("cors.inc");
http_response_code(200);
echo json_encode(array("message" => "API OK"));
?>
