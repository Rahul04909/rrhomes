<!-- includes/header.php -->
<link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/header.css">

<!-- Slim Top Bar -->
<div class="top-bar" id="top-bar">
    <div class="top-bar-container">
        <div class="top-bar-left">
            <span>Free Consultation</span>
            <span class="divider">|</span>
            <span>Luxury Living</span>
            <span class="divider">|</span>
            <span>Call: +91 98765 43210</span>
        </div>
        <div class="top-bar-right">
            <a href="#">Help</a>
            <span class="divider">|</span>
            <a href="#">Sign In</a>
        </div>
    </div>
</div>

<header id="main-header" class="header">
    <div class="header-container">

        <!-- Navigation Menu -->
        <nav class="main-nav" id="main-nav">
            <div class="nav-left-items">
                <a href="index.php" class="active">HOME</a>
                <a href="../../about-us.php">ABOUT US</a>
                <a href="#">PROJECTS</a>
            </div>

            <div class="nav-gap"></div>

            <div class="nav-right-items">
                <a href="#">SPECIFICATIONS</a>
                <a href="../../contact-us.php">CONTACT US</a>
            </div>
        </nav>

        <!-- Centered Logo (Absolute) -->
        <div class="logo-container" id="logo-container">
            <a href="index.php">
                <img src="assets/rr-home-logo.png" alt="RR Homes Logo" id="logo-image">
            </a>
        </div>

        <!-- Right Side CTA -->
        <div class="header-buttons" id="header-buttons">
            <a href="tel:+919876543210" class="btn btn-outline">
                <i class="fa-solid fa-phone"></i>
            </a>
            <a href="#" class="btn btn-primary">Enquire Now</a>
        </div>

        <!-- Mobile Menu Toggle -->
        <div class="mobile-toggle" id="mobile-toggle">
            <i class="fa-solid fa-bars"></i>
        </div>
    </div>
</header>

<!-- Mobile Navigation -->
<div class="mobile-nav" id="mobile-nav">
    <div class="mobile-nav-close" id="mobile-nav-close">
        <i class="fa-solid fa-times"></i>
    </div>
    <div class="mobile-logo">
        <img src="assets/rr-home-logo.png" alt="RR Homes Logo">
    </div>
    <ul class="mobile-menu-list">
        <li><a href="index.php">HOME</a></li>
        <li><a href="#">ABOUT US</a></li>
        <li><a href="#">PROJECTS</a></li>
        <li><a href="#">SPECIFICATIONS</a></li>
        <li><a href="#">CONTACT US</a></li>
    </ul>
    <div class="mobile-actions">
        <a href="tel:+919876543210" class="btn btn-outline mobile-btn"><i class="fa-solid fa-phone"></i> +91 98765
            43210</a>
        <a href="#" class="btn btn-primary mobile-btn">Enquire Now</a>
    </div>
</div>

<script>
    // Scroll Event for Dynamic Header
    document.addEventListener("DOMContentLoaded", () => {
        const header = document.getElementById("main-header");
        const topBar = document.getElementById("top-bar");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 40) {
                header.classList.add("scrolled");
                topBar.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
                topBar.classList.remove("scrolled");
            }
        });

        // Mobile Menu Toggle
        const toggleBtn = document.getElementById("mobile-toggle");
        const closeBtn = document.getElementById("mobile-nav-close");
        const mobileNav = document.getElementById("mobile-nav");

        toggleBtn.addEventListener("click", () => {
            mobileNav.classList.add("active");
            document.body.style.overflow = 'hidden';
        });

        closeBtn.addEventListener("click", () => {
            mobileNav.classList.remove("active");
            document.body.style.overflow = 'auto';
        });
    });
</script>