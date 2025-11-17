<?php
require_once 'functions.php';


// If user is already logged in, redirect to dashboard
if (!empty($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    }

    $enrollment_no = strtoupper(trim($_POST['enrollment_no'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($enrollment_no === '' || $password === '') {
        $errors[] = "Provide both enrollment number and password.";
    } else {

        $stmt = $pdo->prepare("SELECT id, password_hash FROM registration WHERE enrollment_no = ?");
        $stmt->execute([$enrollment_no]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = "Either Login ID or Password is wrong";
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="header">Advocate Login Panel</div>

<div class="wrapper">
    <div class="card">

        <h2>Login</h2>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
                <div class="error"><?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post">

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

            <label>Enrollment Number</label>
            <input type="text" name="enrollment_no" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>

        </form>

        <p style="text-align:center; margin-top:15px;">
            <a href="register.php">New User? Register Here</a>
        </p>

    </div>
</div>

</body>
</html>
