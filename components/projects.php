<?php
require_once __DIR__ . '/../database/config.php';
$stmt = $conn->prepare("SELECT id, title, short_description, seo_featured_image FROM projects ORDER BY id DESC LIMIT 6");
$stmt->execute();
$projects = $stmt->get_result();
?>
<!-- components/projects.php -->
<link rel="stylesheet" href="assets/css/projects.css">

<section class="projects-section">
    <div class="projects-container">
        
        <!-- Section Heading -->
        <div class="section-heading">
            <h2>Our Featured <span class="gold-text">Projects</span></h2>
            <p>Discover our meticulously crafted residential spaces that redefine modern luxury living.</p>
        </div>

        <!-- Projects Grid -->
        <div class="projects-grid">
            <?php if ($projects->num_rows > 0): ?>
                <?php while ($row = $projects->fetch_assoc()): ?>
                <div class="project-card">
                    <div class="card-img" style="height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                        <?php if(!empty($row['seo_featured_image'])): ?>
                            <img src="assets/uploads/projects/<?= htmlspecialchars($row['seo_featured_image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <img src="assets/rr-home-logo.png" alt="RR Homes" style="max-height: 80px; opacity: 0.3;">
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= htmlspecialchars(mb_strimwidth($row['short_description'], 0, 150, '...')) ?></p>
                        
                        <div class="card-action">
                            <a href="project-details.php?id=<?= $row['id'] ?>" class="skew-btn">READ MORE</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; color: #666; font-size: 1.2rem; min-height: 200px; display: flex; align-items: center; justify-content: center;">
                    No projects available right now. Check back later!
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
