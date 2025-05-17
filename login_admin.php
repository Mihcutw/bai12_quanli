<?php
include '../minh/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || empty($password)) {
        $error = "Vui lòng nhập email và mật khẩu hợp lệ.";
    } else {
        try {
            $stmt = $conn_user->prepare('SELECT id, password FROM admins WHERE email = ?');
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                header('Location: manage_users.php');
                exit;
            } else {
                $error = "Email hoặc mật khẩu không đúng.";
            }
        } catch (PDOException $e) {
            $error = "Lỗi đăng nhập: " . $e->getMessage();
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="form-wrapper">
    <div class="form-container">
        <h2>Đăng Nhập Admin</h2>
        <?php if ($error): ?>
            <div class="error-messages">
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>
            <button type="submit" class="btn-login">
                Đăng Nhập
            </button>
        </form>
    </div>
</div>

<style>
body {
    font-family: Arial, sans-serif;
    background-image: linear-gradient(45deg, #f3ec78, #af4261);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    margin: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
.form-wrapper {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-bottom: 60px;
}
.form-container {
    background-color: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(147, 112, 219, 0.1);
    border: 2px solid transparent;
    background: linear-gradient(#fff, #fff) padding-box, linear-gradient(90deg, #00eaff, #ff007a) border-box;
    width: 100%;
    max-width: 500px;
    animation: fadeInDown 0.6s ease-in-out;
}
@keyframes fadeInDown {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}
h2 {
    background: linear-gradient(90deg, #3915bb, #b424b4);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-align: center;
    margin-bottom: 1.5rem;
}
.form-group {
    margin-bottom: 1rem;
}
label {
    display: block;
    color: #4682b4;
    margin-bottom: 0.5rem;
}
input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #b0c4de;
    border-radius: 5px;
    box-sizing: border-box;
}
input::placeholder {
    color: #b0b0b0;
    font-style: italic;
}
.btn-login {
    width: 100%;
    padding: 0.8rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: linear-gradient(90deg, #9370db, #4682b4);
    color: white;
    font-weight: bold;
    margin-top: 1rem;
}
.btn-login:hover {
    background: linear-gradient(90deg, #00e676, #00c853);
    box-shadow: 0 2px 10px rgba(0, 200, 83, 0.5);
}
.error-messages {
    color: red;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
}
.error {
    background-color: #ffdddd;
    padding: 10px;
    border-radius: 5px;
}
</style>