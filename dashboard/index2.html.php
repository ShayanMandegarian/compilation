<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  <meta charset="utf-8" />
  <title>Reports</title>

  <style>

  ::selection {
    background: #8f61e5; /* WebKit/Blink Browsers */
    color:#fff;
  }
  ::-moz-selection {
    background: #8f61e5; /* Gecko Browsers */
    color:#fff;
  }
  .btn-custom { /* css for purple button */
          background-color:#8f61e5 !important;
          color: #fff !important;
  }

  #myForm select {
    width:1550px; }

  #loader {
  position: fixed;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  border: 36px groove #CCCCFF;
  border-radius: 50%;
  border-top: 36px groove #8f61e5;
  border-bottom: 36px groove #8f61e5;
  width: 220px;
  height: 220px;
  -webkit-animation: spin 1.2s infinite;
  animation: spin 1.2s  infinite;
  }

  @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(359.9999999999deg); }
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(359.99999999999deg); }
  }

  input[type=date]:focus {
    border-color: #8f61e5;
    box-shadow: 2px 2px 1px 1px #8f61e5;
  }
  select[multiple]:focus{
    border-color: #8f61e5;
    box-shadow: 2px 2px 1px 1px #8f61e5;
  }
  select[multiple] option:checked {
    color: #8f61e5;
    border-color: #8f61e5;
    box-shadow: 2px 2px 1px 1px #8f61e5;
  }
  </style>

  <script>
  window.onbeforeunload = function () {
    window.scrollTo(0, 0);
  }
  jQuery('#loader').show();
  jQuery('#content').hide();
  var subbed = 0;
  </script>
</head>

<body>
  <div id="loader"></div>
  <div id="content" class="wrapper">
    <div class="container-fluid">
      <div class="page-header clearfix">
        <div class="row">
          <div class="col-sm">
            <h2 class="pull-left">Search Reports</h2>
          </div>
          <div class="col-sm-2" style="margin:5px">
            <div class="text-right">
              <a href="http://localhost/dashboard/guide.html" class="btn btn-custom btn-md">Guide</a>
            </div>
          </div>
        </div>
          <form action="http://localhost/dashboard/middleman" id="myForm" method="GET">
          <div class="col-sm">
            <div class="form-group" style="width:100%; box-sizing: border-box;">
              <span>Date:</span>
              <input type="date" min="2014-01-01" max="2018-12-31" name="date" placeholder="mm/dd/yyyy" class="form-control"
              required oninvalid="this.setCustomValidity('Please enter a valid date')" oninput="setCustomValidity(''); subbed=1">
            </div>
            <div class="form-group">
              <span>Route: (optional) ctrl+click to unselect</span>
              <select name="route[]" id="sel" class="form-control" multiple style="width:100%; box-sizing: border-box;">
                <option value="Apple">Apple</option> <!-- All the routes marked as active -->
                <option value="Facebook">Facebook</option>
                <option value="Facebook*">Facebook*</option>
                <option value="Genentech">Genentech</option>
                <option value="Google">Google</option>
                <option value="Intel">Intel</option>
                <option value="MT01">MT01</option>
                <option value="MT02">MT02</option>
                <option value="MT03">MT03</option>
                <option value="MT04">MT04</option>
                <option value="MT05">MT05</option>
                <option value="MT06">MT06</option>
                <option value="MT08">MT08</option>
                <option value="MT09">MT09</option>
                <option value="MTtemp">MTtemp</option>
                <option value="TF01">TF01</option>
                <option value="TF02">TF02</option>
                <option value="TF03">TF03</option>
                <option value="TF04">TF04</option>
                <option value="TF05">TF05</option>
                <option value="TF06">TF06</option>
                <option value="TF07">TF07</option>
                <option value="TF08">TF08</option>
                <option value="TF09">TF09</option>
                <option value="Oracle">Oracle</option>
                <option value="Palantir">Palantir</option>
                <option value="RWC">RWC</option>
                <option value="StorageDonations">StorageDonations</option>
              </select>
            </div>
            <div class="text-center"><button id="buttin" type="submit" style="width:75%; box-sizing: border-box; text-align: center;" class='btn btn-lg btn-custom'><u>Search</u></button></div>
          </form>
          <br>
          </div>
        </div>
      </div>
    <?php
     include 'table.html.php';
    ?>
  </div>
  <script>
    jQuery('#loader').show();
    $(window).load(function() { // show loading spinner until page loads, then fade
      jQuery('#loader').fadeOut(1500);
      jQuery('#content').fadeIn(1500);
    });
    function showLoad() { // when the search button is pressed AND a date was chosen, fade to loading spinner
      if (subbed == 1) {
        jQuery('#loader').fadeIn(1500);
        jQuery('#content').fadeOut(1500);
      };
    };
    var load = document.getElementById("buttin"); // when search button is pressed, call showLoad
    load.addEventListener("click", showLoad, false);
    $("#buttin").on("click", showLoad());
  </script>

</body>
<html>
