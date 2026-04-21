<?php
require_once __DIR__ . '/database/config.php';

// get id
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

if (!$project) {
    header("Location: index.php");
    exit;
}

// Extract properties
$title = htmlspecialchars($project['title']);
$short_description = htmlspecialchars($project['short_description']);
$description = $project['description']; 
$specifications = $project['specifications']; 
$hero_slides = json_decode($project['hero_slides'], true) ?: [];
$images = json_decode($project['images'], true) ?: [];
$seo_featured = htmlspecialchars($project['seo_featured_image']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($project['seo_meta_title'] ?: $project['title']) ?> | RR Homes</title>
    <meta name="description" content="<?= htmlspecialchars($project['seo_meta_description'] ?: $short_description) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($project['seo_meta_keywords']) ?>">
    <?php if($project['seo_schema']): ?>
    <script type="application/ld+json">
    <?= $project['seo_schema'] ?>
    </script>
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/projects.css">
    <!-- Include Swiper for slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .project-hero { position: relative; height: 75vh; background: #000; overflow: hidden; margin-top: 80px; }
        .project-hero .swiper { width: 100%; height: 100%; }
        .project-hero .swiper-slide img { width: 100%; height: 100%; object-fit: cover; opacity: 0.6; }
        .hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: #fff; z-index: 10; padding: 20px; background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.9)); }
        .hero-overlay h1 { font-size: 4rem; margin-bottom: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }
        .hero-overlay h1 span { color: #d4af37; }
        .hero-overlay p { font-size: 1.25rem; max-width: 800px; line-height: 1.6; }
        
        .details-container { padding: 80px 5%; display: flex; flex-wrap: wrap; gap: 50px; background: whitesmoke; }
        .details-content { flex: 1 1 60%; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .details-sidebar { flex: 1 1 30%; background: #1a1a1a; color: #fff; padding: 40px; border-radius: 10px; border-top: 5px solid #d4af37; box-shadow: 0 10px 30px rgba(0,0,0,0.15); align-self: flex-start; position: sticky; top: 120px;}
        
        .section-title { font-size: 2rem; color: #222; margin-bottom: 30px; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 10px; text-transform: uppercase;}
        .content-box { margin-bottom: 50px; font-size: 1.1rem; line-height: 1.8; color: #555; }
        .content-box img { max-width: 100%; border-radius: 8px; margin: 20px 0; }
        
        .enquiry-form input, .enquiry-form textarea { width: 100%; padding: 15px; margin-bottom: 20px; border: 1px solid #444; background: #2a2a2a; color: #fff; border-radius: 5px; font-family: inherit; font-size: 1rem; transition: border 0.3s;}
        .enquiry-form input::placeholder, .enquiry-form textarea::placeholder { color: #aaa; }
        .enquiry-form input:focus, .enquiry-form textarea:focus { border-color: #d4af37; outline: none; box-shadow: 0 0 5px rgba(212,175,55,0.3); }
        
        .skew-btn-gold { display: inline-block; background-color: #d4af37; color: #fff; text-decoration: none; padding: 15px 30px; font-weight: 600; text-transform: uppercase; font-size: 1rem; letter-spacing: 1px; border: none; cursor: pointer; transition: all 0.3s ease; position: relative; z-index: 1; border-radius: 4px; border: 2px solid #d4af37; width: 100%; text-align: center;}
        .skew-btn-gold:hover { background-color: #fff; border-color: #fff; color: #1a1a1a; }
        
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .gallery-grid img { width: 100%; height: 250px; object-fit: cover; border-radius: 8px; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer;}
        .gallery-grid img:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        /* Modal for Image Preview */
        .modal { display: none; position: fixed; z-index: 9999; padding-top: 50px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9); }
        .modal-content { margin: auto; display: block; max-width: 80%; max-height: 80vh; object-fit: contain; }
        .close { position: absolute; top: 15px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; transition: 0.3s; cursor: pointer; }
        .close:hover, .close:focus { color: #d4af37; text-decoration: none; cursor: pointer; }

        @media(max-width: 768px) {
            .details-container { flex-direction: column; }
            .hero-overlay h1 { font-size: 2.5rem; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="project-hero">
        <div class="swiper myHeroSwiper">
            <div class="swiper-wrapper">
                <?php if(!empty($hero_slides)): ?>
                    <?php foreach($hero_slides as $slide): ?>
                    <div class="swiper-slide"><img src="assets/uploads/projects/<?= htmlspecialchars($slide) ?>" alt="<?= $title ?>"></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="swiper-slide"><img src="assets/uploads/projects/<?= htmlspecialchars($seo_featured) ?>" alt="<?= $title ?>"></div>
                <?php endif; ?>
            </div>
            <div class="hero-overlay">
                <h1><?= $title ?></h1>
                <p><?= $short_description ?></p>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <div class="details-container">
        <div class="details-content">
            <div class="content-box">
                <h2 class="section-title">Project Overview</h2>
                <div>
                    <?= empty(trim(strip_tags($description))) ? '<p>Details coming soon.</p>' : $description ?>
                </div>
            </div>

            <?php if(!empty(trim(strip_tags($specifications)))): ?>
            <div class="content-box">
                <h2 class="section-title">Specifications</h2>
                <div><?= $specifications ?></div>
            </div>
            <?php endif; ?>

            <?php if(!empty($images)): ?>
            <div class="content-box">
                <h2 class="section-title">Photo Gallery</h2>
                <div class="gallery-grid">
                    <?php foreach($images as $img): ?>
                        <img src="assets/uploads/projects/<?= htmlspecialchars($img) ?>" alt="Gallery Image" class="gallery-item" onclick="openModal(this)">
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="details-sidebar">
            <h3 style="margin-bottom:20px; font-size:1.5rem; text-transform:uppercase;">Interested in <br><span style="color:#d4af37;"><?= $title ?></span>?</h3>
            <p style="margin-bottom:30px; color:#bbb;">Fill out the form below and our real estate experts will get back to you shortly.</p>
            
            <?php
            $enq_msg = '';
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_enquiry'])) {
                // Here is where the actual DB insert or Mail logic goes
                $enq_msg = "<div style='color:#28a745; background:rgba(40,167,69,0.1); padding:15px; border-radius:5px; margin-bottom:20px; font-weight:bold; border-left:4px solid #28a745;'>Thank you! Your enquiry has been completely received. Our team will contact you.</div>";
            }
            ?>
            <?= $enq_msg ?>

            <form class="enquiry-form" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <textarea name="message" rows="4" placeholder="Your Message" required>I am interested in the <?= $title ?> project and would like to know more details.</textarea>
                <button type="submit" name="submit_enquiry" class="skew-btn-gold">SUBMIT ENQUIRY</button>
            </form>
        </div>
    </div>

    <!-- The Modal for Lightbox -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        var swiper = new Swiper(".myHeroSwiper", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        // Lightbox Functions
        var modal = document.getElementById("imageModal");
        var modalImg = document.getElementById("img01");

        function openModal(element) {
            modal.style.display = "block";
            modalImg.src = element.src;
        }

        function closeModal() {
            modal.style.display = "none";
        }
        
        // Close modal on click outside image
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
