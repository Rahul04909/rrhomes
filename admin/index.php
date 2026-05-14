<?php 
include './header.php'; 

// Fetch Statistics
// Total Enquiries
$enquiry_count_query = "SELECT COUNT(*) as total FROM enquiries";
$enquiry_count_result = mysqli_query($conn, $enquiry_count_query);
$enquiry_total = mysqli_fetch_assoc($enquiry_count_result)['total'] ?? 0;

// Total Projects
$project_count_query = "SELECT COUNT(*) as total FROM projects";
$project_count_result = mysqli_query($conn, $project_count_query);
$project_total = mysqli_fetch_assoc($project_count_result)['total'] ?? 0;

// New Enquiries Today
$today_enquiry_query = "SELECT COUNT(*) as total FROM enquiries WHERE DATE(created_at) = CURDATE()";
$today_enquiry_result = mysqli_query($conn, $today_enquiry_query);
$today_enquiry_total = mysqli_fetch_assoc($today_enquiry_result)['total'] ?? 0;

// Total Admins
$admin_count_query = "SELECT COUNT(*) as total FROM admins";
$admin_count_result = mysqli_query($conn, $admin_count_query);
$admin_total = mysqli_fetch_assoc($admin_count_result)['total'] ?? 0;
?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo $enquiry_total; ?></h3>
                <p>Total Enquiries</p>
            </div>
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>
            <a href="enquiries/index.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo $project_total; ?></h3>
                <p>Total Projects</p>
            </div>
            <div class="icon">
                <i class="fas fa-building"></i>
            </div>
            <a href="projects/index.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo $today_enquiry_total; ?></h3>
                <p>Today's Enquiries</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="enquiries/index.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo $admin_total; ?></h3>
                <p>System Admins</p>
            </div>
            <div class="icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <a href="profile.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Recent Enquiries Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Recent Enquiries</h3>
                <div class="card-tools">
                    <a href="enquiries/index.php" class="btn btn-sm btn-primary">View All Enquiries</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0 table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Source</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_sql = "SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 10";
                            $recent_result = mysqli_query($conn, $recent_sql);
                            if ($recent_result && mysqli_num_rows($recent_result) > 0) {
                                while ($row = mysqli_fetch_assoc($recent_result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo date('d M, Y', strtotime($row['created_at'])); ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><span class="badge badge-info"><?php echo htmlspecialchars($row['source']); ?></span></td>
                                        <td>
                                            <a href="enquiries/index.php" class="btn btn-xs btn-default"><i class="fas fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center p-4'>No recent enquiries found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>