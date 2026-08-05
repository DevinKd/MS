<?php
/**
 * 项目管理页面
 * 显示 transaction 数据库中的 project 表数据
 * 需要登录后才能访问
 */

require_once __DIR__ . '/../config/database.php';

// 确保 session 存在
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 检查登录状态，未登录则直接报错
if (!isset($_SESSION['admin_logged_in'])) {
    require_once __DIR__ . '/../admin/not_login.php';
}

// 获取登录用户信息（登录已通过 die() 检查，此处必定为已登录状态）
$userInfo = null;
try {
    $db_acc = getDB('account');
    $stmt = $db_acc->prepare('SELECT * FROM account WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['admin_id']]);
    $userInfo = $stmt->fetch();
} catch (PDOException $e) {
    // 查询失败时显示通用错误
}

// 连接 transaction 数据库
$db = getDB('transaction');

// 查询 project 表
$projects = [];
$tableColumns = [];
$errorMsg = '';

try {
    // 获取列信息
    $columnsStmt = $db->query('DESCRIBE project');
    $tableColumns = $columnsStmt->fetchAll(PDO::FETCH_COLUMN);

    // 查询所有数据
    $projects = $db->query('SELECT * FROM project')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = '数据库查询失败，请检查 project 表是否存在。错误：' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>事务管理系统 - 项目列表</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/project.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="project-container">
            <a href="homepage.php" class="back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                返回首页
            </a>

            <div class="project-header">
                <h1>项目列表</h1>
                <p>以下是系统中所有项目信息</p>
            </div>

            <?php if ($errorMsg): ?>
                <div class="error-message">
                    <?php echo $errorMsg; ?>
                </div>
            <?php elseif (empty($projects)): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <line x1="3" y1="9" x2="21" y2="9"/>
                        <line x1="9" y1="21" x2="9" y2="9"/>
                    </svg>
                    <p>暂无项目数据</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <?php foreach ($tableColumns as $col): ?>
                                <th><?php echo htmlspecialchars($col); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $row): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?php echo is_array($value) ? json_encode($value) : htmlspecialchars((string)$value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>