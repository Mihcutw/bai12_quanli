<?php
include '../minh/config.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}

$error = '';
$success = '';

try {
    // Xử lý xóa user
    if (isset($_GET['delete'])) {
        $user_id = (int)$_GET['delete'];
        $stmt = $conn_user->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $success = "Xóa tài khoản user thành công!";
    }

    // Lấy danh sách users
    $stmt = $conn_user->query('SELECT * FROM users');
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Lỗi truy vấn: " . $e->getMessage();
}
?>

<?php include 'header.php'; ?>

<div class="manage-users-wrapper">
    <div class="container animate-in">
        <h1>Quản Lý Tài Khoản Users</h1>

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

        <div class="action-buttons">
            <a href="create_user.php" class="btn btn-add">+ Thêm User</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Đăng Nhập</th>
                        <th>Email</th>
                        <th>Vai Trò</th>
                        <th>Ngày Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role'] ?? 'user'); ?></td>
                            <td><?php echo $user['created_at']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-edit">Sửa</a>
                                <a href="manage_users.php?delete=<?php echo $user['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="empty-message">Hiện chưa có tài khoản user nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<style>
.manage-users-wrapper {
    min-height: 100vh;
    padding: 40px 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.animate-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease-in-out forwards;
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

h1 {
    color: #fff;
    margin-bottom: 30px;
    text-align: center;
    font-size: 2.5em;
    font-weight: 500;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 30px;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 12px 25px;
    text-decoration: none;
    border-radius: 50px;
    transition: all 0.3s ease;
    font-weight: 500;
    color: white;
}

.btn-add {
    background: linear-gradient(45deg, #00c853, #00e676);
    box-shadow: 0 4px 15px rgba(0, 200, 83, 0.3);
}

.btn-add:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 200, 83, 0.4);
    background: linear-gradient(45deg, #00e676, #00c853);
}

.table-wrapper {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 18px 20px;
    text-align: left;
    transition: all 0.3s ease;
}

th {
    background: linear-gradient(45deg, #9c5ffd, #1de0ff);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

tbody tr:hover {
    background: rgba(156, 95, 253, 0.1);
    transform: scale(1.01);
}

.btn-edit, .btn-delete {
    padding: 8px 18px;
    margin: 0 5px;
    border-radius: 25px;
    color: white;
    font-size: 0.9em;
}

.btn-edit {
    background: linear-gradient(45deg, #3498db, #2980b9);
}

.btn-delete {
    background: linear-gradient(45deg, #e74c3c, #c0392b);
}

.btn-edit:hover {
    background: linear-gradient(45deg, #2980b9, #3498db);
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
}

.btn-delete:hover {
    background: linear-gradient(45deg, #c0392b, #e74c3c);
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(231, 76, 60, 0.3);
}

.empty-message {
    text-align: center;
    padding: 40px;
    color: #7f8c8d;
    font-style: italic;
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

@media (max-width: 768px) {
    .manage-users-wrapper {
        padding: 20px 10px;
    }

    .container {
        margin: 0;
        padding: 10px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 10px;
    }

    .btn-add {
        width: 100%;
        text-align: center;
    }

    table {
        display: block;
        overflow-x: auto;
    }

    th, td {
        min-width: 150px;
    }
}
</style>