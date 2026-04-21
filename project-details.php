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
        body { background-color: #f9f9f9; font-family: 'Inter', sans-serif; }
        .project-hero { position: relative; height: 75vh; background: #000; overflow: hidden; margin-top: 80px; }
        .project-hero .swiper { width: 100%; height: 100%; }
        .project-hero .swiper-slide img { width: 100%; height: 100%; object-fit: cover; opacity: 0.6; }
        .hero-overlay { position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: #fff; z-index: 10; padding: 20px; background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.9)); }
        .hero-overlay h1 { font-size: 3.5rem; margin-bottom: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); }
        .hero-overlay h1 span { color: #d4af37; }
        .hero-overlay p { font-size: 1.25rem; max-width: 800px; line-height: 1.6; text-shadow: 1px 1px 3px rgba(0,0,0,0.5); }
        
        .main-wrapper { background: whitesmoke; }
        .details-container { padding: 60px 5%; max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
        
        .details-content { background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
        .details-sidebar { background: #ffffff; color: #333; padding: 30px; border-radius: 12px; box-shadow: 0 5px 30px rgba(0,0,0,0.08); border-top: 6px solid #d4af37; align-self: flex-start; position: sticky; top: 100px; max-height: calc(100vh - 120px); overflow-y: auto;}
        
        /* Custom sidebar scrollbar to keep it clean */
        .details-sidebar::-webkit-scrollbar { width: 4px; }
        .details-sidebar::-webkit-scrollbar-track { background: #fbfbfb; }
        .details-sidebar::-webkit-scrollbar-thumb { background: #d4af37; border-radius: 4px; }

        .sidebar-header { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .sidebar-header h3 { font-size: 1.4rem; text-transform: uppercase; margin: 0; color: #222; font-weight: 800; line-height: 1.3;}
        .sidebar-header p { color: #666; margin-top: 8px; font-size: 0.9rem; line-height: 1.5; }
        
        .section-title { font-size: 1.8rem; color: #111; margin-bottom: 25px; position: relative; padding-bottom: 10px; font-weight: 700; text-transform: uppercase;}
        .section-title::after { content: ''; position: absolute; left: 0; bottom: 0; width: 60px; height: 3px; background: #d4af37; }
        
        .content-box { margin-bottom: 50px; font-size: 1.05rem; line-height: 1.8; color: #555; }
        .content-box img { max-width: 100%; border-radius: 8px; margin: 20px 0; }
        
        .form-group-custom { margin-bottom: 15px; }
        .form-group-custom label { display: block; font-weight: 600; margin-bottom: 5px; color: #444; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;}
        .enquiry-form input, .enquiry-form textarea { width: 100%; box-sizing: border-box; padding: 12px 15px; border: 1px solid #ddd; background: #fdfdfd; color: #333; border-radius: 5px; font-family: inherit; font-size: 0.95rem; transition: all 0.3s;}
        .enquiry-form input:focus, .enquiry-form textarea:focus { border-color: #d4af37; outline: none; box-shadow: 0 0 0 3px rgba(212,175,55,0.15); background: #fff;}
        .enquiry-form textarea { resize: vertical; min-height: 80px; }

        .skew-btn-gold { display: inline-block; background-color: #d4af37; color: #fff; text-decoration: none; padding: 14px 25px; font-weight: 700; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 1px; border: none; cursor: pointer; transition: all 0.3s ease; border-radius: 5px; width: 100%; text-align: center; box-shadow: 0 4px 15px rgba(212,175,55,0.3); margin-top: 5px;}
        .skew-btn-gold:hover { background-color: #222; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(34,34,34,0.2); color: #d4af37; }
        
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-top: 20px; }
        .gallery-grid img { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; transition: transform 0.4s ease, box-shadow 0.4s ease; cursor: pointer;}
        .gallery-grid img:hover { transform: scale(1.03); box-shadow: 0 10px 20px rgba(0,0,0,0.15); z-index: 1;}
        
        /* Modal for Image Preview */
        .modal { display: none; position: fixed; z-index: 9999; padding-top: 50px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9); backdrop-filter: blur(5px);}
        .modal-content { margin: auto; display: block; max-width: 80%; max-height: 80vh; object-fit: contain; box-shadow: 0 0 30px rgba(0,0,0,0.5); border-radius: 4px;}
        .close { position: absolute; top: 15px; right: 35px; color: #fff; font-size: 40px; font-weight: bold; transition: 0.3s; cursor: pointer; text-shadow: 0 0 10px rgba(0,0,0,0.5);}
        .close:hover, .close:focus { color: #d4af37; text-decoration: none; cursor: pointer; transform: scale(1.1);}

        @media(max-width: 991px) {
            .details-container { grid-template-columns: 1fr; }
            .details-sidebar { position: static; max-height: none; overflow-y: visible; margin-top: 20px;}
            .hero-overlay h1 { font-size: 2.5rem; }
            .details-content { padding: 25px; }
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

    <div class="main-wrapper">
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
                <div class="sidebar-header">
                    <h3>Interested in <br><span style="color:#d4af37;"><?= $title ?></span>?</h3>
                    <p>Fill out the form below and our real estate experts will get back to you shortly.</p>
                </div>
                
                <?php
                $enq_msg = '';
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_enquiry'])) {
                    // Here is where the actual DB insert or Mail logic goes
                    $enq_msg = "<div style='color:#28a745; background:rgba(40,167,69,0.1); padding:15px; border-radius:5px; margin-bottom:20px; font-weight:bold; border-left:4px solid #28a745;'>Thank you! Your enquiry has been completely received. Our team will contact you.</div>";
                }
                ?>
                <?= $enq_msg ?>

                <form class="enquiry-form" method="POST">
                    <div class="form-group-custom">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group-custom">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group-custom">
                        <label>Phone Number</label>
                        <input type="text" name="phone" placeholder="Enter your phone" required>
                    </div>
                    <div class="form-group-custom">
                        <label>Your Message</label>
                        <textarea name="message" rows="4" placeholder="How can we help you?" required>I am interested in the <?= $title ?> project and would like to know more details.</textarea>
                    </div>
                    <button type="submit" name="submit_enquiry" class="skew-btn-gold">SUBMIT ENQUIRY</button>
                </form>
            </div>
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
