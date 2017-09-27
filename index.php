<?php
	require_once("config.php");
if(!is_dir(DIR_TARGET)) {
	mkdir(DIR_TARGET);
}
if(!is_dir(DIR_OUTPUT)) {
	mkdir(DIR_OUTPUT);
}
$Dir = DIR_TARGET;
$array_file = array();
$id = 0;
if(is_dir($Dir)) {
	if($handle = opendir($Dir)) {
		while(($file = readdir($handle)) !== false) {
			if($file != "." && $file != "..") {
				$id++;
				$array_file[]	= array("nama"		=> $file,
										"src"		=> DIR_TARGET."/".pathinfo($file,PATHINFO_BASENAME),
										"type"		=> pathinfo($file,PATHINFO_EXTENSION),
										"id"		=> $id,
										);
			}
		}
		closedir($handle);
	}
}
$DirOut = DIR_OUTPUT;
$array_file_output = array();
$id = 0;
if(is_dir($DirOut)) {
	if($handle = opendir($DirOut)) {
		while(($folder = readdir($handle)) !== false) {
			if($folder != "." && $folder != "..") {
				$id++;
				$array_file_output[]	= $folder;
				echo $folder."<br>";
			}
		}
		closedir($handle);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Resize Images</title>
  <script src="library/jquery.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="library/bootstrap/css/bootstrap-theme.min.css">
	<!-- Latest compiled and minified JavaScript -->
	<script src="library/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="#">
	      	Resize Image
	      </a>
	    </div>
	  </div>
	</nav>
	<div class="container">
		<h1>Daftar File Dalam Folder "<?php echo DIR_TARGET; ?>"</h1>
		<div class="form-inline">
		  <div class="form-group">
		    <label for="exampleInputName2">Lebar Gambar</label>
		    <input id="target_size_all" type="number" class="form-control" placeholder="Lebar yang di inginkan" value="300">
		  </div>
		  <div class="form-group">
		  	<button id="resize_all" type="button" class="btn btn-primary">Resize All</button>
		  </div>
		</div>
		<hr>
		<?php if($array_file) { ?>
		<ul class="list-group">
		<?php foreach($array_file as $data) { 
			if($data) { ?>
		  <li class="list-group-item" id="filedata<?php echo $data['id']; ?>">
		  <div class="btn-group pull-right">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			    Download <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
			    <li><a href="<?php echo $data['src']; ?>" target="_blank">Download Original File</a></li>
			  </ul>
		   </div>
		  <div class="btn-group pull-right">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			    Action <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
			    <li><a href="#modal-resize" data-src="<?php echo $data['src']; ?>" data-toggle="modal">Resize</a></li>
			    <li><a href="#filedata<?php echo $data['id']; ?>" class="hapus_data" data-src="<?php echo $data['src']; ?>">Hapus</a></li>
			  </ul>
		   </div>
		  <h4><?php echo $data['nama']; ?></h4>
		  Type : <span class="label label-info"><?php echo $data['type']; ?></span> Lokasi : <?php echo $data['src']; ?>
		  </li>
		<?php }
		} ?>
		</ul>
		<?Php } else { ?>
		<div class="alert alert-warning">Tidak ada file dalam Folder <?Php echo DIR_TARGET; ?></div>
		<?php } ?>
	</div>
<div id="modal-resize" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Input Data Resize</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
		    <label>Src</label>
		    <input id="src_file" type="text" class="form-control" placeholder="Src">
		</div>
		<div class="form-group">
		    <label>Lebar</label>
		    <input id="lebar_data" type="number" class="form-control" placeholder="Lebar" value="300">
		</div>
		<div id="download_area" class="form-group hidden">
			<a href="#" target="_blank" class="btn btn-success btn-block">Download Data</a>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="resize_data" type="button" class="btn btn-primary">Resize</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<script type="text/javascript">
	$(document).ready(function(e) {
		$(".hapus_data").click(function(e) {
			e.preventDefault();
			var src = $(this).data("src");
			var target = $(this).attr("href");
			$.ajax({
		        type: 'POST',
		        url: 'action.php?hapus=1',
		        data: {
		        	hapus 	: 1,
		        	src 	: src,
		        }
		    }).done(function(response) {
		        // Make sure that the formMessages div has the 'success' class.
		        var result = jQuery.parseJSON(response);		 
		        if(result.status == true) {
		        	$(target).remove();
		        	alert(result.notif);
		        } else {
		        	alert(result.notif);
		        }
		    }).fail(function() {
		        alert("Server Disconnected");
		    })
		})
		$("#resize_data").click(function(e) {
			e.preventDefault();
			var src = $("#src_file").val();
			var sizetarget = $("#lebar_data").val();
			$.ajax({
		        type: 'POST',
		        url: 'action.php?resize=1',
		        data: {
		        	resize 		: 1,
		        	src 		: src,
		        	sizetarget 	: sizetarget,
		        }
		    }).done(function(response) {
		        // Make sure that the formMessages div has the 'success' class.
		        var result = jQuery.parseJSON(response);		 
		        if(result.status == true) {
		        	alert(result.notif);
		        	if(result.link) {
		        		$("#download_area a").attr("href",result.link);
		        		$("#download_area").removeClass("hidden");
		        	}
		        } else {
		        	alert(result.notif);
		        }
		    }).fail(function() {
		        alert("Server Disconnected");
		    })
		})
		$("#resize_all").click(function(e) {
			$(this).text("Sedang memproses");
			$(this).addClass("disabled");
			e.preventDefault();
			$.ajax({
		        type: 'POST',
		        url: 'action.php?resizeall=1',
		        data: {
		        	resize 		: 1,
		        	sizetarget 	: $("#target_size_all").val(),
		        }
		    }).done(function(response) {
		        // Make sure that the formMessages div has the 'success' class.
		        var result = jQuery.parseJSON(response);		 
		        if(result.status == true) {
		        	alert("Resize Selesai");
		        	$("#resize_all").text("Resize All");
		        	$("#resize_all").removeClass("disabled");
		        }
		    }).fail(function() {
		        alert("Server Disconnected");
		    })
		})
		$('#modal-resize').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var recipient = button.data('src') // Extract info from data-* attributes
		  var modal = $(this)
		  modal.find('#src_file').val(recipient);
		})
		$('#modal-resize').on('hidden.bs.modal', function (event) {
		  	$('#src_file').val("");
		  	$("#download_area a").attr("href","#");
		    $("#download_area").addClass("hidden");
		})
	})
</script>