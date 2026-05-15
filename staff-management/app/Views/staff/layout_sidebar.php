<?php
$current_page = $current_page ?? 'dashboard';
$page_title   = $page_title ?? 'Staff Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=IM+Fell+English:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
    <?= $this->include('staff/style') ?>
</head>
<body>
    <?= $this->include('staff/sidebar') ?>
    <button class="mobile-menu-toggle" type="button" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="main-layout" id="mainLayout">
        <?= $this->renderSection('content') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initSidebar();
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainLayout = document.getElementById('mainLayout');

            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('active');
                return;
            }

            sidebar.classList.toggle('collapsed');
            mainLayout.classList.toggle('expanded');

            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainLayout = document.getElementById('mainLayout');

            if (window.innerWidth <= 992) {
                sidebar.classList.remove('collapsed');
                mainLayout.classList.remove('expanded');
                return;
            }

            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainLayout.classList.add('expanded');
            }
        }

        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');

            if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const mainLayout = document.getElementById('mainLayout');

            if (window.innerWidth <= 992) {
                sidebar.classList.remove('collapsed');
                mainLayout.classList.remove('expanded');
                return;
            }

            sidebar.classList.remove('active');
            initSidebar();
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
