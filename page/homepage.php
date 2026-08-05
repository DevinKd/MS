<?php
/**
 * 个人主页面
 * 显示用户个人信息、最近事务等
 */
require_once __DIR__ . '/../config/database.php';

// 启动 session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 检查登录状态
$isLoggedIn = isset($_SESSION['admin_logged_in']);

// 获取用户信息
$userInfo = null;
if ($isLoggedIn) {
    try {
        $db = getDB('account');
        $stmt = $db->prepare('SELECT * FROM account WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['admin_id']]);
        $userInfo = $stmt->fetch();
    } catch (PDOException $e) {
        // 查询失败
        $isLoggedIn = false;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>事务管理系统 - 个人主页</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/homepage.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="main-content">

        <?php if ($isLoggedIn): ?>
            <!-- 已登录：显示个人信息和统计数据 -->
            <div class="welcome-section">
                <h1>欢迎回来，<?php echo htmlspecialchars($userInfo['username'] ?? '用户'); ?>！</h1>
                <p>今天是个好日子，来看看你最近的财务情况吧</p>
            </div>

            <!-- 统计卡片 -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">总收入</div>
                    <div class="stat-value positive">¥0.00</div>
                    <div class="stat-change positive">较上月 +0%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">总支出</div>
                    <div class="stat-value negative">¥0.00</div>
                    <div class="stat-change negative">较上月 +0%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">净收入</div>
                    <div class="stat-value">¥0.00</div>
                    <div class="stat-change">当前账户余额</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">待办事务</div>
                    <div class="stat-value">0</div>
                    <div class="stat-change">待完成的事务数</div>
                </div>
            </div>

            <!-- 最近事务 -->
            <div class="recent-section">
                <h2>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    最近事务
                </h2>
                <div class="transaction-list">
                    <div class="transaction-item">
                        <div class="tx-info">
                            <div class="tx-icon pending">📋</div>
                            <div>
                                <div class="tx-title">暂无最近事务</div>
                                <div class="tx-date">开始录入你的第一条事务吧</div>
                            </div>
                        </div>
                        <div class="tx-amount">—</div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- 未登录：显示登录引导 -->
            <div class="not-logged-in">
                <h1>👋 欢迎回来！</h1>
                <p>登录您的事务管理系统，开始管理您的财务事务</p>
                <a href="../admin/login.php" class="btn-login-main">立即登录</a>
            </div>
        <?php endif; ?>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>