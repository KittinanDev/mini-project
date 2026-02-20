<?php

if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

$title = $title ?? 'Contact Form + Email Sender';
$flash = $flash ?? null;
$viewName = $viewName ?? 'home.php';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title); ?></title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #0b0f14;
            --grid: rgba(255, 255, 255, 0.06);
            --card: #111826;
            --card-strong: #141f2f;
            --ink: #f4f6f9;
            --muted: #a7b0bc;
            --accent: #25c4a4;
            --accent-strong: #1aa386;
            --accent-warm: #f4b843;
            --danger: #ef5b5b;
            --border: #1f2a3a;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: "Prompt", "Kanit", "Tahoma", sans-serif;
            background:
                linear-gradient(135deg, rgba(37, 196, 164, 0.08), rgba(244, 184, 67, 0.08)),
                repeating-linear-gradient(0deg, var(--grid), var(--grid) 1px, transparent 1px, transparent 32px),
                repeating-linear-gradient(90deg, var(--grid), var(--grid) 1px, transparent 1px, transparent 32px),
                var(--bg);
            color: var(--ink);
        }
        header {
            padding: 28px 32px 22px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(160deg, rgba(17, 24, 38, 0.9), rgba(7, 10, 16, 0.9));
        }
        .header-inner {
            max-width: 1100px;
            margin: 0 auto;
        }
        header h1 {
            margin: 0 0 6px 0;
            font-size: 30px;
            letter-spacing: 0.4px;
        }
        header p {
            margin: 0;
            color: var(--muted);
        }
        nav {
            margin-top: 16px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        nav a {
            color: var(--ink);
            text-decoration: none;
            border: 1px solid var(--border);
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 14px;
            background: rgba(17, 24, 38, 0.6);
        }
        nav a:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        main {
            max-width: 1100px;
            margin: 32px auto 48px;
            padding: 0 20px;
        }
        .panel {
            background: rgba(17, 24, 38, 0.85);
            border: 1px solid var(--border);
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.35);
        }
        .section {
            padding: 22px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: var(--card);
            margin-bottom: 20px;
        }
        .section:last-child {
            margin-bottom: 0;
        }
        .section-title {
            margin: 0 0 12px 0;
            font-size: 22px;
        }
        .eyebrow {
            text-transform: uppercase;
            font-size: 12px;
            color: var(--accent-warm);
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        .lead {
            font-size: 18px;
            color: var(--muted);
            margin: 0 0 16px 0;
        }
        .pill {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            font-size: 13px;
            color: var(--muted);
            margin-right: 8px;
            margin-bottom: 8px;
            background: rgba(12, 16, 24, 0.8);
        }
        .grid-2 {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .card-mini {
            padding: 16px;
            border-radius: 14px;
            background: var(--card-strong);
            border: 1px solid var(--border);
        }
        .list {
            margin: 0;
            padding-left: 20px;
            color: var(--muted);
            line-height: 1.8;
        }
        .workflow {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        }
        .workflow-step {
            padding: 14px;
            border-radius: 12px;
            border: 1px dashed rgba(255, 255, 255, 0.2);
            background: rgba(17, 24, 38, 0.9);
            text-align: center;
        }
        .workflow-step span {
            display: block;
            color: var(--muted);
            font-size: 13px;
            margin-top: 6px;
        }
        .contact-shell {
            display: grid;
            gap: 18px;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.9fr);
        }
        .contact-aside {
            background: linear-gradient(145deg, rgba(37, 196, 164, 0.15), rgba(37, 196, 164, 0.05));
            border: 1px solid rgba(37, 196, 164, 0.35);
            border-radius: 16px;
            padding: 20px;
        }
        .contact-main {
            background: rgba(10, 14, 22, 0.7);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }
        .contact-list {
            margin: 16px 0 0 0;
            padding: 0;
            list-style: none;
            color: var(--muted);
            line-height: 1.9;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .form-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-field label {
            font-size: 14px;
            color: var(--muted);
        }
        .form-field input,
        .form-field textarea {
            background: rgba(6, 10, 16, 0.85);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            font-family: inherit;
            font-size: 15px;
            color: var(--ink);
        }
        .form-field input:focus,
        .form-field textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 196, 164, 0.2);
        }
        .form-field input:invalid:focus,
        .form-field textarea:invalid:focus {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 91, 91, 0.2);
        }
        .form-field textarea {
            min-height: 140px;
            resize: vertical;
        }
        .btn {
            border: none;
            background: var(--accent);
            color: #0a0f14;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 999px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }
        .btn:hover {
            background: var(--accent-strong);
        }
        .btn-spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(10, 15, 20, 0.4);
            border-top-color: rgba(10, 15, 20, 0.9);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            opacity: 0;
        }
        .btn:active .btn-spinner {
            opacity: 1;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th,
        td {
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 14px;
        }
        th {
            color: var(--muted);
            font-weight: 600;
        }
        .flash {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            border: 1px solid transparent;
        }
        .flash.success {
            background: rgba(37, 196, 164, 0.18);
            color: #bff3e7;
            border-color: rgba(37, 196, 164, 0.4);
        }
        .flash.error {
            background: rgba(239, 91, 91, 0.16);
            color: #ffd1d1;
            border-color: rgba(239, 91, 91, 0.4);
        }
        .muted {
            color: var(--muted);
        }
        footer {
            text-align: center;
            color: var(--muted);
            padding: 32px 16px 40px;
        }
        @media (max-width: 960px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            .contact-shell {
                grid-template-columns: 1fr;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 720px) {
            header {
                padding: 20px;
            }
            header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="header-inner">
        <h1>Contact Form + Email Sender</h1>
        <p>ส่งข้อความติดต่อระบบอัตโนมัติ ผ่าน SMTP</p>
    </div>
</header>
<main>
    <?php if ($flash): ?>
        <div class="flash <?php echo e($flash['type']); ?>">
            <?php echo e($flash['message']); ?>
        </div>
    <?php endif; ?>
    <div class="panel">
        <?php require dirname(__DIR__) . '/templates/' . $viewName; ?>
    </div>
</main>
<footer>
    Contact Form + Email Sender System
</footer>
</body>
</html>
