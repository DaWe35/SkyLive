<!-- Page Content -->
<div class="w3-padding-large" id="main">

  <!-- Contact Section -->
	<div class="w3-padding-64 w3-content w3-text-grey" id="contact">
		<p class="text-center">Upload live event details, generate stream token:</p>

		<form method="POST" class="new-stream-form" enctype="multipart/form-data">
			<p><input class="w3-input" type="text" placeholder="Title" name="title" required></p>
			<p><textarea class="w3-input" type="text" placeholder="Description" name="description" required></textarea></p>
			<p>Thumbnail: <input class="w3-input" type="file" placeholder="Thumbnail" name="file" required></p>
			<p>Start time: 
				<input class="w3-input" type="datetime-local" id="scheule_time_local" onchange="change_timestamp()" required>
				<input type="hidden" id="scheule_time" name="scheule_time" required>
			</p>

			<p>
				Visibility:
				<input type="radio" id="public" name="visibility" value="public" required>
				<label for="public">Public</label>
				<input type="radio" id="non-listed" name="visibility" value="non-listed">
				<label for="non-listed">Non-listed</label>
			</p>
			<p class="text-center">
				<button class="btn btn-grey4 btn-outline-light btn-lg" type="submit">
					<i class="fa fa-save"></i> Save details
				</button>
			</p>
		</form>
	<!-- End Contact Section -->
	</div>

<!-- END PAGE CONTENT -->
</div>

<script>
	var now = new Date();
	now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
	document.getElementById('scheule_time_local').value = now.toISOString().slice(0,16);
	change_timestamp()

	function change_timestamp() {
		let scheule_time_local = Date.parse(document.getElementById('scheule_time_local').value) / 1000;
		let scheule_time = scheule_time_local;
		document.getElementById('scheule_time').value = scheule_time;
	}
</script>