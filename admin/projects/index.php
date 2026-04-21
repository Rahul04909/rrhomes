<?php 
require_once '../header.php'; 
require_once '../../database/config.php';

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Select to delete images later if needed
    $stmt = $conn->prepare("SELECT hero_slides, images, seo_featured_image FROM projects WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $proj = $res->fetch_assoc();
        
        // Optional: delete image files here safely
        $featured = $proj['seo_featured_image'];
        if ($featured && file_exists('../../assets/uploads/projects/' . $featured)) {
            unlink('../../assets/uploads/projects/' . $featured);
        }

        if ($proj['hero_slides']) {
            $heroes = json_decode($proj['hero_slides'], true);
            if(is_array($heroes)) {
                foreach($heroes as $img) {
                    if (file_exists('../../assets/uploads/projects/' . $img)) unlink('../../assets/uploads/projects/' . $img);
                }
            }
        }

        if ($proj['images']) {
            $imgs = json_decode($proj['images'], true);
            if(is_array($imgs)) {
                foreach($imgs as $img) {
                    if (file_exists('../../assets/uploads/projects/' . $img)) unlink('../../assets/uploads/projects/' . $img);
                }
            }
        }

        // Delete from table
        $del_stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $del_stmt->bind_param("i", $delete_id);
        if($del_stmt->execute()) {
            echo "<script>Swal.fire('Deleted!', 'Project has been deleted.', 'success').then(() => { window.location.href='index.php'; });</script>";
        }
    }
}

$projects = $conn->query("SELECT id, title, short_description, seo_featured_image FROM projects ORDER BY id DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Manage Projects</h3>
                <a href="add-project.php" class="btn btn-sm btn-primary ml-auto"><i class="fas fa-plus"></i> Add New Project</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th style="width: 100px">Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th style="width: 150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($projects->num_rows > 0): ?>
                            <?php while($row = $projects->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <?php if($row['seo_featured_image']): ?>
                                            <img src="../../assets/uploads/projects/<?= htmlspecialchars($row['seo_featured_image']) ?>" alt="Img" style="width: 80px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars(mb_strimwidth($row['short_description'], 0, 50, '...')) ?></td>
                                    <td>
                                        <a href="edit-project.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No projects found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php?delete_id=' + id;
        }
    })
}
</script>

<?php require_once '../footer.php'; ?>
