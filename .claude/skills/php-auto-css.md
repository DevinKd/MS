---
name: php-auto-css
description: 创建 PHP 文件时自动同步创建同名 CSS 文件到 css/ 目录，所有样式放在 CSS 文件中，PHP 中只保留 <link> 引用
triggers:
  - language: php
  - pattern: "<style"
  - pattern: "class="
  - action: extract_and_create
---

# PHP → CSS 自动配套生成规则

## 核心规则
当你创建一个新的 `.php` 页面或需要为 PHP 文件添加样式时，**必须在 `css/` 目录中同步创建同名 `.css` 文件**，所有样式代码放在 CSS 文件中，PHP 中只保留 `<link>` 引用。

## 命名规则
- 取 PHP 文件名（不含路径）去掉 `.php` 后缀
- 在 `css/` 目录下创建同名 `.css` 文件
- CSS 文件路径始终为 `css/文件名.css`

示例映射：
| PHP 文件 | CSS 文件 |
|----------|----------|
| `admin/dashboard.php` | `css/dashboard.css` |
| `page/project.php` | `css/project.css` |
| `login.php` | `css/login.css` |

> 如果 `css/` 目录不存在，**必须先创建目录**。

## 操作步骤

### 1. 创建 CSS 文件（在 PHP 文件之前或同时创建）
创建 `css/文件名.css`，内容模板：

```css
/* ============================================
   文件名 Styles
   文件名样式表
   ============================================ */

/* ========== 顶部导航栏 ========== */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 64px;
    /* ... 你的样式 ... */
}
```

### 2. 在 PHP 文件中引用 CSS
在 `<head>` 中添加（放在其他 CSS 之后）：
```html
<link rel="stylesheet" href="../css/文件名.css">
```

### 3. 迁移内联样式（如果已有 `<style>` 块）
- **提取所有样式**到对应的 CSS 文件
- **删除** PHP 文件中的 `<style>` 块
- 如果页面有特殊的 `<style>`（如错误页面独立样式），保留该内联样式但主页面样式全部移入 CSS

### 4. 保持 CSS 与 PHP 同步
- 每次修改 PHP 中的类名，同步更新 CSS
- 新增的样式写在对应 CSS 文件的末尾
- 始终遵循 `css/` 目录的规范，不随意创建嵌套子目录

## 例外情况
- **错误页面** (`die()`) 中用于独立展示的样式可以保留内联 `<style>`，这些不属于主页面样式
- **登录页 `css/login.css`** 是通用基础样式，其他页面可以通过 `<link>` 引用它作为全局基础
- 如果样式非常简单且只用于单个元素，可以不提取（但通常建议提取）