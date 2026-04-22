<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../../database/config.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_query = "DELETE FROM enquiries WHERE id = $id";
    mysqli_query($conn, $delete_query);
    header("Location: index.php?msg=deleted");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enquiries - RR Homes Admin</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .table-responsive { overflow-x: auto; }
        .message-cell { max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .source-badge { background: #d4af37; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include '../header.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Enquiries</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Enquiry deleted successfully!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Form Submissions</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Contact Info</th>
                                    <th>Message</th>
                                    <th>Source</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM enquiries ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . date('d M Y, h:i A', strtotime($row['created_at'])) . "</td>";
                                        echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                                        echo "<td><i class='fas fa-envelope text-muted'></i> " . htmlspecialchars($row['email']) . "<br><i class='fas fa-phone text-muted'></i> " . htmlspecialchars($row['phone']) . "</td>";
                                        echo "<td class='message-cell' title='" . htmlspecialchars($row['message']) . "'>" . htmlspecialchars($row['message']) . "</td>";
                                        echo "<td><span class='source-badge'>" . htmlspecialchars($row['source']) . "</span></td>";
                                        echo "<td>
                                                <button class='btn btn-info btn-sm view-btn' data-name='".htmlspecialchars($row['name'])."' data-email='".htmlspecialchars($row['email'])."' data-phone='".htmlspecialchars($row['phone'])."' data-message='".htmlspecialchars($row['message'], ENT_QUOTES)."' data-source='".htmlspecialchars($row['source'])."' data-date='".date('d M Y, h:i A', strtotime($row['created_at']))."'><i class='fas fa-eye'></i> View</button>
                                                <a href='#' onclick='confirmDelete(" . $row['id'] . ")' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No enquiries found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include '../footer.php'; ?>

</div>

<!-- Modal for Viewing Enquiry -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Enquiry Details</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Date:</strong> <span id="m_date"></span></p>
        <p><strong>Source:</strong> <span id="m_source" class="text-primary font-weight-bold"></span></p>
        <hr>
        <p><strong>Name:</strong> <span id="m_name"></span></p>
        <p><strong>Email:</strong> <span id="m_email"></span></p>
        <p><strong>Phone:</strong> <span id="m_phone"></span></p>
        <hr>
        <p><strong>Message:</strong></p>
        <div id="m_message" class="p-3 bg-light border rounded"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="" id="m_mailto" class="btn btn-primary"><i class="fas fa-reply"></i> Reply by Email</a>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

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
                window.location.href = 'index.php?delete=' + id;
            }
        });
    }

    $(document).ready(function() {
        $('.view-btn').click(function() {
            $('#m_name').text($(this).data('name'));
            $('#m_email').text($(this).data('email'));
            $('#m_phone').text($(this).data('phone'));
            $('#m_message').text($(this).data('message'));
            $('#m_source').text($(this).data('source'));
            $('#m_date').text($(this).data('date'));
            $('#m_mailto').attr('href', 'mailto:' + $(this).data('email') + '?subject=Reply to your inquiry regarding ' + $(this).data('source'));
            $('#viewModal').modal('show');
        });
    });
</script>
</body>
</html>
