<?php
include '../minh/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $role = htmlspecialchars(trim($_POST['role'] ?? 'admin'));

    // Validate dữ liệu
    if (empty($username)) {
        $error = "Tên đăng nhập không được để trống.";
    } elseif (!$email) {
        $error = "Email không hợp lệ.";
    } elseif (strlen($password) < 6) {
        $error = "Mật khẩu phải có ít nhất 6 ký tự.";
    } else {
        try {
            // Kiểm tra email đã tồn tại
            $stmt = $conn_user->prepare('SELECT id FROM admins WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email đã được sử dụng.";
            } else {
                // Mã hóa mật khẩu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Thêm admin mới vào bảng admins
                $stmt = $conn_user->prepare('INSERT INTO admins (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
                $stmt->execute([$username, $email, $hashed_password, $role]);
                $success = "Tạo tài khoản admin thành công!";
            }
        } catch (PDOException $e) {
            $error = "Lỗi khi tạo tài khoản: " . $e->getMessage();
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="form-wrapper">
    <div class="form-container">
        <h2>Tạo Tài Khoản Admin</h2>
        <?php if ($error): ?>
            <div class="error-messages">
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-messages">
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>
            <div class="form-group">
                <label for="role">Vai trò</label>
                <input type="text" id="role" name="role" value="admin" readonly>
            </div>
            <button type="submit" class="btn-add-admin">
                <span class="btn-icon">+</span> Tạo Admin
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

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
input[readonly] {
    background-color: #f0f0f0;
    cursor: not-allowed;
}
input::placeholder {
    color: #b0b0b0;
    font-style: italic;
}
.btn-add-admin {
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
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-add-admin .btn-icon {
    font-size: 1.2rem;
    font-weight: bold;
}
.btn-add-admin:hover {
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
.success-messages {
    color: green;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
}
.success {
    background-color: #ddffdd;
    padding: 10px;
    border-radius: 5px;
}
</style>