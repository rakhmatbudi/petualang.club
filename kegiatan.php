<!DOCTYPE html>
<?php include 'dbconnection.php';?>

<html lang="en">
<?php
  if (isset($_POST['btnSubmit']))
  {   
    // insert Activity
    $sqlInsert = "INSERT INTO activity (club_id, activities_name, activities_description) VALUES(1, '" . $_POST['txtName'] . "', '" . $_POST['txtDescription'] . "') returning activity_id";
  
    //echo $sqlInsert;
    $result = pg_query($conn, $sqlInsert);
    while ($rs = pg_fetch_object($result)) 
    {
        $new_id = $rs->activity_id;
    }

    //echo "<br><br>Last id = " . $new_id;
    //echo "<br><br>";
    //var_dump($_FILES["formFile"]);
    
    $files = $_FILES['formFile'];
    $file_count = count($files['name']);
    
    // validation
    $errors = [];
    //echo "<br><br>File Uploaded: ";
    for ($i = 0; $i < $file_count; $i++) {
        $filename = $files['name'][$i];
        $UPLOAD_DIR = 'photo';
        
        $sqlInsertPhoto = "INSERT INTO activity_photo (activity_id, path) VALUES(" . $new_id . ", '" . $filename . "')";
        $result = pg_query($conn, $sqlInsertPhoto);
        //echo "<br>" . $sqlInsertPhoto;
        
        //echo "<br>" . $filename;
        
        $tmp = $files['tmp_name'][$i];
        //echo " tmp = " . $tmp;
        //
        
        
        $uploaded_file = $filename;
        //echo " uploaded_file= " . $uploaded_file;
        // new filepath
        $filepath = './' . $UPLOAD_DIR . '/' . $uploaded_file;
        //echo " filepath= " . $filepath;
        
        $success = move_uploaded_file($tmp, $filepath);
        if(!$success) {
            echo "<br>" . "The file $filename was failed to move.";
        }
        else
        {
            echo "<br>" . $filename . " is successfully uploaded";
        }
    }
  }
?>

<head>
<meta charset="utf-8">
<title>Kegiatan Petualang</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="author" content="" />
<!-- css -->
<link href="css/bootstrap.css" rel="stylesheet" />
<link href="css/bootstrap-responsive.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<!-- Theme skin -->
<link href="skins/default.css" rel="stylesheet" />
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png" />
<link rel="shortcut icon" href="ico/favicon.png" />
</head>
<body>
<div id="wrapper">

	<section id="inner-headline">
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="inner-heading">
					<h2>Upload Kegiatan</h2>
				</div>
			</div>
		</div>
	</div>
	</section>
	<section id="content">
	
	<div class="container">
		<div class="row">
			<div class="span12">
			    <h4 class="d-none">Data kegiatan <strong> PetuaALang</strong></h4>
				<form class="form-horizontal" name="form1" id="form1" action="kegiatan.php" method="post" enctype="multipart/form-data">

					<div class="row">
						<div class="span4 field">
							<input type="text" name="txtName" id="txtName" placeholder="* Nama Kegiatan" data-rule="maxlen:4" data-msg="Please enter at least 4 chars" />
							<div class="validation">
							</div>
						</div>
						<div class="span12 margintop10 field">
							<textarea rows="6" name="txtDescription" id="txtDescription" class="input-block-level" placeholder="* Isi deskripsi kegiatan disiini..." data-rule="required" data-msg="Please write something"></textarea>
							<div class="validation">
							</div>
							
						</div>
						<div class="span12 field">
						    <div class="mb-3">
                              <label for="formFile" class="form-label">Pilih foto</label>
                              <input multiple class="form-control" type="file" id="formFile" name="formFile[]">
                            </div>
						</div>
						<div class="span12 field">
						    <p>
								<button class="btn btn-theme margintop10 pull-left" type="submit" name="btnSubmit">Submit</button>
								<span class="pull-right margintop20">Semua data harus diisi</span>
							</p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	</section>
</div>
<a href="#" class="scrollup"><i class="icon-chevron-up icon-square icon-32 active"></i></a>
<!-- javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jcarousel/jquery.jcarousel.min.js"></script>
<script src="js/jquery.fancybox.pack.js"></script>
<script src="js/jquery.fancybox-media.js"></script>
<script src="js/google-code-prettify/prettify.js"></script>
<script src="js/portfolio/jquery.quicksand.js"></script>
<script src="js/portfolio/setting.js"></script>
<script src="js/tweet/jquery.tweet.js"></script>
<script src="js/jquery.flexslider.js"></script>
<script src="js/jquery.nivo.slider.js"></script>
<script src="js/modernizr.custom.79639.js"></script>
<script src="js/jquery.ba-cond.min.js"></script>
<script src="js/jquery.slitslider.js"></script>
<script src="js/animate.js"></script>
<script src="js/custom.js"></script>
<script src="js/validate.js"></script>
</body>
</html>