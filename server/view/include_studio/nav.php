<!-- Icon Bar (Sidebar - hidden on small screens) -->
<nav class="w3-sidebar w3-bar-block w3-small w3-hide-small w3-center">
  <!-- Avatar image in top left corner
  <img src="" style="width:100%"> -->
  <a href="<?= URL ?>" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
    <i class="fas fa-tv w3-xxlarge"></i>
    <p>SkyLive</p>
  </a>
  <a href="<?= URL ?>studio" class="w3-bar-item w3-button w3-padding-large <?= $displayPage == 'index' ? 'w3-black' : 'w3-hover-black' ?>">
    <i class="fas fa-satellite-dish w3-xxlarge"></i>
    <p>Studio</p>
  </a>
</nav>

<!-- Navbar on small screens (Hidden on medium and large screens) -->
<div class="w3-top w3-hide-large w3-hide-medium" id="myNavbar">
  <div class="w3-bar w3-black w3-opacity w3-hover-opacity-off w3-center w3-small">
    <a href="<?= URL ?>" class="w3-bar-item w3-button" style="width:25% !important">SkyLive</a>
    <a href="<?= URL ?>studio" class="w3-bar-item w3-button" style="width:25% !important">Studio</a>
  </div>
</div>