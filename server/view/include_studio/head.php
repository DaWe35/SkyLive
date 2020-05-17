<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Decentralized live streams on Sia Skynet">
<meta name="author" content="DaWe">
<meta property="og:image" content="<?= URL ?>assets/logos/logo1.jpg" />

<title>SkyLive</title>

<!-- Bootstrap core CSS -->
<link href="assets/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap core JavaScript -->
<script src="assets/jquery.min.js"></script>

<script>
  function printDateTime(datetime) {
    var evtm = new Date(parseInt(datetime*1000)).toLocaleString([], {year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute:'2-digit'})
    document.write(evtm)
  }
</script>



<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

<style>
body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
.w3-row-padding img {margin-bottom: 12px}
/* Set the width of the sidebar to 120px */
.w3-sidebar {width: 120px;background: #222;}
/* Add a left margin to the "page content" that matches the width of the sidebar (120px) */
#main {margin-left: 120px}
/* Remove margins from "page content" on small screens */
@media only screen and (max-width: 600px) {#main {margin-left: 0}}
</style>