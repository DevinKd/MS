<?php
/**
 * 公共页面头部（导航栏）
 * 使用方法：require_once 'header.php';  // 在 <body> 标签后
 *
 * 依赖：session 已启动（如需检查登录状态则页面顶部需 session_start()）
 * 依赖：config/database.php 和 getDB 函数
 */

// 确保 session 存在
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 默认变量（页面已定义则跳过）
if (!isset($isLoggedIn)) {
    $isLoggedIn = isset($_SESSION['admin_logged_in']);
}

// 页面未定义 $userInfo 时才查询数据库（避免重复查询）
if (!isset($userInfo) && $isLoggedIn) {
    if (function_exists('getDB')) {
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
}

if (!isset($userInfo)) {
    $userInfo = null;
}
?>

<!-- 顶部导航栏 -->
<nav class="navbar">
    <div class="nav-logo">
        <img src="../images/logo.jpg" alt="Logo">
        事务管理系统
    </div>

    <?php if ($isLoggedIn): ?>
        <!-- 已登录：显示用户信息 -->
        <div class="nav-user">
            <div class="avatar">
                <?php if (!empty($userInfo['avatar'])): ?>
                    <img src="<?php echo htmlspecialchars($userInfo['avatar']); ?>" alt="头像">
                <?php else: ?>
                    <?php echo htmlspecialchars(mb_substr($userInfo['username'] ?? '用户', 0, 1)); ?>
                <?php endif; ?>
            </div>
            <div class="user-info">
                <div class="user-name">
                    <?php echo htmlspecialchars($userInfo['username'] ?? '用户'); ?>
                </div>
                <div class="user-role">
                    <?php echo htmlspecialchars($userInfo['email'] ?? '普通用户'); ?>
                </div>
            </div>
            <a href="../admin/logout.php" class="btn-logout">退出登录</a>
        </div>
    <?php else: ?>
        <!-- 未登录：显示登录按钮 -->
        <div class="nav-user">
            <a href="../admin/login.php" class="btn-login-nav">登 录</a>
        </div>
    <?php endif; ?>
</nav>
