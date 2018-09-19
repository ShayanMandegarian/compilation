<?php
date_default_timezone_set('America/Los_Angeles');
$stamp = date_timestamp_get(date_create());
$fulldate = date("Y-m-d", $stamp);
$hour = date("G", $stamp);
if ($hour >= 18) {
  header ('Location: middleman?date='.$fulldate);
}
else if (date("D", $stamp) == "Mon"){
  $fulldate = date('Y-m-d', strtotime("-3 days"));
  header ('Location: middleman?date='.$fulldate);
}
else {
  $fulldate = date('Y-m-d', strtotime("-1 days"));
  header ('Location: middleman?date='.$fulldate);
}
?>
