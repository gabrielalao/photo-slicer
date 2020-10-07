<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Slice Image Online</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">


  <link href="<?php echo base_url()?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url()?>assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url()?>assets/vendor/ionicons/css/ionicons.min.css" rel="stylesheet">


  <link href="<?php echo base_url()?>assets/css/style.css" rel="stylesheet">

</head>

<body>


  <main id="main">


     <!-- ======= Contact Section ======= -->
    <section  id="contact" class="section-bg wow fadeInUp">
      <div class="container">

        <div class="section-header">
          <h3>Slice Images</h3>
          <p>Slice your own images and download it.</p>
        </div>

        <div class="form">
          <form action="" method="post" role="form" class="php-email-form" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group col-md-4" >
                <label for="fileToUpload">Select image for slice</label>
                <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                <?php echo form_error('fileToUpload')?>
              </div>
              
              <div class="form-group col-md-3" >
                <label for="slice_cnt">Number of slices</label>
                <select name="slice_cnt" id="slice_cnt" class="form-control">
                    <option value="2">2</option>
                    <option value="4" selected="">4</option>
                    <option value="6">6</option>
                    <option value="8">8</option>
                    <option value="10">10</option>
                </select>
              </div>
              
              <div class="form-group col-md-3" >
                <label for="slice_type">Type</label>
                <select name="slice_type" id="slice_type" class="form-control">
                    <option value="0">Horizontal</option>
                    <option value="1">Vertical</option>
                    <option value="2" selected="">Grid</option>
                </select>
              </div>
              
              <div class="form-group col-md-2" >
              	  <label>.</label>
	              <div class="text-center">  <button type="submit">Slice</button></div>	  
              </div>
              
            </div>
            
          </form>
        </div>

      </div>
    </section><!-- End Contact Section -->

    <!-- ======= Team Section ======= -->
    <section id="team">
      <div class="container">
        
        <div class="row">

          <div class="col-md-6">
            <h3>Original Image</h3>
            <?php if(isset($original_img)){
            		echo $original_img;
            }else{
	            echo '<img src="'.().'images/original_preview.png" class="Original Image" alt="">';
            }?>
          </div>
          
          <div class="col-md-6">
            <h3>Slices</h3>
            
            <?php if(isset($slice_img)){
            		echo $slice_img;
            }else{
	            echo '<img src="'.().'images/crop_preview.png" class="Original Image" alt="">';
            }?>
            
            
           <?php if(isset($filenameuniq)){?>
            <br>
            <br>
            <form action="<?php echo base_url()?>welcome/download_file" method="post" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $filenameuniq?>" name="img_name">
                <input type="hidden" value="<?php echo $filenameuniq.'_original.'.$imageFileType;?>" name="original_file">
                <input type="submit" class="btn btn-success" value="Download Image" name="download">
            </form>
            <?php }?>
            
          </div>
          
        </div>

      </div>
    </section><!-- End Team Section -->
    

  </main><!-- End #main -->

  

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <!-- Uncomment below i you want to use a preloader -->
  <!-- <div id="preloader"></div> -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
	