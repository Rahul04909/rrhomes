<?php require_once '../header.php'; ?>
<?php require_once '../../database/config.php'; ?>

<?php
if (!isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_project'])) {
    
    // Create folders if they don't exist
    $target_dir = "../../assets/uploads/projects/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $short_desc = $conn->real_escape_string($_POST['short_description'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $specifications = $conn->real_escape_string($_POST['specifications'] ?? '');
    $seo_meta_title = $conn->real_escape_string($_POST['seo_meta_title'] ?? '');
    $seo_meta_description = $conn->real_escape_string($_POST['seo_meta_description'] ?? '');
    $seo_meta_keywords = $conn->real_escape_string($_POST['seo_meta_keywords'] ?? '');
    $seo_schema = $conn->real_escape_string($_POST['seo_schema'] ?? '');
    $og_information = $conn->real_escape_string($_POST['og_information'] ?? '');
    
    // Fetch old to not overwrite if empty
    $old_res = $conn->query("SELECT hero_slides, images, seo_featured_image FROM projects WHERE id = $id");
    $old_data = $old_res->fetch_assoc();

    // Auto-generate schema if empty
    if (empty(trim($_POST['seo_schema']))) {
        // Generic RealEstateAgent / Product Schema
        $schema_array = [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "name" => stripslashes($title),
            "description" => stripslashes($short_desc),
        ];
        $seo_schema = $conn->real_escape_string(json_encode($schema_array, JSON_PRETTY_PRINT));
    }
    
    // Handle File Uploads
    $seo_featured_image = $old_data['seo_featured_image'];
    if (isset($_FILES['seo_featured_image']) && $_FILES['seo_featured_image']['error'] == 0) {
        $filename = time() . "_th_" . basename($_FILES['seo_featured_image']['name']);
        if (move_uploaded_file($_FILES['seo_featured_image']['tmp_name'], $target_dir . $filename)) {
            $seo_featured_image = $filename;
            if ($old_data['seo_featured_image'] && file_exists($target_dir . $old_data['seo_featured_image'])) unlink($target_dir . $old_data['seo_featured_image']);
        }
    }

    $hero_slides_json = $old_data['hero_slides'];
    if (isset($_FILES['hero_slides']) && $_FILES['hero_slides']['error'][0] == 0) {
        $hero_slides = [];
        foreach($_FILES['hero_slides']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['hero_slides']['error'][$key] == 0) {
                $fname = time() . "_hero_" . $key . "_" . basename($_FILES['hero_slides']['name'][$key]);
                if (move_uploaded_file($tmp_name, $target_dir . $fname)) {
                    $hero_slides[] = $fname;
                }
            }
        }
        if(!empty($hero_slides)) {
            $hero_slides_json = $conn->real_escape_string(json_encode($hero_slides));
            // Optional: delete old slides here
            $old_slides = json_decode($old_data['hero_slides'], true);
            if(is_array($old_slides)){
                foreach($old_slides as $img) if (file_exists($target_dir . $img)) unlink($target_dir . $img);
            }
        }
    }

    $images_json = $old_data['images'];
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] == 0) {
        $images = [];
        foreach($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] == 0) {
                $fname = time() . "_img_" . $key . "_" . basename($_FILES['images']['name'][$key]);
                if (move_uploaded_file($tmp_name, $target_dir . $fname)) {
                    $images[] = $fname;
                }
            }
        }
        if(!empty($images)) {
            $images_json = $conn->real_escape_string(json_encode($images));
            // Optional: delete old images here
            $old_imgs = json_decode($old_data['images'], true);
            if(is_array($old_imgs)){
                foreach($old_imgs as $img) if (file_exists($target_dir . $img)) unlink($target_dir . $img);
            }
        }
    }

    $sql = "UPDATE projects SET title='$title', short_description='$short_desc', description='$description', specifications='$specifications', 
            hero_slides='$hero_slides_json', images='$images_json', seo_meta_title='$seo_meta_title', seo_meta_description='$seo_meta_description', 
            seo_meta_keywords='$seo_meta_keywords', seo_schema='$seo_schema', og_information='$og_information', seo_featured_image='$seo_featured_image' 
            WHERE id = $id";
            
    if ($conn->query($sql) === TRUE) {
        echo "<script>Swal.fire('Success!', 'Project has been updated.', 'success').then(() => { window.location.href='index.php'; });</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Fetch project details
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

if (!$project) {
    echo "Project not found.";
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Edit Project: <?= htmlspecialchars($project['title']) ?></h3>
            </div>
            <form action="edit-project.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="projectTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basic-tab" data-toggle="tab" data-bs-toggle="tab" href="#basic" role="tab">Basic Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="media-tab" data-toggle="tab" data-bs-toggle="tab" href="#media" role="tab">Media & Slides</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="seo-tab" data-toggle="tab" data-bs-toggle="tab" href="#seo" role="tab">SEO Aspects</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="projectTabContent">
                        <!-- Basic Info -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="form-group mb-3">
                                <label>Project Title *</label>
                                <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Short Description</label>
                                <textarea class="form-control" name="short_description" rows="3"><?= htmlspecialchars($project['short_description']) ?></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea class="summernote" name="description"><?= htmlspecialchars($project['description']) ?></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>Specifications</label>
                                <textarea class="summernote" name="specifications"><?= htmlspecialchars($project['specifications']) ?></textarea>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="tab-pane fade" id="media" role="tabpanel">
                            <div class="form-group mb-3">
                                <label>Hero Slides (Will overwrite old slides if new ones selected)</label>
                                <input type="file" class="form-control" name="hero_slides[]" multiple id="heroSlidesInput" accept="image/*">
                                <div id="heroSlidesPreview" class="d-flex flex-wrap gap-2 mt-2">
                                    <?php 
                                    $heroes = json_decode($project['hero_slides'], true);
                                    if (is_array($heroes)) {
                                        foreach($heroes as $img) {
                                            echo '<img src="../../assets/uploads/projects/'.htmlspecialchars($img).'" class="preview-img border-success" title="Current Image">';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Gallery Images (Will overwrite old gallery if new ones selected)</label>
                                <input type="file" class="form-control" name="images[]" multiple id="galleryImagesInput" accept="image/*">
                                <div id="galleryImagesPreview" class="d-flex flex-wrap gap-2 mt-2">
                                    <?php 
                                    $imgs = json_decode($project['images'], true);
                                    if (is_array($imgs)) {
                                        foreach($imgs as $img) {
                                            echo '<img src="../../assets/uploads/projects/'.htmlspecialchars($img).'" class="preview-img border-success" title="Current Image">';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Featured Image (Thumbnail)</label>
                                <input type="file" class="form-control" name="seo_featured_image" accept="image/*" id="featuredImgInput">
                                <?php if($project['seo_featured_image']): ?>
                                    <img id="featuredImgPreview" src="../../assets/uploads/projects/<?= htmlspecialchars($project['seo_featured_image']) ?>" class="mt-2" style="height: 100px; border-radius: 4px; object-fit: cover;">
                                <?php else: ?>
                                    <img id="featuredImgPreview" class="mt-2 d-none" style="height: 100px; border-radius: 4px; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- SEO -->
                        <div class="tab-pane fade" id="seo" role="tabpanel">
                            <div class="form-group mb-3">
                                <label>SEO Meta Title</label>
                                <input type="text" class="form-control" name="seo_meta_title" value="<?= htmlspecialchars($project['seo_meta_title']) ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Meta Description</label>
                                <textarea class="form-control" name="seo_meta_description" rows="3"><?= htmlspecialchars($project['seo_meta_description']) ?></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Meta Keywords (comma separated)</label>
                                <input type="text" class="form-control" name="seo_meta_keywords" value="<?= htmlspecialchars($project['seo_meta_keywords']) ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label>OG Information (JSON format or text)</label>
                                <textarea class="form-control" name="og_information" rows="3"><?= htmlspecialchars($project['og_information']) ?></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Schema Auto-generated (Leave empty to auto-generate from title/desc, or customize JSON here)</label>
                                <textarea class="form-control" name="seo_schema" rows="5"><?= htmlspecialchars($project['seo_schema']) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="submit_project" class="btn btn-primary">Update Project</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.preview-img {
    height: 80px;
    width: 120px;
    object-fit: cover;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-right: 10px;
    margin-bottom: 10px;
}
.border-success { border: 2px solid #28a745 !important; }
</style>

<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Image previews
    function handlePreview(input, containerId) {
        $(input).on('change', function() {
            var container = $('#' + containerId);
            container.empty();
            if (this.files) {
                $.each(this.files, function(i, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        container.append('<img src="'+e.target.result+'" class="preview-img">');
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    }

    handlePreview('#heroSlidesInput', 'heroSlidesPreview');
    handlePreview('#galleryImagesInput', 'galleryImagesPreview');

    $('#featuredImgInput').on('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#featuredImgPreview').attr('src', e.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>

<?php require_once '../footer.php'; ?>
