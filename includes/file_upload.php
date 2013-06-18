<?php
require_once(LIB_PATH.DS.'database.php');

class File extends DatabaseObject {
	protected static $table_name = "file";
	protected static $db_fields = array('id', 'filename', 'path', 'timestamp', 'size', 'type','process');
	public $id;
	public $filename;
	public $path;
	public $date;
	public $size;
	public $type;
	public $process;
	private $temp_path;
	protected $upload_dir="upload";
	public $errors=array();
	
	
	protected $upload_errors = array(
		// http://www.php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK 				=> "No errors.",
		UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
  		UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
 	 	UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
  		UPLOAD_ERR_NO_FILE 		=> "No file.",
  		UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
  		UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
  		UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
	);
	
	public function attach($file) {
		// Perform error checking on the form parameters
		if(!$file || empty($file) || !is_array($file)) {
		  // error: nothing uploaded or wrong argument usage
		  $this->errors[] = "No file was uploaded.";
		  return false;
		} elseif($file['error'] != 0) {
		  // error: report what PHP says went wrong
		  $this->errors[] = $this->upload_errors[$file['error']];
		  return false;
		} else {
			// Set object attributes to the form parameters.
		  $this->temp_path  = $file['tmp_name'];
		  $this->filename   = basename($file['name']);
		  $this->path = SITE_ROOT .DS. $this->upload_dir;
		  $this->type       = $file['type'];
		  $this->size       = $file['size'];
		// Don't worry about saving anything to the database yet.
		return true;

		}
	}
	
	public function save() {
		// A new record won't have an id yet.
		if(isset($this->id)) {
			// Really just to update the caption
			$this->update();
		} else {
			// Make sure there are no errors
			
			// Can't save if there are pre-existing errors
		  if(!empty($this->errors)) { return false; }
		  
		
		  // Can't save without filename and temp location
		  if(empty($this->filename) || empty($this->temp_path)) {
		    $this->errors[] = "The file location was not available.";
		    return false;
		  }
			
			// Determine the target_path
		  $target_path = SITE_ROOT .DS. $this->upload_dir .DS. $this->filename;
		  
		  // Make sure a file doesn't already exist in the target location
		  if(file_exists($target_path)) {
		    $this->errors[] = "The file {$this->filename} already exists.";
		    return false;
		  }
		
			// Attempt to move the file 
			if(move_uploaded_file($this->temp_path, $target_path)) {
		  	// Success
				// Save a corresponding entry to the database
				if($this->create()) {
					// We are done with temp_path, the file isn't there anymore
					unset($this->temp_path);
					return true;
				}
			} else {
				// File was not moved.
		    $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
		    return false;
			}
		}
	}
	
	
	public function process() {
		ini_set('max_execution_time', 300);
		$file_handle = fopen( $this->path . DS . $this->filename, "r");
		while (!feof($file_handle)) {
			$content = fgets($file_handle);
			if (!IsNullOrEmptyString($content)) {
				$data = explode("\t", $content);
				$bby = new BBY();
				$bby->sku = $data[0];
				$bby->upc = $data[1];
				$bby->bbysku = $data[2];
				$bby->save();
			}
			unset($bby);
		}
		ini_set('max_execution_time', 30);
	}
}
?>