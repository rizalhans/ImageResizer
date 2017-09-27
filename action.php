<?php
require_once("config.php");
require_once("library/images_resize.php");
if(isset($_GET['hapus']) && $_GET['hapus'] == 1) {
	if(isset($_POST['src']) && $_POST['src'] != "") {
		$src = $_POST['src'];
		if(is_file($src)) {
			if(unlink($src)) {
				$result = array("status"	=> true,
								"reload"	=> false,
								"notif"		=> "Hapus data Berhasil",
								);
				echo json_encode($result);
				exit;
			} else {
				$result = array("status"	=> false,
								"reload"	=> false,
								"notif"		=> "Error Unlink",
								);
				echo json_encode($result);
				exit;
			}
		} else {
				$result = array("status"	=> false,
								"reload"	=> false,
								"notif"		=> "File Tidak ditemukan",
								);
				echo json_encode($result);
				exit;
		}
	} else {
				$result = array("status"	=> false,
								"reload"	=> false,
								"notif"		=> "Ajax tidak mengirim src",
								);
				echo json_encode($result);
				exit;	
	}
}
if(isset($_GET['resize']) && $_GET['resize'] == 1) {
	if(isset($_POST['src']) && $_POST['src'] != "") {
		$src = $_POST['src'];
		if(isset($_POST['src']) && $_POST['src'] != "") {
			$sizetarget = $_POST['sizetarget'];
		} else {
			$sizetarget = SIZE_TARGET;
		}
		$dir_output = DIR_OUTPUT."/".$sizetarget;
		if(!is_dir($dir_output)) {
			mkdir($dir_output);
		}
		$nama_file = pathinfo($src,PATHINFO_BASENAME);
		$image = new RanaImage(); 
		$image->load($src);
		$image->thumbproduk($sizetarget);
		$image->save($dir_output."/".$nama_file);

		$result = array("status"		=> true,
						"reload"		=> false,
						"notif"			=> "Resize data Berhasil",
						"link"			=> $dir_output."/".$nama_file,
						);
		echo json_encode($result);
		exit;
	} else {
				$result = array("status"	=> false,
								"reload"	=> false,
								"notif"		=> "Ajax tidak mengirim src",
								"link"		=> "",
								);
				echo json_encode($result);
				exit;	
	}
}
if(isset($_GET['resizeall']) && $_GET['resizeall'] == 1) {
	$Dir = DIR_TARGET;
	$array_file = array();
	$id = 0;
	if(isset($_POST['sizetarget']) && $_POST['sizetarget'] != "") {
		$sizetarget = $_POST['sizetarget'];
	} else {
		$sizetarget = SIZE_TARGET;
	}
	$dir_output = DIR_OUTPUT."/".$sizetarget;
	if(!is_dir($dir_output)) {
		mkdir($dir_output);
	}
	if(is_dir($Dir)) {
		if($handle = opendir($Dir)) {
			while(($file = readdir($handle)) !== false) {
				if($file != "." && $file != "..") {
					// aksi dimulai
					$nama_file = pathinfo($file,PATHINFO_BASENAME);
					$image = new RanaImage(); 
					$image->load(DIR_TARGET."/".$file);
					$image->thumbproduk($sizetarget);
					$image->save($dir_output."/".$nama_file);
				}
			}
			closedir($handle);
		}
	}
			$result = array("status"		=> true,
							"reload"		=> false,
							"notif"			=> "Resize data Berhasil ".$dir_output."/".$nama_file,
							);
			echo json_encode($result);
			exit;	
}
?>