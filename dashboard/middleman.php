<?php
include_once ('config_db.inc.php');
include_once ('sec_funcs.php');

if (isset($_GET['date'])) {
  $date = $_GET['date'];
}
else {
  header ('Location: .'); // if no date is given, (url manually entered wrong) go to dashboard
}
$route = ''; // declare route variable
$routeGiven = 0;
$conn = getconnection();
$array = array();
$total = array();
$routes = array();
$grand = array();

if (isset($_GET['route']) && $_GET['route'] != '' ) { //if a route is provided, go here
  //declare variables before loop to prevent deletion of content
  foreach ($_GET['route'] as $route) {  //loops through each route provided
    $routes[] = $route; // store given routes to use later in table.html.php
  }
  $routeGiven = 1;
}

$query = "SELECT Address1, count(*), Route_ID from spot_invoice where date(PromisedDate) = '".$date."'
and Voided=0 and Route_ID in (select InstanceID from spot_route where Active=1) and closet_barcode not like 'UNKNOWN'
group by Address1 order by Address1 asc"; // query for cols 1 and 2
$result = $conn->query($query);

$query2 = "SELECT DISTINCT(spot_group), count(*) FROM spot_invoice_driver_audit WHERE
DATE(created_on) = '".$date."' AND status='Delivered' GROUP BY spot_group"; // query for col 3
$result2 = $conn->query($query2);

$query3 = "SELECT DISTINCT(spot_group), count(*) FROM spot_invoice_driver_audit WHERE
DATE(created_on) = '".$date."' AND status='Still in closet' GROUP BY spot_group";
$result3 = $conn->query($query3);

$query4 = "SELECT Address1, spot_group FROM `spot_invoice_driver_audit` WHERE `alert_processed` = 0 AND date(created_on) = '".$date."'";
$result4 = $conn->query($query4);

$valid = 0;
while ($row = mysqli_fetch_row($result)) {
  $routeQuery = "SELECT RouteName FROM spot_route WHERE InstanceID LIKE '".$row[2]."'"; // translate routeName to routeID
  $routeResult = $conn->query($routeQuery);
  $rrRow = mysqli_fetch_row($routeResult);
  $routeId = $rrRow[0];
  $array[] = ["address"=>$row[0], "col2"=>$row[1], "col3"=>'', "col4"=>'', "col5"=>0, "date"=>$date, "route"=>$routeId, "lower"=>strtolower($row[0])];
  $valid = 1;
}

while ($row = mysqli_fetch_row($result2)) {
  $key = array_search(strtolower($row[0]), array_column($array, 'lower'));
  if ($key !== false) {
    $array[$key]['col3'] = $row[1];
  }
}

while ($row = mysqli_fetch_row($result3)) {
  $key = array_search(strtolower($row[0]), array_column($array, 'lower'));
  if ($key !== false) {
    $array[$key]['col4'] = $row[1];
  }
}
$grandAnom = 0;
while ($row = mysqli_fetch_row($result4)) {
  $key = array_search(strtolower($row[0]), array_column($array, 'lower'));
  if ($key !== false) {
    $array[$key]['col5'] = $array[$key]['col5'] + 1;
    if (!$routeGiven || ($routeGiven && in_array($array[$key]['route'], $routes)))
      $grandAnom = $grandAnom + 1;
  }
  $oldKey = $key;
  $key = array_search(strtolower($row[1]), array_column($array, 'lower'));
  if ($key !== false && $key != $oldKey) {
    $array[$key]['col5'] = $array[$key]['col5'] + 1;
    if (!$routeGiven || ($routeGiven && in_array($array[$key]['route'], $routes)))
      $grandAnom = $grandAnom + 1;
  }
}

$conn->close();
array_multisort(array_column($array, 'route'), SORT_ASC, $array);
$grandScan = 0;
$grandProm = 0;
$grandClos = 0;
if ($valid) { // if the mysql queries returned anything...
  $prevRoute = $array[0]['route'];
  $scan = 0;
  $prom = 0;
  $clos = 0;
  $anom = 0;

  foreach($array as $row) {
    $route = $row['route'];
    if ($route == $prevRoute) {
      $scan = $scan + $row['col2'];
      $prom = $prom + $row['col3'];
      $clos = $clos + $row['col4'];
      $anom = $anom + $row['col5'];
      $prevRoute = $route;
    }
    else {
      $total[$prevRoute] = ['scan'=>$scan, 'prom'=>$prom, 'clos'=>$clos, 'anom'=>$anom];
      if (!$routeGiven || ($routeGiven && in_array($prevRoute, $routes))) {
        $grandScan = $grandScan + $scan;
        $grandProm = $grandProm + $prom;
        $grandClos = $grandClos + $clos;
      }
      $scan = $row['col2'];
      $prom = $row['col3'];
      $clos = $row['col4'];
      $anom = $row['col5'];
      $prevRoute = $route;
    }
  }
  $total[$prevRoute] = ['scan'=>$scan, 'prom'=>$prom, 'clos'=>$clos, 'anom'=>$anom];
  $scan = $row['col2'];
  $prom = $row['col3'];
  $clos = $row['col4'];
  $anom = $row['col5'];
  if (!$routeGiven || ($routeGiven && in_array($prevRoute, $routes))) {
    $grandScan = $grandScan + $scan;
    $grandProm = $grandProm + $prom;
    $grandClos = $grandClos + $clos;
  }
}
else { // if no mysqli results, populate array with placeholder
  $array[] = ["address"=>"No Results Found", "col2"=>'----', "col3"=>'----', "col4"=>'----', "col5"=>"----", "date"=>$date, "route"=>$route, "lower"=>strtolower($route)];
}
// }
include 'index2.html.php'; // after array and total are populated, include index2

function getconnection(){
    global $db_host, $db_user, $db_name, $db_pass;
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Error " . mysqli_error($conn));
    $conn->set_charset("latin1");
    return $conn;
}
?>
