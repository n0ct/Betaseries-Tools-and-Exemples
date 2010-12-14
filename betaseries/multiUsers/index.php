<?php  require_once(dirname(__FILE__).'/config/config_betaseries.php'); ?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>View Exemple</title>
	<link rel="shortcut icon" href="images/favicon.png" />
	<link rel="stylesheet" type="text/css" href="<?=constant("FOLDER_PATH")?>/css/index.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?=constant("FOLDER_PATH")?>/css/index_print.css" media="print" />
	<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="/donnees/canvas/liquid-particles/liquid-particles.js"></script> 
</head>
<body>
<div id="wrapper"><!-- #wrapper -->
	<nav><!-- top nav -->
		<div class="menu">
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Link 2</a></li>
				<li><a href="#">Link 3</a></li>
			</ul>
		</div>
	</nav><!-- end of top nav -->
	<header><!-- header -->
		<h1><a href="#">Betaseries.com API > PHP Comparaison</a></h1>
		<h2>made by Benjamin Boulaud (ben.nbld&lt;at&gt;gmail&lt;dot&gt;com)</h2>
	</header><!-- end of header -->
	<section id="main"><!-- #main content area -->
			<section id="content"><!-- #content -->
				<div id="betaseries" class="betaseries">
					<section>
						<article>
							<br /><br />
							<center>
							<a href="<?=$_SERVER['HTTP_HOST'];?>" onclick="betaseries(this.href);return false;">
								Afficher la vue multi-utilisateurs.
							</a>
							</center>
							<br /><br />
						</article>
					<section>
				</div>
			</section>
		</section><!-- end of #content -->
	</section><!-- end of #main content -->
	<footer> 
		<section id="footer-area"> 
 
			<section id="footer-outer-block"> 
					<aside class="footer-segment"> 
							<h4>Friends</h4> 
								<ul> 
									<li><a href="#">one linkylink</a></li> 
									<li><a href="#">two linkylinks</a></li> 
									<li><a href="#">three linkylinks</a></li> 
									<li><a href="#">four linkylinks</a></li> 
									<li><a href="#">five linkylinks</a></li> 
								</ul> 
					</aside><!-- end of #first footer segment --> 
 
					<aside class="footer-segment"> 
							<h4>Awesome Stuff</h4> 
								<ul> 
									<li><a href="#">one linkylink</a></li> 
									<li><a href="#">two linkylinks</a></li> 
									<li><a href="#">three linkylinks</a></li> 
									<li><a href="#">four linkylinks</a></li> 
									<li><a href="#">five linkylinks</a></li> 
								</ul> 
					</aside><!-- end of #second footer segment --> 
 
					<aside class="footer-segment"> 
							<h4>Coolness</h4> 
								<ul> 
									<li><a href="#">one linkylink</a></li> 
									<li><a href="#">two linkylinks</a></li> 
									<li><a href="#">three linkylinks</a></li> 
									<li><a href="#">four linkylinks</a></li> 
									<li><a href="#">five linkylinks</a></li> 
								</ul> 
					</aside><!-- end of #third footer segment --> 
					
					<aside class="footer-segment"> 
							<h4>Blahdyblah</h4> 
								<p>Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta.</p> 
					</aside><!-- end of #fourth footer segment --> 
 
			</section><!-- end of footer-outer-block --> 
 
		</section><!-- end of footer-area --> 
	</footer> 
</div><!-- #wrapper -->
<!-- Free template created by http://freehtml5templates.com --> 
<script type="text/javascript">
	function betaseries()
	{
		$('#betaseries').html('Chargement en cours ...');
		$.ajax({
		  url: '<?=constant("FOLDER_PATH")?>/views/accordion.php',
		  success: function(data) {
		    $('#betaseries').html(data);
		  }
		});
	}
</script>
</body>
</html>
