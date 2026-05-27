<?php
if (!function_exists('sidebar_url_segments')) {
    function sidebar_url_segments(?string $path = null): array
    {
        $path = parse_url($path ?? service('uri')->getPath(), PHP_URL_PATH) ?? '';

        return array_values(array_filter(explode('/', trim($path, '/')), static function (string $segment): bool {
            $segment = strtolower(trim($segment));

            return $segment !== '' && $segment !== 'public' && $segment !== 'index.php';
        }));
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
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img onclick="toggleSidebar()" src="<?= base_url('images/LOGO NG SAN ISIDRO.png') ?>" alt="San Isidro Logo">
            </div>
            <div class="sidebar-title">San Isidro Labrador<br>Resort and Leisure Farm</div>
        </div>
    </div>

    <nav class="nav-section">
        <div class="nav-section-title">
            WORKFLOW
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('staff/dashboard')?>"
                   class="nav-link <?= sidebar_route_matches(['staff/dashboard', 'dashboard']) ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('staff/schedule')?>"
                   class="nav-link <?= sidebar_route_matches(['staff/schedule', 'schedule']) ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>My Schedule</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('assignment')?>"
                   class="nav-link <?= sidebar_route_matches(['assignment', 'assignments', 'staff/assignments', 'staff/assignToBooking']) ? 'active' : '' ?>">
                    <i class="fas fa-tasks"></i>
                    <span>Assignments</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('availability')?>"
                   class="nav-link <?= sidebar_route_matches(['availability', 'staff/availability']) ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i>
                    <span>Availability</span>
                </a>
            </li>
        </ul>
    </nav>

    <nav class="nav-section">
        <div class="nav-section-title">
            ACCOUNT
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('staff/profile')?>" 
                   class="nav-link <?= sidebar_route_matches(['staff/profile', 'profile']) ? 'active' : '' ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('staff/logout')?>" 
                   class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
