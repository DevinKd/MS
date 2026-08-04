<?php
/**
 * 退出登录
 * 销毁 Session 并重定向到登录页
 */
session_start();
$_SESSION = [];
session_destroy();
header('Location: login.php');
exit;