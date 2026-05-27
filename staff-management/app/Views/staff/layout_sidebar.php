<?php
$current_page = $current_page ?? null;

if ($current_page === null) {
    if (!function_exists('sidebar_url_segments')) {
        function sidebar_url_segments(?string $path = null): array
        {
            $path = parse_url($path ?? service('uri')->getPath(), PHP_URL_PATH) ?? '';
            $segments = array_values(array_filter(explode('/', trim($path, '/')), static function (string $segment): bool {
                $segment = strtolower(trim($segment));

                return $segment !== '' && $segment !== 'public' && $segment !== 'index.php';
            }));

            return $segments;
        }
    }

    if (!function_exists('sidebar_route_matches')) {
        function sidebar_route_matches(string|array $routes, ?string $path = null): bool
        {
            $currentSegments = sidebar_url_segments($path);

            foreach ((array) $routes as $route) {
                $routeSegments = sidebar_url_segments($route);

                if ($routeSegments === [] || count($currentSegments) < count($routeSegments)) {
                    continue;
                }

                if (array_slice($currentSegments, -count($routeSegments)) === $routeSegments) {
                    return true;
                }
            }

            return false;
        }
    }

    $currentPageMap = [
        'dashboard' => ['staff/dashboard', 'dashboard'],
        'schedule' => ['staff/schedule', 'schedule'],
        'assignments' => ['assignment', 'assignments', 'staff/assignments'],
        'assign-booking' => ['staff/assignToBooking'],
        'availability' => ['availability', 'staff/availability'],
        'profile' => ['staff/profile', 'profile'],
        'team' => ['staff-management', 'staff/management'],
    ];

    $current_page = 'dashboard';

    foreach ($currentPageMap as $pageName => $routes) {
        if (sidebar_route_matches($routes)) {
            $current_page = $pageName;
            break;
        }
    }
}

$page_title = $page_title ?? 'Staff Portal';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <?= $this->include('staff/style') ?>
</head>
<body>
    <?= $this->include('staff/sidebar', ['current_page' => $current_page]) ?>
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
