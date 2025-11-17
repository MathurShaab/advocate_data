<?php
require_once 'functions.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    }

    $name          = trim($_POST['name'] ?? '');
    $password      = $_POST['password'] ?? '';
    $enrollment_no = strtoupper(trim($_POST['enrollment_no'] ?? ''));
    $mobile        = trim($_POST['mobile'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $state         = trim($_POST['state'] ?? '');
    $district      = trim($_POST['district'] ?? '');
    $pin           = trim($_POST['pin'] ?? '');

    if ($name === '') $errors[] = "Name required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if (!valid_enrollment($enrollment_no)) $errors[] = "Invalid enrollment number format.";
    if (!valid_mobile($mobile)) $errors[] = "Mobile must be 10 digits and in valid format.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
    if ($state === '' || $district === '' || !valid_pin($pin)) $errors[] = "Address fields invalid.";

    if (empty($errors)) {

        $pwd_hash   = password_hash($password, PASSWORD_DEFAULT);
        $mobile_enc = encrypt_field($mobile);
        $email_enc  = encrypt_field($email);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO registration 
                (name, password_hash, enrollment_no, mobile_enc, email_enc, state, district, pin_code)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([$name, $pwd_hash, $enrollment_no, $mobile_enc, $email_enc, $state, $district, $pin]);

            $success = "Registration successful. Please log in.";

            header("Location: index.php?registered=1");
            exit;


        } catch (PDOException $e) {
            if (stripos($e->getMessage(), 'Duplicate') !== false) {
                $errors[] = "Enrollment number already registered.";
            } else {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Advocate Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="header">Advocate Registration</div>

<div class="wrapper">
    <div class="card">

        <h2>Register</h2>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
                <div class="error"><?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
            
        <?php endif; ?>

        <form method="post">

            <input type="hidden" name="csrf_token"
                   value="<?php echo htmlspecialchars(csrf_token()); ?>">

            <label>Name</label>
            <input type="text" name="name"
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>

            <label>Password (min 6 chars)</label>
            <input type="password" name="password" required>

            <label>Enrollment Number</label>
            <input type="text" name="enrollment_no"
                   placeholder="e.g. A1234BC1234"
                   value="<?php echo htmlspecialchars($_POST['enrollment_no'] ?? ''); ?>" required>

            <label>Mobile</label>
            <input type="text" name="mobile"
                   value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>" required>

            <label>Email</label>
            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>

            <label>State</label>
            <input type="text" name="state"
                   value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>" required>

            <label>District</label>
            <input type="text" name="district"
                   value="<?php echo htmlspecialchars($_POST['district'] ?? ''); ?>" required>

            <label>PIN Code</label>
            <input type="text" name="pin"
                   value="<?php echo htmlspecialchars($_POST['pin'] ?? ''); ?>" required>

            <button type="submit">Register</button>

        </form>

        <p style="text-align:center; margin-top:15px;">
            <a href="login.php">Already have an account? Login</a>
        </p>

    </div>
</div>

</body>
</html>
