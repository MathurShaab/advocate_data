<?php

require_once 'functions.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

$stmt = $pdo->prepare("SELECT id, dob, date_of_enrollment, photo_path FROM advocate_data WHERE reg_id = ?");
$stmt->execute([$user_id]);
$existing = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    }

    $dob = $_POST['dob'] ?: null;
    $date_of_enrollment = $_POST['date_of_enrollment'] ?: null;

    if ($dob && !DateTime::createFromFormat('Y-m-d', $dob)) $errors[] = "Invalid DOB.";
    if ($date_of_enrollment && !DateTime::createFromFormat('Y-m-d', $date_of_enrollment)) $errors[] = "Invalid Date of Enrollment.";

    $photo_path_to_store = $existing['photo_path'] ?? null;

    if (!empty($_FILES['photo']['name'])) {
        $file = $_FILES['photo'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error.";
        } else {
            $size = $file['size'];
            if ($size < 20 * 1024 || $size > 500 * 1024) {
                $errors[] = "Photo must be between 20KB and 500KB.";
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            $allowed = ['image/jpeg', 'image/pjpeg'];
            if (!in_array($mime, $allowed)) {
                $errors[] = "Photo must be JPG format.";
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'jpg' && $ext !== 'jpeg') $errors[] = "Photo file extension must be .jpg";

            if (empty($errors)) {
                if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
                $newname = UPLOAD_DIR . 'photo_' . $user_id . '_' . time() . '.jpg';
                if (!move_uploaded_file($file['tmp_name'], $newname)) {
                    $errors[] = "Failed to save uploaded file.";
                } else {
                    chmod($newname, 0644);
                    $photo_path_to_store = $newname;
                }
            }
        }
    }

    if (empty($errors)) {
        if ($existing) {
            $stmt = $pdo->prepare("UPDATE advocate_data SET dob = ?, date_of_enrollment = ?, photo_path = ? WHERE reg_id = ?");
            $stmt->execute([$dob, $date_of_enrollment, $photo_path_to_store, $user_id]);
            $success = "Data updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO advocate_data (reg_id, dob, date_of_enrollment, photo_path) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $dob, $date_of_enrollment, $photo_path_to_store]);
            $success = "Data saved successfully.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Info</title></head>
<body>
<h2>Add / Update Additional Info</h2>
<p><a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a></p>

<?php foreach ($errors as $e) echo "<p style='color:red;'>".htmlspecialchars($e)."</p>"; ?>
<?php if ($success) echo "<p style='color:green;'>".htmlspecialchars($success)."</p>"; ?>

<form method="post" enctype="multipart/form-data" action="">
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
  <label>DOB: <input type="date" name="dob" value="<?php echo htmlspecialchars($existing['dob'] ?? ''); ?>"></label><br>
  <label>Date of Enrollment: <input type="date" name="date_of_enrollment" value="<?php echo htmlspecialchars($existing['date_of_enrollment'] ?? ''); ?>"></label><br>
  <label>Upload Photo (JPG, 20KB - 500KB): <input type="file" name="photo" accept=".jpg,.jpeg"></label><br>
  <button type="submit">Save</button>
</form>
</body>
</html>