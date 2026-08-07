<?php
/**
 * Admin Login Page
 * 事务管理系统登录界面
 */

$message = '';
$messageType = '';

// 处理登录表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = '请输入用户名和密码';
        $messageType = 'error';
    } else {
        // 从数据库 account 表验证
        require_once __DIR__ . '/../config/database.php';
        $db = getDB('account');

        try {
            $stmt = $db->prepare('SELECT id, username, password FROM account WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && $password === $user['password']) {
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_id']    = $user['id'];
                header('Location: ../page/homepage.php');
                exit;
            }
        } catch (PDOException $e) {
            // 数据库错误时返回通用提示，不暴露细节
            $message = '数据库错误，请稍后重试';
            $messageType = 'error';
        }

        // 验证失败
        if (!isset($_SESSION['admin_logged_in'])) {
            $message = '用户名或密码错误';
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>事务管理系统 - 登录</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/login_bg.css">
</head>
<body>

    <!-- Floating particles background -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">

            <!-- Header -->
            <div class="login-header">
                <div class="login-logo"><img src="../images/logo.jpg" alt="Logo"></div>
                <h1>事务管理系统</h1>
                <p>登录以继续</p>
            </div>

            <!-- Alert Message -->
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <?php if ($messageType === 'error'): ?>
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        <?php else: ?>
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        <?php endif; ?>
                    </svg>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="" id="loginForm" autocomplete="off">
                <!-- Username -->
                <div class="form-group">
                    <label for="username">用户名</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="username" name="username" class="form-input"
                               placeholder="请输入用户名" required autofocus
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">密码</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" id="password" name="password" class="form-input"
                               placeholder="请输入密码" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="显示/隐藏密码">
                            <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Options -->
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember">
                        <span>记住我</span>
                    </label>
                    <a href="#" class="forgot-link">忘记密码?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login">
                    <span>
                        登 录
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; 2026 事务管理系统. All rights reserved.</p>
            </div>

        </div>
    </div>

    <script>
        // 密码显示/隐藏切换
        function togglePassword() {
            const pwd  = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.innerHTML =
                    '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/> ' +
                    '<line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                pwd.type = 'password';
                icon.innerHTML =
                    '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/> ' +
                    '<circle cx="12" cy="12" r="3"/>';
            }
        }

        // 表单回车提交
        document.getElementById('loginForm').addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                e.preventDefault();
                this.submit();
            }
        });
    </script>

</body>
</html>