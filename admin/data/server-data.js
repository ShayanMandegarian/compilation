$(function() {
    var cpuChart1 = Morris.Donut({
        element: 'morris-donut-cpu1',
	colors: ['red','darkred','green'],
        data: [{
            label: "Idle",
            value: 12
        }, {
            label: "System",
            value: 30
        }, {
            label: "User",
            value: 20
        }],
        resize: true
    });

    var cpuChart2 = Morris.Donut({
        element: 'morris-donut-cpu2',
	colors: ['red','darkred','green'],
        data: [{
            label: "Idle",
            value: 12
        }, {
            label: "System",
            value: 30
        }, {
            label: "User",
            value: 20
        }],
        resize: true
    });	
	
	updateCpus(cpuChart1, cpuChart2);	
	
	var ramChart = Morris.Donut({
        element: 'morris-donut-ram',
	colors: ['green','red'],
        data: [{
            label: "Used",
            value: 42
        }, {
            label: "Free",
            value: 30
        }],
        resize: true
    });


	updateRAM(ramChart);
	
	$('#dataTables-routes').DataTable({
		responsive: true,
		paging: false,
		searching: false,
		columns: [
        { data: 'RouteName' },
        { data: 'promised' },
        { data: 'LastScan' },
		{ data: 'LastScanBy' },
		{ data: 'LastScanAt' }
		]
	});	
	updateEmail();	
	updateRouteData();
	
	window.setInterval(function(){
		updateEmail();
		updateRAM(ramChart);
		updateCpus(cpuChart1, cpuChart2);	
		updateRouteData();
	}, 60000);
	
	
});

function updateRAM(ramChart){
	$.get( "https://www.purpletie.com/mobile/dash_index.php/meminfo", function( data ) {
		var myData = [{'label':'Free', 'value':data['ram_free']},{'label':'Used', 'value':data['ram_used']}];
		ramChart.setData(myData);
	});
}


function updateCpus(cpuChart1, cpuChart2){
	$.get( "https://www.purpletie.com/mobile/dash_index.php/cpuinfo", function( data ) {
		var myData1 = [{'label':'User', 'value':data['cpu0']['user']},{'label':'System', 'value':data['cpu0']['sys']},{'label':'Idle', 'value':data['cpu0']['idle']}];
		cpuChart1.setData(myData1);
		var myData2 = [{'label':'User', 'value':data['cpu1']['user']},{'label':'System', 'value':data['cpu1']['sys']},{'label':'Idle', 'value':data['cpu1']['idle']}];
		cpuChart2.setData(myData2);
	});
}

function updateRouteData(){
	$.get( "https://www.purpletie.com/mobile/dash_index.php/routeinfo", function( data ) {
		var datatable = $('#dataTables-routes').DataTable();
	    datatable.clear();
		datatable.rows.add(data);
		datatable.draw();
	});
}

function updateEmail() {
   var xttp = new XMLHttpRequest();
   var data_file = "http://localhost/pt_dashboard/data/email.php";
   xttp.onreadystatechange = function(){
      if (xttp.readyState == 4) {
         var jsonObj = JSON.parse(xttp.responseText);
         if (jsonObj['status'] == 'UP') {
            card = '<div class="panel panel-success">';
            card += '<div class="panel-heading">Email Server Status: Up</div></div>';
            //document.getElementById('email').innerHTML = card;
	    $('#email').html(card);
         }
         else {
            card = '<div class="panel panel-danger">';
            card += '<div class="panel-heading">Email Server Status: Down</div></div>';
            //document.getElementById('email').innerHTML = card;
	    $('#email').html(card);
         }
       }
   }
   xttp.open("GET", data_file, true);
   xttp.send();
}

