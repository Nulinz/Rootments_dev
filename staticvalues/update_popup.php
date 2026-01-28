<?php
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);
$btn = $input['btn'];

if($btn = "rootments") {
      echo json_encode(array("version" => "1.0.15"));
}
?>
