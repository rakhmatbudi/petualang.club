<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-C0XQV9V35T"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-C0XQV9V35T');
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MCT2V42S');</script>
    <!-- End Google Tag Manager -->
    
    
    <meta charset="utf-8">
    <title>PetuAlang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- css -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap-responsive.css" rel="stylesheet" />
    <link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="css/jcarousel.css" rel="stylesheet" />
    <link href="css/flexslider.css" rel="stylesheet" />
    <link href="css/slitslider.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <!-- Theme skin -->
    <link id="t-colors" href="skins/default.css" rel="stylesheet" />
    <!-- boxed bg -->
    <link id="bodybg" href="bodybg/bg1.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" href="ico/favicon.png" />
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MCT2V42S"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    <div id="wrapper">
    	<!-- start header -->
    	<header>
    	<div class="container">
    	    <div class="row" style="text-align: center;">
    	        
    				<div class="logo">
    					<a href="index.php"><img src="img/logo.png" alt="" class="logo" /></a>
    				</div>
    		</div>
    	</div>
    	</header>
    	<!-- end header -->
    	<section id="featured">
    	<!-- start slider -->
    	<div id="slider" class="sl-slider-wrapper demo-2">
    		<div class="sl-slider">
    			<div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
    				<div class="sl-slide-inner">
    					<div class="bg-img bg-img-1">
    					</div>
    					<h2>Petu<strong>A</strong>lang</h2>
    					<blockquote>
    						<p>
    							Tidak ada yang lebih indah dari seseorang yang berusaha keras untuk membuat hidup indah bagi orang lain.
    						</p>
    						<cite>Mandy Hale</cite>
    					</blockquote>
    				</div>
    			</div>		
    		</div>
    	</div>
    	<!-- /slider-wrapper -->
    	<!-- end slider -->
    	</section>
    	<section class="callaction">
        	<div class="container">
        	    <!-- ANNOUNCEMENT SECTION (JUDUL) -->
        		<div class="row">
        			<div class="span12">
        				<div class="big-cta mx-auto">
        					<div class="cta-text mx-auto">
        						<h3><span class="highlight"><strong>Pengumuman</strong></span></h3>
        					</div>
        				</div>
        			</div>
        		</div>
        		<div class="row">
        			<div class="span12">
        				<div id="carouselExampleIndicators" class="carousel slide">
                          <div class="carousel-indicators">
                                <?php
                        			// Getting Activities			       
                                    $url = 'https://api.petualang.club';
                                    $request_url = $url . '/announcements?club=1';
                                    $curl = curl_init($request_url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, [
                                      'X-RapidAPI-Host: kvstore.p.rapidapi.com',
                                      'X-RapidAPI-Key: 7xxxxxxxxxxxxxxxxxxxxxxx',
                                      'Content-Type: application/json'
                                    ]);
                                    
                                    $response = json_decode(curl_exec($curl), true);
                                    curl_close($curl);
                        
                                    $menuitem = 0;
                                    $act = "";
                                    $announcmentFolder = "img/announcement/";
                                    foreach ($response["data"] as $item) {
                                        if ($menuitem == 0) {
                                            $act = " class=\"active\" ";
                                        } else {
                                            $act = "";
                                        }
                                        echo "<button type=\"button\" data-bs-target=\"#carouselExampleIndicators\" data-bs-slide-to=\"" . $menuitem . "\"" .  $act . " aria-current=\"true\" aria-label=\"" . $item["name"] . "\"></button>";
                                        $menuitem = $menuitem + 1;
                                    }
                                ?>
                                    
                          </div>
                          <div class="carousel-inner">
                              <?php
                                    $menuitem = 0;
                                    $act = "";
                                    $announcmentFolder = "img/announcement/";
                                    foreach ($response["data"] as $item) {
                                        if ($menuitem == 0) {
                                            $act = " active ";
                                        } else {
                                            $act = "";
                                        }
                                        echo "<div class=\"carousel-item" . $act . "\">";
                                        echo "<img src=\"" . $announcmentFolder.$item["image"] . "\" class=\"d-block w-100\" alt=\"" . $item["name"] . "\">";
                                        echo "</div>";
                                        $menuitem = $menuitem + 1;
                                    }
                              ?>
                            
                          </div>
                          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
        			</div>
        		</div>
    	        <div class="row">
        			<div class="span12">
        				<div class="solidline">
        				</div>
        			</div>
        		</div>
        		<!-- end divider -->
        	    <div class="row" style="text-align: center;">
        			<div class="span12" id="about">
        				<img src="img/petualang.jpg" alt="" />
        			</div>
        		</div>
        	    <div class="row">
        			<div class="span12">
        				<div class="big-cta">
        					<div class="cta-text">
        						<h3>Tentang <span class="highlight"><strong> Petualang</strong></span></h3>
        					</div>
        				</div>
        			</div>
        		</div>
        		 <div class="row" style="text-align: center;">
        			<div class="span12" id="about">
        				<p style="text-align:left">
        					<strong>PETUALANG</strong> merupakan Forum yang bergerak dalam bidang Sosial dan BerpetuAlang 
Kemanusiaan, yang bermarkas di Cileungsi, Bogor.   PETUALANG Berdiri 18 Juli 2018 Basic awal Komunitas kendaraan yg suka ber PetuAlang dan berbagi ke pelosok negeri pencetus Awal om Giok Raya  sekaligus sebagai foundernya. 
Berbagai kegiatan sosial kemanusian kita ikut Terjun dan beberapa agenda tahunan yg PetuAlang jalankan sehingga pada pasca Semeru erupsi terbentuk lah PetuAlang Rescue, Berawal dari kesamaan hobby dalam
dunia otomotif, off-road dan on-the-road. Banyak program yg kita lakukan  TQTQ, tanam pohon,Dapur umum, Rescue Sar, Donor Darah, dll Kegiatan PETUALANG ini menjangkau ke daerah
pelosok negri dan melihat banyak daerah yang tertinggal, Menjadikan dan Mewujudkan
Silaturahmi dalam jaringan komunitas, tingkat nasional, pendanaan, jejaring dan kerelawanan
untuk memperjuangkan kehidupan yang sejahtera, adil dan bermartabat bagi semua orang, serta menolong antar sesama. 
kerangka hak-hak asasi manusia dan pemeliharaan alam yang berkelanjutan. 
</p>
<p style="text-align:left">
                            Tujuan didirikannya PETUALANG Rescue:
                        </p>
                        <ul style="text-align:left">
                            <li style="text-align:left"><i class="bi bi-check2-all"></i> Menghimpun sumber daya dalam bentuk dana, jejaring dan kerelawanan,
    mengelolanya secara terbuka, bertanggungjawab dan berkelanjutan.</li>
                            <li style="text-align:left"><i class="bi bi-check2-all"></i> Memberikan dukungan sumber daya strategis pada saat-saat penting dalam
    perkembangan lembaga-lembaga yang bekerja untuk membuka akses dan pelibatan
    warga untuk memecahkan masalah disekitarnya. .</li>
                            <li style="text-align:left"><i class="bi bi-check2-all"></i> Membangun kemitraan untuk mendorong lahir dan tumbuhnya Jaringan baru
    komunitas di tingkat daerah.</li>
                            <li style="text-align:left"><i class="bi bi-check2-all"></i> Menyediakan dukungan dana emergensi untuk mendukung inisiatif-inisiatif darurat
    untuk menyikapi situasi genting yang mengancam. </li>
                            <li style="text-align:left"><i class="bi bi-check2-all"></i> Bergerak dalam emergency response kebencanaan dan recovery . </li>
                          </ul>
</p>
<p>
PETUALANG mempunyai dukungan legal dengan akta Pendirian No.339 tanggal 08 Desember 2020, Notaris Sugeng Purnawan
SH. Pengesahan Kemenkumham SK No. AHU 0024777A.H.01.04 thn 2020. Tanda Daftar
Yayasan No. 460/022-02/Bid.Dayasos/2021. NPWP 96.837.148.4-453.000.
Didasari oleh kepedulian para pengagas yg Digagas oleh Giok Raya  terhadap keadaan masyarakat Indonesia yang di berbagai
sisi masih memerlukan perhatian, maka PetuAlang  untuk berpartisipasi aktif dan turut peduli terhadap keadaan tersebut.
Kegiatan kami melakukan assessment dan bantuan darurat dalam berbagai bencana,
menindaklanjuti dengan berbagai kegiatan operasi pemulihan bencana, seperti membangun
Huntara (Hunian Sementara) bagi para penyintas yg memerlukan, membangun fasilitas
umum, seperti renovasi masjid/musholla , sekolah , lingkungan dan pemulihan trauma kepada
para penyintas bencana dan kegiatan pendukung lainnya.
<br>
Di masa landai, kami melakukan kegiatan santunan kepada saudara – saudara kita dhuafa,
membangun Rumpin (Rumah Impian), membuka perpustakaan umum di area yg memerlukan,
serta edukasi kepada anak terlantar. Dalam beraktifitas, kami bersinergi dengan berbagai
komunitas dan lembaga swadaya masyarakat dalam melaksanakan kegiatan - kegiatan kami.
Sumber dana untuk kegiatan kegiatan yang dilakukan berasal dari  hasil penjualan
merchandise yang kami produksi. Disamping itu kami menggalang dana dari koorporasi
melalui dana program Corporate Social Responsibility (CSR), sumbangan dari lembaga atau
perorangan serta simpatisan yang selama ini telah bersinergi positif bersama kami. 
Demikianlah selayang pandang PetuAlang  Semoga kehadiran dan kiprah
kami dapat selalu  Bergerak Bersama Berkarya Berguna 
                        </p>
                        
        			</div>
        		</div>
            </div>
    	</section>
    	<section id="content">
    	    <div class="container">
    	        
    	        
    	        <!-- REKENING SECTION (JUDUL) -->
        		<div class="row">
        			<div class="span12">
        				<div class="big-cta">
        					<div class="cta-text">
        						<h3>Mari berkontribusi. <span class="highlight"><strong>Sekecil apapun,</strong></span> itu berharga!</h3>
        					</div>
        				</div>
        			</div>
        		</div>
        	
        	    <!-- REKENING SECTION -->
    		    <div class="row">
    			<div class="span12">
    				<div class="row">
    				    <div class="card aligncenter mx-auto" style="width: 25rem;">
                          <div class="card-body">
                            <h5 class="card-title">Rekening Utama Petualang</h5>
                            <p class="card-text"><strong>Bank Nagari</strong></p>
                            <p class="card-text">No Rek: 2600.0210.18071-8</p>
                            <p class="card-text">Nama: PETUALANG PEDULI KEMANUSIAAN</p>
                          </div>
                        </div>
                        <div class="card aligncenter mx-auto" style="width: 25rem;">
                          <div class="card-body">
                            <h5 class="card-title">Rekening Peduli Palestina</h5>
                            <p class="card-text"><strong>Bank Nagari</strong></p>
                            <p class="card-text">No Rek: 2600.0210.18097-0</p>
                            <p class="card-text">Nama: PETUALANG PEDULI PALESTINA</p>
                          </div>
                        </div>
    				</div>
    			</div>
    		</div>
    		    <!-- divider -->
        		<div class="row">
        			<div class="span12">
        				<div class="solidline">
        				</div>
        			</div>
        		</div>
        		<!-- end divider -->
    
                <div class="row" style="text-align: center;">
        			<div class="span12">
        				<div class="big-cta">
        					<div class="cta-text">
        						<h3>Kontak <span class="highlight"><strong> PetuAlang</strong></span> di seluruh Indonesia</h3>
        					</div>
        				</div>
        			</div>
        		</div>
        		<div class="row" style="text-align: center;">
        			<div class="span12">
        				<div class="pricing-box-alt span12">
        					<div class="pricing-terms">
        						<h6>Mako</h6>
        					</div>
        					<div class="pricing-content">
        						<ul>
        							<li>1&2 Jabodetabek <a href="http://wa.me/6208121335329">Giox Raya</a></li>
        							<li>3 Tegal <a href="http://wa.me/6287874000070">Slenteng</a></li>
        							<li>4 Semarang raya <a href="http://wa.me/6281228811400">Didit</a></li>
        							<li>5 Jepara <a href="http://wa.me/6281325239976">Endik</a></li>
        							<li>6 Mojokerto <a href="http://wa.me/6282139976929">Abdul</a></li>
        							<li>7 Lampung <a href="http://wa.me/6281379303405">Anwar</a></li>
        							<li>8 Pekan Baru <a href="http://wa.me/628126510329">Sandy</a></li>
        							<li>9 Batang <a href="http://wa.me/6285290525719">Handoko</a></li>
        							<li>10 Bali <a href="http://wa.me/6285717808181">Aris</a></li>
        							<li>11 Bandung <a href="http://wa.me/6285793474948">Anis</a></li>
        							<li>12 Medan <a href="http://wa.me/62819898397">Yasin</a></li>
        							<li>13 Malang <a href="http://wa.me/628113612607">Tedja</a></li>
        							<li>14 Manado <a href="http://wa.me/62811450944">Sammy</a></li>
        							<li>15 Karawang <a href="http://wa.me/6283156984916">Ryan</a></li>
        							<li>16 Jogja</li>
        							<li>17 Cirebon <a href="http://wa.me/6285974178708">Zim</a></li>
        							<li>18 Garut</li>
        							<li>19 Magelang <a href="http://wa.me/6281329021793">Warih</a></li>
        							<li>20 Jayapura <a href="http://wa.me/6281340998233">Krisna</a></li>
        							<li>21 Purworejo <a href="http://wa.me/6281325225280">Genggong</a></li>
        							<li>22 Bangka Belitung <a href="whttp://a.me/6285273236665">Amirulloh</a></li>
        							<li>23 Palembang <a href="http://wa.me/81367260256">Hannato</a></li>
        							<li>24 Solo raya (KarangAnyar) <a href="http://wa.me/6281239898193">Ogik</a></li>
        							<li>25 Cilegon <a href="http://wa.me/6281289903698">Agus Tani</a></li>
        							<li>26 Sibolga <a href="http://wa.me/62811626282">Amiin</a></li>
        							<li>27 Kupang <a href="http://wa.me/6282237657690">Robert</a></li>
        							<li>28 Sukabumi <a href="http://wa.me/6285691104144">Bams</a></li>
        							<li>29 Cianjur <a href="http://wa.me/6285186659909">Ivan</a></li>
        							<li>30 Pangkalan Bun <a href="http://wa.me/6285249803467">Anggie</a></li>
        							<li>31 Banda Aceh <a href="http://wa.me/6285219498228">Hendra</a></li>
        							<li>32 Mataram <a href="http://wa.me/6281250298444">Fatur</a></li>
        							<li>33 Pontianak <a href="http://wa.me/6285332943373">Roy</a></li>
        							<li>34 Tangsel <a href="http://wa.me/6281290438552">Taufik</a></li>
        							<li>35 Banjarmasin <a href="http://wa.me/628118989081">Anca</a></li>
        						</ul>
        					</div>
        				</div>
        			</div>
        		</div>
    		
    		
    		<div class="row" style="text-align: center;">
    			<div class="span12">
    				<div class="big-cta">
    					<div class="cta-text">
    						<h3>Kegiatan <span class="highlight"><strong> Petualang</strong></span></h3>
    					</div>
    				</div>
    			</div>
    		</div>
    		<?php
    			// Getting Activities			       
                $url = 'https://api.petualang.club';
                $request_url = $url . '/activities?club=1';
                $curl = curl_init($request_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                  'X-RapidAPI-Host: kvstore.p.rapidapi.com',
                  'X-RapidAPI-Key: 7xxxxxxxxxxxxxxxxxxxxxxx',
                  'Content-Type: application/json'
                ]);
                
                $response = json_decode(curl_exec($curl), true);
                curl_close($curl);
                
                // Getting Activities Photos
                $url = 'https://api.petualang.club';
                $request_url = $url . '/activityPhotos?club=1';
                $curl = curl_init($request_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                  'X-RapidAPI-Host: kvstore.p.rapidapi.com',
                  'X-RapidAPI-Key: 7xxxxxxxxxxxxxxxxxxxxxxx',
                  'Content-Type: application/json'
                ]);
                
                $responsePhoto = json_decode(curl_exec($curl), true);
                curl_close($curl);
    
                $photoFolder = 'photo/';
                foreach ($response["data"] as $item) {
                  echo ' <div class="row" style="text-align: center;"> ';
        		  echo '    <div class="span12"> ';
                  echo '         <h6>' . $item["activities_name"] . '</h6> ';
                  echo '     </div>';
                  echo ' </div>';
                  echo ' <div class="row">';
                  echo '    <div class="span12">';
                  echo '       <div class="row">';
                  echo '          <section id="projects">';
                  echo '	        <ul id="thumbs" class="portfolio">';
                  
                  foreach ($responsePhoto["data"] as $itemPhoto) {
                      if ($itemPhoto["activity_id"] == $item["activity_id"]) {
                         echo '<li class="item-thumbs span3 design" data-id="id-0" data-type="web">';
                         echo '   <a class="hover-wrap fancybox" data-fancybox-group="gallery" title="' . $item["activities_name"] . '" href="' . $photoFolder.rawurlencode($itemPhoto["path"]) . '">';
                         echo '      <span class="overlay-img"></span>';
    				 	 echo '      <span class="overlay-img-thumb font-icon-plus"></span>';
    				 	 echo '   </a>';
    				 	 echo '   <img src="' . $photoFolder.rawurlencode($itemPhoto["path"]) . '" alt="' . $itemPhoto["description"] . '">';
    					 echo '</li>';
                      }
                      else
                      {
                          //echo "<br>ItemPhoto: " . $itemPhoto["activity_id"] . " VS " . "Item: " .  $item["activity_id"];
                      }
                  }
                  echo '            </url>';
                  echo '          </section>';
                  echo '        </div>';
                  echo '      </div>';
                  echo '   </div>';
                }
            ?>
    		
    		<!-- End Portfolio Projects -->
    		<!-- divider -->
    		<div class="row">
    			<div class="span12">
    				<div class="solidline">
    				</div>
    			</div>
    		</div>
    	</div>
    	</section>
    	<section id="bottom">
    	<div class="container">
    		<div class="row">
    			<div class="span12">
    				<div class="aligncenter">
    					<div id="twitter-wrapper">
    						<div id="twitter">
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    	</section>
    	<footer>
    	<div class="container">
    		<div class="row">
    			<div class="span3">
    				<div class="widget">
    					<h5 class="widgetheading">Browse pages</h5>
    					<ul class="link-list">
    						<li><a href="#">Tentang Kami</a></li>
    						<li><a href="#">Kegiatan</a></li>
    						<li><a href="#">Core Team</a></li>
    					</ul>
    				</div>
    			</div>
    			<div class="span3">
    				<div class="widget">
    					<h5 class="widgetheading">Important stuff</h5>
    					<ul class="link-list">
    						<li><a href="#">Press release</a></li>
    						<li><a href="#">Terms and conditions</a></li>
    						<li><a href="#">Privacy policy</a></li>
    					</ul>
    				</div>
    			</div>
    			<div class="span3">
    				<div class="widget">
    					<h5 class="widgetheading">Hubungi Kami</h5>
    					<address>
    					<strong>Mako Charlie</strong><br>
    					 Griya Alam Sentosa Blok N1 no 1<br>
    					 Cileungsi</address>
    					<p>
    						<i class="icon-phone"></i> 0812-1335-329 <br>
    						<i class="icon-envelope-alt"></i> info@petualang.club
    					</p>
    				</div>
    			</div>
    		</div>
    	</div>
    	<div id="sub-footer">
    		<div class="container">
    			<div class="row">
    				<div class="span6">
    					<div class="copyright">
    						<p>
    
    							<span>&copy; Designed by </span><a href="http://google.com" target="_blank">Serendipity Studio</a>
    						</p>
    					</div>
    				</div>
    				<div class="span6">
    					<ul class="social-network">
    						<li><a href="#" data-placement="bottom" title="Facebook"><i class="icon-facebook icon-square"></i></a></li>
    						<li><a href="#" data-placement="bottom" title="Twitter"><i class="icon-twitter icon-square"></i></a></li>
    						<li><a href="#" data-placement="bottom" title="Linkedin"><i class="icon-linkedin icon-square"></i></a></li>
    						<li><a href="#" data-placement="bottom" title="Pinterest"><i class="icon-pinterest icon-square"></i></a></li>
    						<li><a href="#" data-placement="bottom" title="Google plus"><i class="icon-google-plus icon-square"></i></a></li>
    					</ul>
    				</div>
    			</div>
    		</div>
    	</div>
    	</footer>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>