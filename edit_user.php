<?php
include '../minh/config.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}

$error = '';
$success = '';
$user = null;

if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    try {
        $stmt = $conn_user->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        if (!$user) {
            $error = "Tài khoản user không tồn tại.";
        }
    } catch (PDOException $e) {
        $error = "Lỗi truy vấn: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $role = htmlspecialchars(trim($_POST['role'] ?? 'user'));

    if (empty($username)) {
        $error = "Tên đăng nhập không được để trống.";
    } elseif (!$email) {
        $error = "Email không hợp lệ.";
    } else {
        try {
            // Kiểm tra email đã tồn tại (không phải email của chính user đang chỉnh sửa)
            $stmt = $conn_user->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $stmt->execute([$email, $user_id]);
            if ($stmt->fetch()) {
                $error = "Email đã được sử dụng.";
            } else {
                $update_data = ['username' => $username, 'email' => $email, 'role' => $role];
                if (!empty($password)) {
                    $update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $set_clause = implode(', ', array_map(function($key) {
                    return "$key = :$key";
                }, array_keys($update_data)));
                $stmt = $conn_user->prepare("UPDATE users SET $set_clause WHERE id = :id");
                $stmt->execute(array_merge($update_data, ['id' => $user_id]));
                $success = "Cập nhật tài khoản user thành công!";
                $user = array_merge($user, $update_data); // Cập nhật dữ liệu user để hiển thị
            }
        } catch (PDOException $e) {
            $error = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="form-wrapper">
    <div class="form-container">
        <h2>Chỉnh Sửa Tài Khoản User</h2>
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
        <?php if ($user): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu (để trống nếu không đổi)</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới">
                </div>
                <div class="form-group">
                    <label for="role">Vai trò</label>
                    <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($user['role'] ?? 'user'); ?>" readonly>
                </div>
                <button type="submit" class="btn-edit-user">
                    <span class="btn-icon">✔</span> Cập Nhật
                </button>
                <a href="manage_users.php" class="btn-back">
                    <span class="btn-icon">←</span> Quay Lại
                </a>
            </form>
        <?php else: ?>
            <p class="empty-message">Không tìm thấy tài khoản user.</p>
            <a href="manage_users.php" class="btn-back">
                <span class="btn-icon">←</span> Quay Lại
            </a>
        <?php endif; ?>
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
.btn-edit-user {
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
.btn-edit-user .btn-icon {
    font-size: 1.2rem;
    font-weight: bold;
}
.btn-edit-user:hover {
    background: linear-gradient(90deg, #00e676, #00c853);
    box-shadow: 0 2px 10px rgba(0, 200, 83, 0.5);
}
.btn-back {
    width: 100%;
    padding: 0.8rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: linear-gradient(90deg, #ff9800, #ffb300);
    color: white;
    font-weight: bold;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}
.btn-back .btn-icon {
    font-size: 1.2rem;
    font-weight: bold;
}
.btn-back:hover {
    background: linear-gradient(90deg, #ffb300, #ff9800);
    box-shadow: 0 2px 10px rgba(255, 152, 0, 0.5);
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
.empty-message {
    text-align: center;
    padding: 20px;
    color: #7f8c8d;
    font-style: italic;
}
</style>