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
            MAIN NAVIGATION
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('studio/dashboard')?>" 
                   class="nav-link <?= sidebar_route_matches(['studio/dashboard', 'dashboard', 'studio']) ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/bookings')?>" 
                   class="nav-link <?= sidebar_route_matches(['studio/bookings', 'bookings']) ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/schedule')?>" 
                   class="nav-link <?= sidebar_route_matches(['studio/schedule', 'schedule']) ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i>
                    <span>Schedule</span>
                </a>
            </li>
        </ul>
    </nav>

    <nav class="nav-section">
        <div class="nav-section-title">
            STUDIO MANAGEMENT
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('studio/gallery')?>" 
                   class="nav-link <?= sidebar_route_matches(['studio/gallery', 'gallery']) ? 'active' : '' ?>">
                    <i class="fas fa-images"></i>
                    <span>Gallery</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/feedback')?>" 
                   class="nav-link <?= sidebar_route_matches(['studio/feedback', 'feedback']) ? 'active' : '' ?>">
                    <i class="fas fa-comment-dots"></i>
                    <span>Reviews & Feedback</span>
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
                <a href="<?= site_url('studio/profile')?>" 
                   class="nav-link <?= sidebar_route_matches(['studio/profile', 'profile']) ? 'active' : '' ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/logout')?>" 
                   class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
