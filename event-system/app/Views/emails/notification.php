<?php
$status = strtolower((string) ($status ?? 'info'));
$badgeClass = 'badge-secondary';

if (in_array($status, ['approved', 'completed', 'paid', 'fully paid', 'refunded'], true)) {
    $badgeClass = 'badge-success';
} elseif (in_array($status, ['pending', 'assigned'], true)) {
    $badgeClass = 'badge-warning';
} elseif (in_array($status, ['cancelled', 'expired'], true)) {
    $badgeClass = 'badge-danger';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Farmease Notification') ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f3f5f7;
            color: #24313f;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
        }
        .wrapper {
            width: 100%;
            padding: 20px 12px;
            box-sizing: border-box;
        }
        .container {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dbe2e8;
            border-radius: 10px;
            overflow: hidden;
        }
        .header {
            background: #1f5f75;
            color: #ffffff;
            padding: 18px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        .brand {
            margin-top: 4px;
            font-size: 12px;
            opacity: 0.9;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .content {
            padding: 20px;
        }
        .intro {
            margin: 0 0 14px 0;
            font-size: 14px;
        }
        .status {
            margin: 0 0 18px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .badge-success { background: #d9f3e4; color: #196b42; }
        .badge-warning { background: #fff3d0; color: #8a6110; }
        .badge-danger { background: #f9d7dc; color: #8b2735; }
        .badge-secondary { background: #e7ecf0; color: #425466; }
        .section {
            margin-bottom: 16px;
            border: 1px solid #e5ebf0;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-title {
            margin: 0;
            padding: 10px 12px;
            background: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 1px solid #e5ebf0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table td {
            padding: 8px 12px;
            font-size: 13px;
            border-bottom: 1px solid #edf2f6;
            vertical-align: top;
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .table td:first-child {
            color: #5a6b7b;
            width: 42%;
        }
        .notes {
            margin: 0;
            padding: 10px 12px 10px 26px;
            font-size: 13px;
        }
        .footer {
            padding: 14px 20px;
            border-top: 1px solid #e5ebf0;
            background: #f8fafc;
            color: #607286;
            font-size: 12px;
        }
        @media screen and (max-width: 600px) {
            .content {
                padding: 16px;
            }
            .table td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
            .table td:first-child {
                font-weight: 600;
                padding-bottom: 2px;
            }
            .table td + td {
                padding-top: 0;
                padding-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1><?= esc($title ?? 'Notification') ?></h1>
                <div class="brand">Farmease Event System</div>
            </div>

            <div class="content">
                <?php if (!empty($intro)): ?>
                    <p class="intro"><?= esc($intro) ?></p>
                <?php endif; ?>

                <?php if (!empty($status)): ?>
                    <p class="status">
                        <span class="badge <?= esc($badgeClass) ?>"><?= esc($status) ?></span>
                    </p>
                <?php endif; ?>

                <?php if (!empty($details) && is_array($details)): ?>
                    <div class="section">
                        <p class="section-title">Booking Information</p>
                        <table class="table" role="presentation">
                            <?php foreach ($details as $label => $value): ?>
                                <tr>
                                    <td><?= esc((string) $label) ?></td>
                                    <td><?= esc((string) $value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>

                <?php if (!empty($summary) && is_array($summary)): ?>
                    <div class="section">
                        <p class="section-title">Summary</p>
                        <table class="table" role="presentation">
                            <?php foreach ($summary as $label => $value): ?>
                                <tr>
                                    <td><?= esc((string) $label) ?></td>
                                    <td><?= esc((string) $value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>

                <?php if (!empty($notes) && is_array($notes)): ?>
                    <div class="section">
                        <p class="section-title">Notes</p>
                        <ul class="notes">
                            <?php foreach ($notes as $note): ?>
                                <li><?= esc((string) $note) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="footer">
                <?= esc($footerText ?? 'Farmease automated notification') ?>
            </div>
        </div>
    </div>
</body>
</html>
