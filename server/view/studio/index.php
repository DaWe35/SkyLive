

<!-- Page Content -->
<div class="w3-padding-large" id="main">
  <!-- Header/Home -->
	<header class="w3-container w3-padding-32 w3-center text-white" id="home">
		<h1 class="w3-jumbo">SkyLive studio</h1>
		<!-- <p>Photographer and Web Designer.</p> -->
	</header>
	
	<!-- Portfolio Section -->
	<div class="w3-content text-white" id="photos">
		<a type="button" class="btn btn-outline-light btn-lg float-right" href="/studio/new-stream">+ Create new stream</a>
		<h2 class="text-muted">My streams</h2>
		<br>


		<!-- Grid for photos -->
		<div class="w3-row-padding div-table" style="margin:0 -16px"> <?php
		while ($row = $stmt_streams->fetch(PDO::FETCH_ASSOC)) { ?>
			<div class="video-row">
				<img class="video-col" src="<?= image_print($row['streamid']) ?>" alt="" />
				<div class="video-col">
					<h4><?= $row['title'] ?></h4>
					<p><?= $row['description'] ?></p>
				</div>
				
			</div> <?php
		}
		$stmt_streams = null; ?>
			
		<!-- End photo grid -->
		</div>
		
	<!-- End Portfolio Section -->
	</div>

<!-- END PAGE CONTENT -->
</div>