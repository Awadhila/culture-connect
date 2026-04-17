<?php 
$pageTitle = "Home - CultureConnect";
include 'includes/header.php'; ?>

<main class="pt-0"> 
    <div class="container px-3"> 
        
        <header class="row align-items-start mb-0 hero-section">
            
            <div class="col-lg-4 pt-4">
                <p class="text-dark m-0 project-desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
                    ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
                    ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet.
                </p>
            </div>
            
            <div class="col-lg-8 text-end">
                <h1 class="fw-bold hero-text m-0" style="margin-top: -10px !important;">
                    CULTURE<br>CONNECT
                </h1>
            </div>
        </header>

        <section class="row mt-2 align-items-start">
            <div class="col-lg-8">
                <div class="project-container position-relative">
                    <img src="assets/img/project-group.jpg" class="img-fluid w-100" alt="Project Group">
                    
                    <div class="d-flex button-overlay">
                        <a href="#" class="btn btn-black-square me-3">Explore</a>
                        <a href="#" class="btn btn-blue-square d-flex justify-content-between align-items-center">
                            Our Project <span class="ms-4 fs-4">→</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ps-lg-5 text-end">
                <h3 class="fw-bold mb-3 site-url">www.cultureconnect.com</h3>
                <p class="text-dark text-start project-desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                    labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco 
                    laboris nisi ut aliquip ex ea commodo consequat.
                    <br></br>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                    labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco 
                    laboris nisi ut aliquip ex ea commodo consequat.
                </p>
            </div>
        </section>
    </div>
    <section class="services-section py-5">
        <div class="container">
            <h2 class="display-4 fw-bold mb-5" style="color: #72b1e1;">Services</h2>
            
            <div class="row g-4 d-flex align-items-stretch">
                <div class="col-lg-4 d-flex flex-column">
                    <h4 class="service-category-title mb-4">Craft & DIY Trades</h4>
                    <div class="category-wrapper flex-grow-1">
                        <div class="row g-2 h-100">
                            <div class="col-6 mb-2">
                                <div class="service-card">
                                    <div class="image-box"><img src="assets/img/services/woodworking.jpg" alt="Woodworking"></div>
                                    <p class="service-text-label">Woodworking</p>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="service-card">
                                    <div class="image-box"><img src="assets/img/services/textile.jpg" alt="Textile Arts"></div>
                                    <p class="service-text-label">Textile Arts</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="service-card">
                                    <div class="image-box wide"><img src="assets/img/services/jewelry.jpg" alt="Jewelry Making"></div>
                                    <p class="service-text-label">Jewelry Making</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-flex flex-column">
                    <h4 class="service-category-title mb-4">Culinary Arts & Flavor</h4>
                    <div class="category-wrapper flex-grow-1">
                        <div class="row g-2 h-100">
                            <div class="col-12 mb-2">
                                <div class="service-card">
                                    <div class="image-box wide"><img src="assets/img/services/baking.jpg" alt="Baking"></div>
                                    <p class="service-text-label">Baking & Pastry</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="service-card">
                                    <div class="image-box"><img src="assets/img/services/cuisines.jpg" alt="Global Cuisines"></div>
                                    <p class="service-text-label">Global Cuisines</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="service-card">
                                    <div class="image-box"><img src="assets/img/services/barista.jpg" alt="Barista Skills"></div>
                                    <p class="service-text-label">Barista Skills</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-flex flex-column">
                    <h4 class="service-category-title mb-4">Creative Workshops & Learning</h4>
                    <div class="category-wrapper flex-grow-1">
                        <div class="row g-2 h-100">
                            <div class="col-12 mb-2">
                                <div class="service-card">
                                    <div class="image-box wide"><img src="assets/img/services/writing.jpg" alt="Writing"></div>
                                    <p class="service-text-label">Creative Writing Workshop</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="service-card">
                                    <div class="image-box"><img src="assets/img/services/art.jpg" alt="Art Class"></div>
                                    <p class="service-text-label">Art Class</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="service-card">
                                    <div class="image-box"><img src="assets/img/services/music.jpg" alt="Music Lessons"></div>
                                    <p class="service-text-label">Music Lessons</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>