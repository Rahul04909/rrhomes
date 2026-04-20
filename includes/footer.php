<!-- includes/footer.php -->
<link rel="stylesheet" href="assets/css/footer.css">

<footer class="footer">
    <div class="footer-container">
        
        <!-- Top Footer Grid -->
        <div class="footer-grid">
            
            <!-- Col 1: Branding -->
            <div class="footer-col branding-col">
                <img src="assets/rr-home-logo.png" alt="RR Homes" class="footer-logo">
                <p class="tagline">Building Dreams, Delivering Luxury</p>
                <p class="desc">Experience the pinnacle of sophisticated living tailored to exceed your expectations.</p>
                
                <div class="social-icons" style="margin-top: 25px;">
                    <a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
            
            <!-- Col 2: Quick Links -->
            <div class="footer-col links-col">
                <h4 class="col-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Projects</a></li>
                    <li><a href="#">Specifications</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            
            <!-- Col 3: Contact Info -->
            <div class="footer-col contact-col">
                <h4 class="col-title">Contact Us</h4>
                <ul class="contact-list">
                    <li><i class="fa-solid fa-phone"></i> +91 98765 43210</li>
                    <li><i class="fa-solid fa-envelope"></i> info@rrhomes.com</li>
                    <li><i class="fa-solid fa-location-dot"></i> RR Homes, Puri VIP Floors<br>Sector 81, Faridabad<br>Haryana 121007</li>
                </ul>
            </div>
            
            <!-- Col 4: Location Map -->
            <div class="footer-col map-col">
                <h4 class="col-title">Find Us Here</h4>
                <div class="footer-map-wrapper">
                    <iframe 
                        src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=RR%20Homes,%20Puri%20Vip%20Floors,%20Sector%2081,%20Faridabad&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" 
                        width="100%" 
                        height="200" 
                        style="border:0; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Bottom Strip -->
    <div class="footer-bottom">
        <div class="footer-bottom-container">
            <p>&copy; <?php echo date('Y'); ?> RR Homes. All rights reserved.</p>
            <p>Designed with <i class="fa-solid fa-heart" title="Love" style="color: #ff4b4b; font-size: 12px; margin: 0 4px;"></i></p>
        </div>
    </div>
    
    <!-- Back to Top Floating Button -->
    <a href="#" class="back-to-top" id="backToTop" aria-label="Back to Top">
        <i class="fa-solid fa-arrow-up"></i>
    </a>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const backToTop = document.getElementById("backToTop");
        
        // Show/Hide back to top on scroll
        window.addEventListener("scroll", function() {
            if (window.scrollY > 400) {
                backToTop.classList.add("visible");
            } else {
                backToTop.classList.remove("visible");
            }
        });
        
        // Smooth scroll to top
        backToTop.addEventListener("click", function(e) {
            e.preventDefault();
            window.scrollTo({ 
                top: 0, 
                behavior: 'smooth' 
            });
        });
    });
</script>
