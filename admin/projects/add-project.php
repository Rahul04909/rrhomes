<?php require_once '../header.php'; ?>
<?php require_once '../../database/config.php'; ?>

<?php
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
    $seo_featured_image = '';
    if (isset($_FILES['seo_featured_image']) && $_FILES['seo_featured_image']['error'] == 0) {
        $filename = time() . "_th_" . basename($_FILES['seo_featured_image']['name']);
        if (move_uploaded_file($_FILES['seo_featured_image']['tmp_name'], $target_dir . $filename)) {
            $seo_featured_image = $filename;
        }
    }

    $hero_slides = [];
    if (isset($_FILES['hero_slides'])) {
        foreach($_FILES['hero_slides']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['hero_slides']['error'][$key] == 0) {
                $fname = time() . "_hero_" . $key . "_" . basename($_FILES['hero_slides']['name'][$key]);
                if (move_uploaded_file($tmp_name, $target_dir . $fname)) {
                    $hero_slides[] = $fname;
                }
            }
        }
    }
    $hero_slides_json = !empty($hero_slides) ? $conn->real_escape_string(json_encode($hero_slides)) : '';

    $images = [];
    if (isset($_FILES['images'])) {
        foreach($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] == 0) {
                $fname = time() . "_img_" . $key . "_" . basename($_FILES['images']['name'][$key]);
                if (move_uploaded_file($tmp_name, $target_dir . $fname)) {
                    $images[] = $fname;
                }
            }
        }
    }
    $images_json = !empty($images) ? $conn->real_escape_string(json_encode($images)) : '';

    $sql = "INSERT INTO projects (title, short_description, description, specifications, hero_slides, images, seo_meta_title, seo_meta_description, seo_meta_keywords, seo_schema, og_information, seo_featured_image) 
            VALUES ('$title', '$short_desc', '$description', '$specifications', '$hero_slides_json', '$images_json', '$seo_meta_title', '$seo_meta_description', '$seo_meta_keywords', '$seo_schema', '$og_information', '$seo_featured_image')";
            
    if ($conn->query($sql) === TRUE) {
        echo "<script>Swal.fire('Success!', 'Project has been added.', 'success').then(() => { window.location.href='index.php'; });</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add New Project</h3>
            </div>
            <form action="add-project.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="projectTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab">Basic Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="media-tab" data-toggle="tab" href="#media" role="tab">Media & Slides</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab">SEO Aspects</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="projectTabContent">
                        <!-- Basic Info -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="form-group mb-3">
                                <label>Project Title *</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Short Description</label>
                                <textarea class="form-control" name="short_description" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea class="summernote" name="description"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>Specifications</label>
                                <textarea class="summernote" name="specifications"></textarea>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="tab-pane fade" id="media" role="tabpanel">
                            <div class="form-group mb-3">
                                <label>Hero Slides (Select multiple images)</label>
                                <input type="file" class="form-control" name="hero_slides[]" multiple id="heroSlidesInput" accept="image/*">
                                <div id="heroSlidesPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Gallery Images (Select multiple images)</label>
                                <input type="file" class="form-control" name="images[]" multiple id="galleryImagesInput" accept="image/*">
                                <div id="galleryImagesPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Featured Image (Thumbnail)</label>
                                <input type="file" class="form-control" name="seo_featured_image" accept="image/*" id="featuredImgInput">
                                <img id="featuredImgPreview" class="mt-2 d-none" style="height: 100px; border-radius: 4px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- SEO -->
                        <div class="tab-pane fade" id="seo" role="tabpanel">
                            <div class="form-group mb-3">
                                <label>SEO Meta Title</label>
                                <input type="text" class="form-control" name="seo_meta_title">
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Meta Description</label>
                                <textarea class="form-control" name="seo_meta_description" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Meta Keywords (comma separated)</label>
                                <input type="text" class="form-control" name="seo_meta_keywords">
                            </div>
                            <div class="form-group mb-3">
                                <label>OG Information (JSON format or text)</label>
                                <textarea class="form-control" name="og_information" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label>SEO Schema Auto-generated (Leave empty to auto-generate from title/desc, or customize JSON here)</label>
                                <textarea class="form-control" name="seo_schema" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="submit_project" class="btn btn-primary">Save Project</button>
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

    // Auto-generate title for SEO from project title
    $('input[name="title"]').on('keyup', function() {
        var val = $(this).val();
        var seoTitleInput = $('input[name="seo_meta_title"]');
        if (seoTitleInput.val() == '' || seoTitleInput.data('auto') === true) {
            seoTitleInput.val(val).data('auto', true);
        }
    });
    $('input[name="seo_meta_title"]').on('keyup', function() {
        $(this).data('auto', false);
    });
});
</script>

<?php require_once '../footer.php'; ?>
