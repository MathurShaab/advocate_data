<?php
require_once 'functions.php';

if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id, name, enrollment_no, mobile_enc, email_enc, state, district, pin_code FROM registration WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

$mobile = decrypt_field($user['mobile_enc']);
$email = decrypt_field($user['email_enc']);

$stmt2 = $pdo->prepare("SELECT dob, date_of_enrollment, photo_path FROM advocate_data WHERE reg_id = ?");
$stmt2->execute([$user_id]);
$data = $stmt2->fetch();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="header">Advocate Data Dashboard</div>

<div class="wrapper">
    <div class="card card-wide">

        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>

        <!-- Logout Button -->
        <div class="button-group">
            <a class="btn-small btn" href="logout.php">Logout</a>
        </div>

        <!-- Registration Section -->
        <div class="section-title">Your Registration Details</div>
        <div class="readonly-box">
            <p><strong>Enrollment No: </strong><?php echo htmlspecialchars($user['enrollment_no']); ?></p>
            <p><strong>Name: </strong><?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Mobile: </strong><?php echo htmlspecialchars($mobile); ?></p>
            <p><strong>Email: </strong><?php echo htmlspecialchars($email); ?></p>
            <p><strong>State: </strong><?php echo htmlspecialchars($user['state']); ?></p>
            <p><strong>District: </strong><?php echo htmlspecialchars($user['district']); ?></p>
            <p><strong>PIN Code: </strong><?php echo htmlspecialchars($user['pin_code']); ?></p>
        </div>

        <!-- Additional Data Section -->
        <div class="section-title">Additional Information</div>

        <?php if ($data): ?>
            <div class="readonly-box">
                <p><strong>Date of Birth: </strong><?php echo htmlspecialchars($data['dob']); ?></p>
                <p><strong>Date of Enrollment: </strong><?php echo htmlspecialchars($data['date_of_enrollment']); ?></p>

                <?php if (!empty($data['photo_path']) && file_exists($data['photo_path'])): ?>
                    <div class="photo-box">
                        <img src="<?php echo 'uploads/' . basename($data['photo_path']); ?>" alt="Advocate Photo">
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>No additional data added yet.</p>
        <?php endif; ?>

        <div class="button-group">
            <a class="btn" href="add_info.php">Add / Update More Information</a>
        </div>

    </div>
</div>

</body>
</html>
