<?php
// about-us.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | RR Homes</title>
    <meta name="description" content="RR Homes is one of the fastest-growing construction companies in Delhi NCR delivering high-quality projects.">
    <style>
        .page-hero { position: relative; height: 50vh; background: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat; margin-top: 80px; display: flex; align-items: center; justify-content: center; }
        .page-hero::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.5)); }
        .page-hero-content { position: relative; z-index: 1; text-align: center; color: #fff; }
        .page-hero-content h1 { font-size: 4rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 800; text-shadow: 2px 2px 5px rgba(0,0,0,0.5);}
        .page-hero-content h1 span { color: #d4af37; }
        
        .about-section { padding: 80px 5%; background: #fff; }
        .about-container { max-width: 1200px; margin: 0 auto; display: flex; flex-wrap: wrap; gap: 80px; align-items: center; }
        .about-content { flex: 1 1 500px; }
        .about-image { flex: 1 1 400px; position: relative; }
        .about-image img { width: 100%; border-radius: 10px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .about-image::before { content: ''; position: absolute; top: -20px; left: -20px; width: 150px; height: 150px; border-top: 6px solid #d4af37; border-left: 6px solid #d4af37; z-index: -1; border-radius: 4px; }
        .about-image::after { content: ''; position: absolute; bottom: -20px; right: -20px; width: 150px; height: 150px; border-bottom: 6px solid #d4af37; border-right: 6px solid #d4af37; z-index: -1; border-radius: 4px;}
        
        .section-title { font-size: 2.2rem; color: #111; margin-bottom: 25px; position: relative; padding-bottom: 15px; font-weight: 800; text-transform: uppercase;}
        .section-title::after { content: ''; position: absolute; left: 0; bottom: 0; width: 80px; height: 4px; background: #d4af37; }
        
        .about-content p { font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 25px; }
        
        .vision-mission-section { padding: 80px 5%; background: #ffffff; }
        .vm-container { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 50px; }
        .vm-content h3 { font-size: 2.2rem; color: #111; margin-bottom: 25px; position: relative; padding-bottom: 15px; font-weight: 800; text-transform: uppercase;}
        .vm-content h3::after { content: ''; position: absolute; left: 0; bottom: 0; width: 80px; height: 4px; background: #d4af37; }
        .vm-content p { font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 20px; }

        @media(max-width: 768px) {
            .page-hero h1 { font-size: 2.8rem;}
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="page-hero">
        <div class="page-hero-content">
            <h1>About <span>RR Homes</span></h1>
            <p style="font-size: 1.25rem; max-width: 700px; margin: auto; opacity: 0.9; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">Pushing horizons of possibilities by turning your dream house into a reality with our passionate and dedicated team.</p>
        </div>
    </section>

    <section class="about-section">
        <div class="about-container">
            <div class="about-content">
                <h2 class="section-title">Who We Are</h2>
                <p><strong>RR Homes</strong> is one of the fastest-growing construction companies in Delhi NCR. It is stitched by some brilliant minds with clear business values. With more than 10 years of diverse Experience, RR Homes is delivering high-quality projects that ensure top-quality construction, European style elevation, and timely delivery.</p>
                <p>RR homes offer a wide range of builder floors in Faridabad at a very reasonable cost. RR Homes works to develop residential units that are spacious, appealing, and rich in aesthetics. We ensure to provide age-proof homes through the best construction material, latest construction techniques, and a dedicated after-sales team.</p>
                <p>Experience of our Founders <strong>Sandeep Goel</strong> and <strong>Pravesh Goel</strong> has made the quality of construction world-class with proper strategic implementation. We are pushing horizons of possibilities by turning your dream house into a reality with our passionate and dedicated team.</p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="RR Homes Exterior Building">
            </div>
        </div>
    </section>

    <section class="vision-mission-section">
        <div class="vm-container">
            <div class="vm-content">
                <h3>Our Vision</h3>
                <p>To be a premier real estate group by paving a pathway for the finest level of quality construction and contribute to creating modern real estate solutions on the foundation of commitment, trust, and integrity.</p>
                <p>To always be the preferred choice of our customers and to be recognized as one of the most trustable real estate brands.</p>
                <p>We strive to provide affordability, timely delivery, and transparent communication to our customers.</p>
            </div>
            <div class="vm-content">
                <h3>Our Mission</h3>
                <p>RR Homes is working on a mission of creating an atmosphere of trust and integrity within the real estate industry. We aim to exceed the limit of client satisfaction and deliver projects within the time frame while upholding the utmost degree of professionalism and ethics.</p>
                <p>To be consistent and always work towards innovation and sustainable development.</p>
                <p>To establish a long-lasting relationship with our customers and employees by maintaining the highest level of transparency, honesty, and professionalism in our work.</p>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
