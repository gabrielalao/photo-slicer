<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('fileToUpload', 'Image', 'callback_check_file');
		$this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');
		
		if ($this->form_validation->run() == FALSE)
		{
				$this->load->view('image_slice');
		}
		else
		{
				//print_r($_FILES);
				//print_r($_POST);die;
			    
				//$qrtext=$this->input->post('qrtext');
				
				
				if(!is_dir('uploads')) {
				  mkdir('uploads', 0777) ;
				}
				
				$target_dir = "uploads/";
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				$filenameuniq=time().uniqid();
		
				$filename=$filenameuniq.'_original.'.$imageFileType;
				
				$target_file = $target_dir . basename($filename);
				
				//echo $target_file;die;
				
				if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					//echo "The file ". basename( $filename). " has been uploaded.";
					
					//$filename = 'uploads/'.$filename;
					$filename = $target_file;
					$fileData = getimagesize($filename);
					
					
					if($imageFileType=='jpeg' || $imageFileType=='jpg')
					{
						$source_handle = ImageCreateFromJPEG($filename);
					}
					elseif($imageFileType=='png')
					{
						$source_handle = imagecreatefrompng($filename);
					}
					elseif($imageFileType=='gif')
					{
						$source_handle = imagecreatefromgif($filename);
					}
					else
					{
						echo "File not supported.";
						exit;
					}
					
					$imageW = $fileData[0];
					$imageH = $fileData[1];
					
					
					
					$data['original_img']= ' <img src="'.$target_file.'" alt="ORIGINAL" width="400">';
					
					//echo '<br><br> SLICE IMAGES <br><br>';
					
					//$total_slice=4;
					
					$type=$this->input->post('slice_type');
					
					$total_slice=$this->input->post('slice_cnt');
					
					$total_slice_cnt=$total_slice;
					if($type==2)
					{
						$total_slice_cnt=' '.$total_slice.' X '.$total_slice.' ==> '.$total_slice*$total_slice;
					}
					
					if($type==0)$slice_type='Horizontal';elseif($type==1)$slice_type='Vertical';elseif($type==2)$slice_type='Grid';
					
					$data['slice_cnt_msg'] = ' SLICE COUNT : '.$total_slice_cnt.' ::  TYPE : '.$slice_type.' ';
					
					
					if(!is_dir('slice')) {
					  mkdir('slice', 0777) ;
					}
					
					mkdir('slice/'.$filenameuniq, 0777) ;
					
					$output_dir='slice/'.$filenameuniq.'/';
					
					$slice_img='';
					
					if($type==0)
					{
						$X = 0;
						for($i=1;$i<=$total_slice;$i++)
						{
							//$X = 0;
							$Y = 0;
							$W = $imageW/$total_slice;
							$H = $imageH;
							
							$to_crop_array = array('x' =>$X , 'y' =>$Y, 'width' => $W, 'height'=> $H);
							$imgCroped = imagecrop($source_handle, $to_crop_array);
							
							/* Image Split */
							$target_slice=$output_dir.$filenameuniq.'_slice_'.$i.'.jpeg';
							imagejpeg($imgCroped, $target_slice, 100);
						
							$X = $X+$W;
							
							$src=$target_slice;
							$slice_img.= ' <img src="'.$src.'" alt="Slice Image" width="100" >';
							
						}
						
					}
					elseif($type==1)
					{
						$Y = 0;
						for($i=1;$i<=$total_slice;$i++)
						{
							$X = 0;
							//$Y = 0;
							$W = $imageW;
							$H = $imageH/$total_slice;
							
							$to_crop_array = array('x' =>$X , 'y' =>$Y, 'width' => $W, 'height'=> $H);
							$imgCroped = imagecrop($source_handle, $to_crop_array);
							
							/* Image Split */
							$target_slice=$output_dir.$filenameuniq.'_'.$i.'.jpeg';
							imagejpeg($imgCroped, $target_slice, 100);
						
							//$X = $X+$W;
							$Y = $Y+$H;
							
							$src=$target_slice;
							$slice_img.= ' <img src="'.$src.'" alt="Slice Image" width="500" >';
							$slice_img.= '<br><br>';
						}
						
						//echo $slice_img;die;
					}
					elseif($type==2)
					{
						$X = 0;
						$Y = 0;
						$gridX=$total_slice;
						$gridY=$total_slice;
						
						$OW = $imageW;//image height
						$OH = $imageH;//image width
						$W = $OW/$gridX;//image height
						$H = $OH/$gridY;//image width
						
						
						for($i=1;$i<=$gridX;$i++)
						{
							$X=0;
							for($j=1;$j<=$gridY;$j++)
							{
								
								$to_crop_array = array('x' =>$X , 'y' =>$Y, 'width' => $W, 'height'=> $H);
								$imgCroped = imagecrop($source_handle, $to_crop_array);
								
								/* Image Split */
								$target_slice=$output_dir.$filenameuniq.'_'.$i.'_'.$j.'.jpeg';
								imagejpeg($imgCroped, $target_slice, 100);
								
								//echo $i.' X:'.$X.'   Y:'.$Y.'   W:'.$W.'  H:'.$H.'';
								//echo '<br><br>';
								
								$src=$target_slice;
								$slice_img.= ' <img src="'.$src.'" alt="Slice Image" width="50" style="padding: 1px;" >';
								
								$X=$j * $W;
							}
							
							$slice_img.= '<br> ';
							
							$Y=$i * $H;
							
						}
						
						
					}
					
					
					$data['slice_img']=$slice_img;
					
					$data['filenameuniq']=$filenameuniq;
					$data['imageFileType']=$imageFileType;
					
					
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
				
				
				$this->load->view('image_slice',$data);
		}
		
	}
	
	function download_file($file_name='')
	{
		$img=(isset($_POST['img_name']))?$_POST['img_name']:'';
	
		$original_file=(isset($_POST['original_file']))?$_POST['original_file']:'';
		
		if(($img=='') || (!file_exists('./uploads/'.$original_file)))  
		{ 
			echo "<br> <br> Images Already downloaded or removed from server, Please try again";
			
			echo '<br> <br> <a href="'.base_url().'"><h1>TRY AGAIN</h1></a>';
			exit; 
		} 
		
		$error = ""; //error holder
		
		$file_folder = "slice/".$img."/"; // folder to load files
		if(extension_loaded('zip'))
		{ 
				// Checking files are selected
				
				$rootPath = realpath($file_folder);
				
				// Initialize archive object
				$zip = new ZipArchive();
				$zip->open('slice/'.$img.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
				
				// Create recursive directory iterator
				/** @var SplFileInfo[] $files */
				$files = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($rootPath),
					RecursiveIteratorIterator::LEAVES_ONLY
				);
				
				foreach ($files as $name => $file)
				{
					// Skip directories (they would be added automatically)
					if (!$file->isDir())
					{
						// Get real and relative path for current file
						$filePath = $file->getRealPath();
						$relativePath = substr($filePath, strlen($rootPath) + 1);
				
						// Add current file to archive
						$zip->addFile($filePath, $relativePath);
					}
				}
				
				// Zip archive will be created only after closing object
				$zip->close();
				
				$filename=$img.'.zip';
				
				//DOWNLOAD ZIP FILE
				$this->download($filename);
				
				//REMOVE DIRECTORY
				$this->deleteDir($img,$original_file);
				
		}
		
	}
	
	
	function download($file)
	{
			header("Content-type: application/zip"); 
			header("Content-Disposition: attachment; filename=$file"); 
			header("Pragma: no-cache"); 
			header("Expires: 0"); 
			readfile("slice/$file");
			
			/*$this->load->helper('download');
				
			force_download('./slice/'.$file, NULL);*/
			
			unlink('./slice/'.$file);
			
			return true;		
			
	}


	function deleteDir($dirPath,$original_file) {
		//$dirPath='15861864625e8b48de51812';
		$rootPath = 'slice/'.$dirPath;
		
		$files = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		
		foreach ($files as $name => $file)
		{
			if (!$file->isDir())
			{
				$getFilename = $file->getFilename();
				unlink('./slice/'.$dirPath.'/'.$getFilename.'');
			}
		}
		rmdir('./slice/'.$dirPath);
		
		unlink('./uploads/'.$original_file.'');
		
		return true;
	}
	
	
	function clear_all() {
		
		$dir='slice';
		$this->rrmdir($dir);
		
		$dir2='uploads';
		$this->rrmdir2($dir2);
	}
	
	function rrmdir($dir) {
	  if (is_dir($dir)) {
		$dir_array=array();
		s:  
		$objects = scandir($dir);
		foreach ($objects as $object) {
		  if ($object != "." && $object != "..") {
			if (filetype($dir."/".$object) == "dir")
			{
			   $dir=$dir."/".$object;
			   array_push($dir_array,$dir);
			   goto s;
			}
			else
			{
				 unlink ($dir."/".$object);
			}
		  }
		}
		reset($objects);
		//rmdir($dir);
		
		if(!empty($dir_array))
		{
			foreach($dir_array as $dir)
			{
				rmdir($dir);
				$dir='slice';
				$this->rrmdir($dir);
			}
		}
		else
		{
			echo '<h1>ALL SLICE CLEARED</h1>';
		}
		
	  }
	 }

	function rrmdir2($dir) {
	  if (is_dir($dir)) {
		$dir_array=array();
		t:  
		$objects = scandir($dir);
		foreach ($objects as $object) {
		  if ($object != "." && $object != "..") {
			if (filetype($dir."/".$object) == "dir")
			{
			   $dir=$dir."/".$object;
			   array_push($dir_array,$dir);
			   goto t;
			}
			else
			{
				 unlink ($dir."/".$object);
			}
		  }
		}
		reset($objects);
		//rmdir($dir);
		
		if(!empty($dir_array))
		{
			foreach($dir_array as $dir)
			{
				rmdir($dir);
				$dir='uploads';
				$this->rrmdir2($dir);
			}
		}
		else
		{
			echo '<h1>ALL UPLOADS CLEARED</h1>';
			
			echo '<a href="'.base_url().'"><h1>GOTO HOME</h1></a>';
		}
		
	  }
	 }
	
	
	function check_file()
	{
		
		if($_FILES["fileToUpload"]["name"]=='')
        {
            $this->form_validation->set_message('check_file', "Please select image for slices.");
            return FALSE;
        }
		else
		{
			$target_file = basename($_FILES["fileToUpload"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$this->form_validation->set_message('check_file', $msg);
				return FALSE;
			} 
			else
			{
				return TRUE;
			}
		}
			
	}
	
}
