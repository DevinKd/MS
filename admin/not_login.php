<?php
/**
 * 未登录处理
 * 未登录时直接重定向到登录页面
 *
 * 使用方式：
 *   require_once __DIR__ . '/not_login.php';  // 自动重定向到登录页
 */
header('Location: ../admin/login.php');
exit;