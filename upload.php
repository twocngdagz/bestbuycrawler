<?php
	require_once("/includes/initialize.php");
	if(isset($_POST['upload'])) {
		$file = new File();
		$file->attach($_FILES['filename']);
		if($file->save()) {
			// Success
			//$file->process();
			redirect_to('index.php');
      $session->message("Photograph uploaded successfully.");
			//redirect_to('list_photos.php');
		} else {
			// Failure
      $message = join("<br />", $file->errors);
      echo $message;
		}
	}
?>