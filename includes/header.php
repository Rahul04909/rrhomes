<?php
// Handle Modal Enquiry Submission
$header_enq_msg = '';
$show_modal = false;
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_header_enquiry'])) {
    require_once __DIR__ . '/../database/config.php';
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $source = "Header Quick Enquiry";
    
    $sql = "INSERT INTO enquiries (name, email, phone, message, source) VALUES ('$name', '$email', '$phone', '$message', '$source')";
    if(mysqli_query($conn, $sql)) {
        $header_enq_msg = "<div style='color:#28a745; background:rgba(40,167,69,0.1); padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; border-left:3px solid #28a745;'>Thank you! Our team will contact you soon.</div>";
    } else {
        $header_enq_msg = "<div style='color:#dc3545; background:rgba(220,53,69,0.1); padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; border-left:3px solid #dc3545;'>Error submitting enquiry.</div>";
    }
    $show_modal = true;
}

// Fetch Projects for Dropdown
$projects_menu = [];
if(file_exists(__DIR__ . '/../database/config.php')) {
    require_once __DIR__ . '/../database/config.php';
    $proj_sql = "SELECT id, title FROM projects ORDER BY id DESC";
    $proj_res = mysqli_query($conn, $proj_sql);
    if($proj_res) {
        while($p = mysqli_fetch_assoc($proj_res)){
            $projects_menu[] = $p;
        }
    }
}
?>
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
            <span>Call: +91 99711 99138</span>
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
                <a href="index.php" class="<?= strpos($_SERVER['PHP_SELF'], 'index.php') !== false ? 'active' : '' ?>">HOME</a>
                <a href="about-us.php" class="<?= strpos($_SERVER['PHP_SELF'], 'about-us.php') !== false ? 'active' : '' ?>">ABOUT US</a>
                
                <div class="nav-dropdown">
                    <a href="#" class="dropdown-toggle">PROJECTS <i class="fas fa-chevron-down" style="font-size: 0.8em; margin-left: 3px;"></i></a>
                    <div class="dropdown-content">
                        <?php foreach($projects_menu as $pm): ?>
                            <a href="project-details.php?id=<?= $pm['id'] ?>"><?= htmlspecialchars($pm['title']) ?></a>
                        <?php endforeach; ?>
                        <?php if(empty($projects_menu)): ?>
                            <a href="#">No projects available</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="contact-us.php" class="<?= strpos($_SERVER['PHP_SELF'], 'contact-us.php') !== false ? 'active' : '' ?>">CONTACT US</a>
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
            <a href="tel:+919971199138" class="btn btn-outline">
                <i class="fa-solid fa-phone"></i>
            </a>
            <button id="enquireBtn" class="btn btn-primary">Enquire Now</button>
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
        <li><a href="about-us.php">ABOUT US</a></li>
        <li class="mobile-dropdown-trigger">
            <a href="javascript:void(0)">PROJECTS <i class="fas fa-chevron-down"></i></a>
            <ul class="mobile-dropdown-content">
                <?php foreach($projects_menu as $pm): ?>
                    <li><a href="project-details.php?id=<?= $pm['id'] ?>"><?= htmlspecialchars($pm['title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li><a href="contact-us.php">CONTACT US</a></li>
    </ul>
    <div class="mobile-actions">
        <a href="tel:+919971199138" class="btn btn-outline mobile-btn"><i class="fa-solid fa-phone"></i> +91 99711 99138</a>
        <button id="mobileEnquireBtn" class="btn btn-primary mobile-btn">Enquire Now</button>
    </div>
</div>

<!-- Enquire Now Modal -->
<div id="headerEnquireModal" class="header-modal <?= $show_modal ? 'show' : '' ?>">
    <div class="header-modal-content">
        <span class="header-modal-close" id="headerModalClose">&times;</span>
        <h2 class="modal-title">Quick Enquiry</h2>
        <p class="modal-subtitle">Leave your details and we will get back to you shortly.</p>
        
        <?= $header_enq_msg ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="name" class="header-form-control" placeholder="Your Full Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="header-form-control" placeholder="Your Email Address" required>
            </div>
            <div class="form-group">
                <input type="tel" name="phone" class="header-form-control" placeholder="Your Phone Number" required>
            </div>
            <div class="form-group">
                <textarea name="message" class="header-form-control" placeholder="I am interested in..." rows="3" required></textarea>
            </div>
            <button type="submit" name="submit_header_enquiry" class="btn btn-primary w-100">Submit Enquiry</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Scroll Event for Dynamic Header
        const header = document.getElementById("main-header");
        const topBar = document.getElementById("top-bar");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 40) {
                header.classList.add("scrolled");
                if(topBar) topBar.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
                if(topBar) topBar.classList.remove("scrolled");
            }
        });

        // Mobile Menu Toggle
        const toggleBtn = document.getElementById("mobile-toggle");
        const closeBtn = document.getElementById("mobile-nav-close");
        const mobileNav = document.getElementById("mobile-nav");

        if(toggleBtn && closeBtn && mobileNav) {
            toggleBtn.addEventListener("click", () => {
                mobileNav.classList.add("active");
                document.body.style.overflow = 'hidden';
            });

            closeBtn.addEventListener("click", () => {
                mobileNav.classList.remove("active");
                document.body.style.overflow = 'auto';
            });
        }

        // Mobile Dropdown Toggle
        const mobileDropTriggers = document.querySelectorAll('.mobile-dropdown-trigger > a');
        mobileDropTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            });
        });

        // Modal Logic
        const modal = document.getElementById("headerEnquireModal");
        const openBtn = document.getElementById("enquireBtn");
        const mobileOpenBtn = document.getElementById("mobileEnquireBtn");
        const closeModalBtn = document.getElementById("headerModalClose");

        function openModal() {
            modal.classList.add("show");
            document.body.style.overflow = 'hidden'; // Prevent scrolling
            if(mobileNav) mobileNav.classList.remove("active"); // close mobile menu if open
        }

        function closeModal() {
            modal.classList.remove("show");
            document.body.style.overflow = 'auto';
        }

        if(openBtn) openBtn.addEventListener("click", openModal);
        if(mobileOpenBtn) mobileOpenBtn.addEventListener("click", openModal);
        if(closeModalBtn) closeModalBtn.addEventListener("click", closeModal);

        window.addEventListener("click", (e) => {
            if (e.target == modal) {
                closeModal();
            }
        });
    });
</script>