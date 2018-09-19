<?php

$email = shell_exec('./check_smtp -H outlook.office365.com');
$json = json_encode($email);

if ($json[6] == 'O' && $json[7] == 'K') {
   $response = array('status'=>'UP');
}
else {
   $response = array('status'=>'DOWN');
}

echo json_encode($response);
?>

