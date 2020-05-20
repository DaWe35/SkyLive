

<!-- Page Content -->
<div class="w3-padding-large" id="main">
  <!-- Header/Home -->
	<header class="w3-container w3-padding-32 w3-center text-white" id="home">
		<h1 class="w3-jumbo">SkyLive studio</h1>
		<!-- <p>Photographer and Web Designer.</p> -->
	</header>
	
	<!-- Section -->
	<div class="w3-content text-white" id="photos">
		<a type="button" class="btn btn-grey4 btn-outline-light btn-lg float-right" href="/studio/new-stream">+ Create new stream</a>
		<h2 class="text-muted">My streams</h2>
		<br>


		<!-- Grid for photos -->
		<div class="w3-row-padding div-table" style="margin:0 -16px">
			<div class="video-body"><?php
				while ($row = $stmt_streams->fetch(PDO::FETCH_ASSOC)) { ?>
					<div class="video-row">
						<div class="video-cell">
							<a href="/player?s=<?= $row['streamid'] ?>">
								<img class="" src="<?= image_print($row['streamid'], 600) ?>" alt="" />
							</a>
						</div>
						<div class="video-cell">
							<a href="/player?s=<?= $row['streamid'] ?>">
								<h4><?= $row['title'] ?></h4>
							</a>
							<p><?= $row['description'] ?></p>
							<p>Token: 
								<span class="streams-token"><?= $row['token'] ?></span><span>...</span>
								<button class="btn btn-grey4 btn-sm" onclick="copy('<?= $row['token'] ?>')">Copy</button> <?php
								if ($row['started'] == 1 && $row['finished'] == 0) { ?>
									<button class="btn btn-danger btn-sm" onclick="finish_stream('<?= $row['streamid'] ?>', this)">Finish</button> <?php
								} ?>
							</p>
						</div>
						
					</div> <?php
				}
				$stmt_streams = null; ?>
			</div>
		<!-- End table -->
		</div>
		
	<!-- End Section -->
	</div>

<!-- END PAGE CONTENT -->
</div>

<script>
function copy(text) {
    let input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);
    input.select();
    let result = document.execCommand('copy');
    document.body.removeChild(input);
    return result;
}

function finish_stream(streamid, elem) {
	elem.disabled = true;
	var request = $.ajax({
		url: "/studio/finish_stream",
		type: "POST",
		data: {streamid: streamid},
		dataType: "html"
	});

	request.done(function(msg) {
		if (msg == 'ok') {
			elem.remove();
		} else {
			alert( "Request failed: " + msg );
		}
	});

	request.fail(function(jqXHR, textStatus) {
		alert( "Request failed: " + textStatus );
	});
}
</script>