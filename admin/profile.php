<?php include './header.php'; ?>
<?php
$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $mobile = $_POST['mobile'] ?? '';
        $username = $_POST['username'] ?? '';
        
        $profile_image = $logged_in_image;

        // Handle image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $target_dir = "./src/images/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
            $target_file = $target_dir . $file_name;
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $file_name;
            }
        }

        $stmt = $conn->prepare("UPDATE admins SET name = ?, email = ?, mobile = ?, username = ?, profile_image = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $email, $mobile, $username, $profile_image, $_SESSION['admin_id']);
        if ($stmt->execute()) {
            $msg = "Profile updated successfully!";
            $logged_in_name = htmlspecialchars($name);
            $logged_in_image = htmlspecialchars($profile_image);
            // Refresh admin data since we changed it
            // header("Refresh: 1"); 
            // Better to display it with the current data below
        } else {
            $err = "Error updating profile. Username or Email might already exist.";
        }
    } elseif (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($new_password === $confirm_password) {
            $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['admin_id']);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $row = $res->fetch_assoc();
                if (password_verify($old_password, $row['password'])) {
                    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $upd_stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
                    $upd_stmt->bind_param("si", $new_hash, $_SESSION['admin_id']);
                    if ($upd_stmt->execute()) {
                        $msg = "Password changed successfully!";
                    } else {
                        $err = "Error changing password.";
                    }
                } else {
                    $err = "Old password is incorrect.";
                }
            }
        } else {
            $err = "New passwords do not match.";
        }
    }
}

// Fetch current info
$stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$current_admin = $stmt->get_result()->fetch_assoc();
?>

<div class="row">
    <div class="col-md-6">
        <?php if($msg): ?>
            <div class="alert alert-success"><?= $msg ?></div>
        <?php endif; ?>
        <?php if($err): ?>
            <div class="alert alert-danger"><?= $err ?></div>
        <?php endif; ?>
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Update Profile</h3>
            </div>
            <form action="profile.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($current_admin['name']) ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($current_admin['email']) ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Mobile</label>
                        <input type="text" class="form-control" name="mobile" value="<?= htmlspecialchars($current_admin['mobile']) ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($current_admin['username']) ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Profile Image</label>
                        <input type="file" class="form-control" name="profile_image" accept="image/*">
                        <?php if ($current_admin['profile_image']): ?>
                            <img src="./src/images/<?= htmlspecialchars($current_admin['profile_image']) ?>" class="mt-2" style="max-height: 80px;" alt="Profile">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Change Password</h3>
            </div>
            <form action="profile.php" method="POST">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Old Password</label>
                        <input type="password" class="form-control" name="old_password" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="change_password" class="btn btn-success">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>