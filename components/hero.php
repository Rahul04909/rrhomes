<!-- components/hero.php -->
<link rel="stylesheet" href="assets/css/hero.css">
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<section class="hero-section">
    <div class="hero-wrapper">
        <!-- Swiper -->
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <img src="assets/2.png" alt="RR Homes Banner">
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <img src="assets/2.png" alt="RR Homes Banner">
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <img src="assets/2.png" alt="RR Homes Banner">
                </div>

            </div>
            
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            
            <!-- Add Navigation (Hidden on mobile usually, but kept for desktop) -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      var swiper = new Swiper(".heroSwiper", {
        spaceBetween: 20, // space between slides if multiple, though we only show 1
        centeredSlides: true,
        effect: "fade", // smooth premium fade transition (optional, but looks luxurious)
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
        autoplay: {
          delay: 4500,
          disableOnInteraction: false,
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
          dynamicBullets: true,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        loop: true,
        grabCursor: true, // indicates draggability on desktop
      });
  });
</script>
