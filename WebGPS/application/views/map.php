<html>
<title>Device Tracker</title>
  <head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>media/bootstrap-3.2.0/dist/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>media/bootstrap-3.2.0/dist/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>media/css/bootstrapValidator.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>media/css/bootstrap-datetimepicker.min.css">
	<link href='http://fonts.googleapis.com/css?family=Fredoka One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto:700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Nunito' rel='stylesheet' type='text/css'>

	<!-- Latest compiled and minified JavaScript -->
	<script src="<?php echo base_url(); ?>media/js/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>media/js/moment.js"></script>
	<script src="<?php echo base_url(); ?>media/bootstrap-3.2.0/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/bootstrap-datetimepicker.min.js" ></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>
    <script>
     function initialize() {
      var mapOptions = {
	zoom: 12,
	center: new google.maps.LatLng(-1.3048035,36.8473969),
	mapTypeId: google.maps.MapTypeId.TERRAIN
      };

      var map = new google.maps.Map(document.getElementById('map-canvas'),
	  mapOptions);

      var flightPlanCoordinates = [
	<?php $i = 0; if($coordinates != null){foreach($coordinates->result() as $coordinate){if($i > 0){?>,<?php }?>
	  new google.maps.LatLng(<?php echo $coordinate->latitude; ?>, <?php echo $coordinate->longitude; ?>)
	<?php $i ++; }} ?>
      ];
      var flightPath = new google.maps.Polyline({
	path: flightPlanCoordinates,
	geodesic: true,
	strokeColor: '#FF0000',
	strokeOpacity: 1.0,
	strokeWeight: 2
      });

      flightPath.setMap(map);
    }

    google.maps.event.addDomListener(window, 'load', initialize); 
    $(function () {
                $('#from').datetimepicker({
                    pick12HourFormat: false
                    });
                $('#to').datetimepicker({
                    pick12HourFormat: false
                    });
            });
    </script>
	<style type="text/css">	
	      body {
		width:1100px;
		margin: 0 auto;
	      }
		.page {
		height:80%;		
		}
		.header {
		height:15%;
		text-align:center;
		}
	</style>
  </head>
  <body>
    <div class="header">
      <h1>DEVICE TRACKER</h1>
      <div class="row">
	<div class="col-xs-6 col-sm-3">
	<form action="" method="POST">
	    <select class="form-control input-sm" name="device_id"><option>--Please Select A Device To Track--</option><?php foreach($devices->result() as $device){?><option><?php echo $device->device_id; ?></option><?php } ?></select>
	    </div>
	    <div class="col-xs-6 col-sm-3">
		    <div class='input-group date' id='from'>
			<input type='text' class="form-control" name="from" />
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
			</span>
		    </div>
	    </div>
	    <div class="col-xs-6 col-sm-3">
		    
		    <div class='input-group date' id='to'>
			<input type='text' class="form-control" name="to"/>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
			</span>
		    </div>
	    </div>
	    <div class="col-xs-6 col-sm-3"><input type="submit" class="btn btn-primary" value="PLOT PATH ON MAP" /></div>
	</form>
      </div>
      <?php //$this->output->enable_profiler(TRUE); ?>
    </div>
    <div id="map-canvas" class="page"></div>
  </body>
</html>
