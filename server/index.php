<?php
include 'config.php';
$player = 'https://siasky.net/GACBp1IOURtbxtEneB48Xo6ibidUtU0Us9U5oJMwZr8Ksg?stream=';

?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Decentralized live streams on Sia Skynet">
	<meta name="author" content="DaWe">
	<meta property="og:image" content="<?= URL ?>assets/logos/logo1.jpg" />

	<title>SkyLive</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="style.css" rel="stylesheet">
	<script>
		function printDateTime(datetime) {
			var evtm = new Date(parseInt(datetime*1000)).toLocaleString([], {year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute:'2-digit'})
			document.write(evtm)
		}
	</script>

</head>

<body>
	<!-- Header -->
	<header class="bg-gradient py-5">
		<div class="container h-100">
			<div class="row h-100 align-items-center">
				<div class="col-lg-9">
					<h1 class="display-4 text-white mt-5 mb-2 font-weight-bold">
						<button class='play'></button>
						SkyLive
					</h1>
					<p class="lead mb-5 text-white-50">Decentralized livestreams on Sia Skynet<br>
				</div>
				<div class="col-lg-3"> <?php
					if (isset($_GET['subscribed'])) { ?>
							<p class="lead text-white">Succesful subscription,<br> thank you!<br>
						</form> <?php
					} else { ?>
						<form id="subscribe" action="subscribe.php" method="post">
							<p class="lead text-white-50">Subscribe for newsletter<br>
							<div class="form-group">
								<input type="email" name="email" class="form-control" placeholder="Enter address">
							</div>
							<button type="submit" class="btn btn-primary float-right">Subscribe</button>
						</form> <?php
					} ?>
				</div>
			</div>
		</div>
	</header>

	<!-- Page Content -->
	<div class="container-fluid">
		<div class="row mt-5 mr-2 ml-2"> <?php

			$streamId = 'obws2';
			$event_time = 1589904000;
			$event_end_time = 1589914800; ?>
			<div class="col-md-3 mb-5">
				<div class="card h-100 shadow">
					<a href="<?= $player . $streamId?>" class="position-relative"> <?php
						date_default_timezone_set('UTC');
						$current = time();
						if ($current < $event_time) { ?>
							<div class="ribbon ribbon-green">Upcoming event (<script>printDateTime(<?= $event_time ?>)</script>)</div> <?php
						} else if ($current < $event_end_time) { ?>
							<div class="ribbon ribbon-red">On air</div> <?php
						} ?>
						<img class="card-img-top" src="thumbnails/obws2.png" alt="">
					</a>
					<div class="card-body">
						<a href="<?= $player . $streamId?>">
							<h4 class="card-title">The Decentralized Financial Crisis: Attacking DeFi</h4>
						</a>
						<div class="card-text">Details on <a href="https://www.meetup.com/Open-Blockchain-Workshop-Series/events/270437669/">Meetup</a></div>
					</div>
				</div>
			</div> <?php

			$streamId = 'workshop';
			$event_time = 1588870800;
			$event_end_time = 1588874400; ?>
			<div class="col-md-3 mb-5">
				<div class="card h-100 shadow">
					<a href="<?= $player . $streamId?>" class="position-relative"> <?php
						date_default_timezone_set('UTC');
						$current = time();
 						if ($current < $event_time) { ?>
							<div class="ribbon ribbon-green">Upcoming event (<script>printDateTime(1588870800)</script>)</div> <?php
						} else if ($current < $event_end_time) { ?>
							<div class="ribbon ribbon-red">On air</div> <?php
						} ?>
						<img class="card-img-top" src="thumbnails/skynet_dev_workshop.png" alt="">
					</a>
					<div class="card-body">
						<a href="<?= $player . $streamId?>">
							<h4 class="card-title">Skynet Workshop for Developers</h4>
						</a>
						<div class="card-text">Thursday at 1 pm ET (UTC 2020-05-07 17:00)</div>
					</div>
				</div>
			</div> <?php

			$streamId = 'podcast'; ?>
			<div class="col-md-3 mb-5">
				<div class="card h-100 shadow">
					<a href="<?= $player . $streamId?>" class="position-relative">
						<img class="card-img-top" src="thumbnails/podcast.png" alt="">
					</a>
					<div class="card-body">
						<a href="<?= $player . $streamId?>">
							<h4 class="card-title">Podcast</h4>
						</a>
						<div class="card-text">Sia's David Vorick: Outcompeting Amazon Web Services, a $35B Revenue Giant</div>
					</div>
				</div>
			</div> <?php

			$streamId = 'obws'; ?>
			<div class="col-md-3 mb-5">
				<div class="card h-100 shadow">
					<a href="<?= $player . $streamId?>" class="position-relative"> <?php
						date_default_timezone_set('UTC');
						$current = date('Y-m-d H:i', time());

 						if ($current < '2020-03-24 17:00') { ?>
							<div class="ribbon ribbon-green">Upcoming event (UTC 17:00)</div> <?php
						} else if ($current < '2020-03-24 20:00') { ?>
							<div class="ribbon ribbon-red">On air</div> <?php
						} ?>
						<img class="card-img-top" src="thumbnails/obws.png" alt="">
					</a>
					<div class="card-body">
						<a href="<?= $player . $streamId?>">
							<h4 class="card-title">Bitcoin's privacy-enhancing technologies</h4>
						</a>
						A hands-on workshop with David Molnar from Wasabi Wallet.<br>
						<a href="https://www.meetup.com/Open-Blockchain-Workshop-Series/events/269152809/" class="card-text">Event info</a><br>
					</div>
				</div>
			</div> <?php

			$streamId = 'long_test'; ?>
			<div class="col-md-3 mb-5">
				<div class="card h-100 shadow">
					<a href="<?= $player . $streamId?>" class="position-relative">
						<img class="card-img-top" src="thumbnails/long_test.png" alt="">
					</a>
					<div class="card-body">
						<a href="<?= $player . $streamId?>">
							<h4 class="card-title">3 hour live test</h4>
						</a>
						<div class="card-text">Just a boring 10s HLS chunk testing live with Minecraft & DJ show. After 2 hours <a href="https://github.com/DaWe35/SkyLive/issues/16" title="Open the issue">uploading slowed down</a>, and delay increased</div>
					</div>
				</div>
			</div> <?php

			$streamId = 'starlink'; ?>
			<div class="col-md-3 mb-5">
				<div class="card h-100 shadow">
					<a href="<?= $player . $streamId?>" class="position-relative">
						<img class="card-img-top" src="thumbnails/starlink.jpg" alt="">
					</a>
					<div class="card-body">
						<a href="<?= $player . $streamId?>">
							<h4 class="card-title">SpeceX Starlink mission</h4>
						</a>
						<div class="card-text">2020. march 18. restream</div>
					</div>
				</div>
			</div> <?php

			$streamId = 'skylive'; ?>
			<div class="col-md-3 mb-5">
				<div class="card h-100 shadow">
					<a href="<?= $player . $streamId?>" class="position-relative">
						<img class="card-img-top" src="thumbnails/first.jpg" alt="">
					</a>
					<div class="card-body">
						<a href="<?= $player . $streamId?>">
							<h4 class="card-title">First Live on Skynet!</h4>
						</a>
						<a href="https://siasky.net/CADUOqGUR0us09iZrSAAq6Qj5MrI2GrFqtdEiUKwkyZllA" class="card-text">Download in mp4</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /.row -->

	</div>
	<!-- /.container -->

	<!-- Footer -->
	<footer class="py-5 bg-dark">
		<div class="container">
			<p class="m-0 text-center text-white">
				<a href="https://minnit.chat/SkyLive" class="text-white">Chat</a>
				•
				<a href="https://github.com/DaWe35/SkyLive" class="text-white">GitHub</a>
			</p>
		</div>
		<!-- /.container -->
	</footer>

	<!-- Bootstrap core JavaScript -->
	<script src="assets/jquery.min.js"></script>
	<script src="assets/bootstrap.bundle.min.js"></script>

</body>

</html>
