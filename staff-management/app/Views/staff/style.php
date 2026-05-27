<style>
    :root {
        --bg-color: #f5f3f0;
        --surface-color: #ffffff;
        --text-main: #3b2a18;
        --text-muted: #8b7d6b;
        --primary-color: #8b7d6b;
        --primary-hover: #6d5d4d;
        --primary-light: #f0ede8;
        --border-color: #e8e3db;
        --success-color: #28a745;
        --success-bg: #d4edda;
        --warning-color: #856404;
        --warning-bg: #fff3cd;
        --info-color: #084298;
        --info-bg: #cfe2ff;
        --danger-color: #dc3545;
        --danger-bg: #f8d7da;
        --pending-color: #856404;
        --pending-bg: #fff3cd;
        --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 6px 20px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-main);
        overflow-x: hidden;
    }

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 220px;
        height: 100vh;
        background-color: #8b7d6b;
        color: #fff;
        overflow-y: auto;
        z-index: 1000;
        padding-bottom: 20px;
        transition: var(--transition);
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar.collapsed .sidebar-title,
    .sidebar.collapsed .nav-section-title,
    .sidebar.collapsed .nav-link span {
        display: none;
    }

    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 12px;
    }

    .sidebar-header {
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-logo-icon {
        width: 40px;
        height: 40px;
        background: transparent;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .sidebar-logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        cursor: pointer;
        background: transparent;
    }

    .sidebar-title {
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
        color: #fff;
    }

    .nav-section {
        padding: 15px 12px 10px;
    }

    .nav-section-title {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.55);
        margin-bottom: 8px;
        padding: 0 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nav-menu {
        list-style: none;
    }

    .nav-item {
        margin-left: -2em;
        margin-bottom: 3px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        border-radius: 6px;
        font-size: 13px;
        transition: var(--transition);
        position: relative;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.12);
        color: #fff;
        transform: translateX(3px);
    }

    .nav-link.active {
        background-color: #fff;
        color: #8b7d6b;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background-color: #8b7d6b;
        border-radius: 0 4px 4px 0;
    }

    .nav-link.active i {
        color: #6f4e37;
    }

    .main-layout {
        margin-left: 220px;
        min-height: 100vh;
        background: var(--bg-color);
        transition: var(--transition);
    }

    .main-layout.expanded {
        margin-left: 70px;
    }

    .top-header {
        background-color: var(--bg-color);
        padding: 18px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        position: sticky;
        top: 0;
        z-index: 999;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .welcome-section {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .admin-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        border: 2px solid #d4cfc5;
    }

    .welcome-text h2 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-main);
        margin: 0;
    }

    .welcome-text p {
        font-size: 12px;
        color: var(--text-muted);
        margin: 0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: none;
        background: #fff;
        color: var(--text-main);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .icon-btn:hover {
        background: var(--primary-light);
        color: var(--text-main);
    }

    .dashboard-content {
        padding: 30px 35px;
    }

    .page-header {
        margin-bottom: 22px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 13px;
        margin: 0;
    }

    .gold-line {
        width: 60px;
        height: 2px;
        background: #c19a6b;
        border-radius: 2px;
        margin: 10px 0 14px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .hero-panel {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 20px;
        box-shadow: var(--shadow-sm);
    }

    .hero-kicker {
        display: inline-block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .hero-copy h1 {
        margin: 0 0 8px;
        font-size: 24px;
        color: var(--text-main);
        line-height: 1.25;
    }

    .hero-copy p {
        margin: 0;
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .hero-metrics {
        display: grid;
        gap: 10px;
    }

    .hero-metric {
        background: #f9f8f6;
        border: 1px solid #f0ede8;
        border-radius: var(--radius-sm);
        padding: 12px;
    }

    .hero-metric span {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 4px;
        letter-spacing: 0.05em;
    }

    .hero-metric strong {
        font-size: 22px;
        color: var(--text-main);
    }

    .stat-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 18px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary-color);
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-info h3 {
        font-size: 12px;
        color: var(--text-muted);
        margin: 0 0 5px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-info p {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color: var(--text-main);
    }

    .bottom-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }

    .mini-card,
    .card,
    .info-card,
    .form-card {
        background: #fff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .mini-card,
    .info-card,
    .form-card {
        padding: 20px;
    }

    .mini-card-header,
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0ede8;
    }

    .mini-card-header h4,
    .card-header h3 {
        font-size: 15px;
        margin: 0;
        color: var(--text-main);
        font-weight: 600;
    }

    .card-body {
        padding: 0;
    }

    .mini-card-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .assignment-row,
    .booking-item,
    .assignment-card {
        background: #f9f8f6;
        border: 1px solid #f0ede8;
        border-left: 3px solid var(--primary-color);
        border-radius: var(--radius-sm);
        padding: 12px;
        transition: var(--transition);
    }

    .assignment-row:hover,
    .booking-item:hover,
    .assignment-card:hover {
        background: var(--primary-light);
    }

    .assignment-info,
    .booking-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 8px;
    }

    .assignment-venue {
        font-size: 11px;
        color: var(--text-muted);
    }

    .assignment-meta,
    .booking-meta,
    .card-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        font-size: 11px;
        color: var(--text-muted);
    }

    .assignment-reference {
        margin-top: 5px;
        font-size: 10px;
        color: var(--primary-color);
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    .action-stack {
        gap: 10px;
    }

    .action-link {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        background: #f9f8f6;
        border: 1px solid #f0ede8;
        border-radius: 8px;
        padding: 12px;
        color: var(--text-main);
        transition: var(--transition);
    }

    .action-link:hover {
        background: var(--primary-light);
        color: var(--text-main);
        transform: translateX(2px);
    }

    .action-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #f0ede8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .action-link strong {
        display: block;
        font-size: 13px;
    }

    .action-link small {
        display: block;
        font-size: 11px;
        color: var(--text-muted);
    }

    .assignment-status,
    .status-badge,
    .badge {
        display: inline-block;
        padding: 3px 9px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending { background: var(--pending-bg); color: var(--pending-color); }
    .status-confirmed { background: var(--success-bg); color: #155724; }
    .status-approved { background: var(--success-bg); color: #155724; }
    .status-completed { background: var(--info-bg); color: var(--info-color); }
    .bg-success { background: var(--success-bg); color: #155724; }
    .bg-secondary { background: #e9ecef; color: #495057; }
    .bg-danger { background: var(--danger-bg); color: #721c24; }
    .bg-info,
    .bg-primary,
    .badge.bg-primary,
    .badge.bg-info { background: var(--info-bg); color: var(--info-color); }

    .view-all {
        font-size: 11px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .table-container,
    .table-responsive {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: auto;
        box-shadow: var(--shadow-sm);
    }

    .table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 12px;
        border-bottom: 1px solid #f0ede8;
        font-size: 13px;
        vertical-align: middle;
    }

    .table th {
        background: #f9f8f6;
        color: var(--text-main);
        font-weight: 600;
    }

    .table tr:hover td {
        background: #faf9f7;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .form-control,
    select.form-control,
    textarea.form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d4cfc5;
        border-radius: 8px;
        font-size: 13px;
        color: var(--text-main);
        background: #fff;
        outline: none;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(139, 125, 107, 0.15);
    }

    .form-text {
        display: block;
        margin-top: 4px;
        font-size: 11px;
        color: var(--text-muted);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .btn-primary,
    .btn-secondary,
    .btn-warning,
    .btn-danger,
    .btn-info,
    .btn-sm {
        border: none;
        text-decoration: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        padding: 9px 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--primary-color);
        color: #fff;
    }

    .btn-primary:hover { background: var(--primary-hover); color: #fff; }

    .btn-secondary { background: #e9ecef; color: #495057; }
    .btn-secondary:hover { background: #dde1e5; color: #495057; }

    .btn-warning { background: #fff3cd; color: #856404; }
    .btn-danger { background: #f8d7da; color: #721c24; }
    .btn-info { background: #d1ecf1; color: #0c5460; }

    .btn-sm {
        padding: 6px 8px;
        font-size: 11px;
        border-radius: 6px;
        background: #f0ede8;
        color: var(--text-main);
    }

    .card-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .info-item label {
        display: block;
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .info-item p {
        margin: 0;
        font-size: 14px;
        color: var(--text-main);
    }

    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    .ftab {
        border: 1px solid #d4cfc5;
        background: #fff;
        color: var(--text-main);
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px;
        padding: 7px 12px;
        cursor: pointer;
        transition: var(--transition);
    }

    .ftab.active,
    .ftab:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
    }

    .ftab-count {
        margin-left: 4px;
        opacity: 0.9;
    }

    .search-wrap {
        position: relative;
        margin-bottom: 16px;
    }

    .search-input {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 1px solid #d4cfc5;
        border-radius: 8px;
        font-size: 13px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 13px;
    }

    .empty-state {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 40px 20px;
        text-align: center;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 34px;
        margin-bottom: 10px;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .empty-sub {
        font-size: 13px;
    }

    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }

    .spinner-border {
        width: 46px;
        height: 46px;
        border: 4px solid rgba(255, 255, 255, 0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .toast-notification {
        position: fixed;
        right: 20px;
        bottom: 20px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-left: 3px solid var(--info-color);
        border-radius: 8px;
        padding: 12px 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 3000;
        opacity: 0;
        transform: translateY(12px);
        transition: var(--transition);
    }

    .toast-notification.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast-success { border-left-color: var(--success-color); }
    .toast-error { border-left-color: var(--danger-color); }
    .toast-info { border-left-color: var(--info-color); }

    .toast-close {
        border: none;
        background: none;
        color: var(--text-muted);
        cursor: pointer;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .alert {
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 12px;
        font-size: 13px;
    }

    .alert-success { background: var(--success-bg); color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background: var(--danger-bg); color: #721c24; border: 1px solid #f5c6cb; }

    .pagination {
        margin-top: 14px;
    }

    .mobile-menu-toggle {
        display: none;
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        background: var(--primary-color);
        color: #fff;
        z-index: 1001;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 1200px) {
        .stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 992px) {
        .sidebar {
            left: -220px;
        }

        .sidebar.active {
            left: 0;
        }

        .main-layout {
            margin-left: 0;
        }

        .mobile-menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-content {
            padding: 20px;
        }

        .hero-panel {
            grid-template-columns: 1fr;
        }

        .bottom-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .top-header {
            padding: 16px 18px;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }
    }
    /* UI Stabilization Helpers: normalize buttons and badges */
    .btn {
        padding: 0.45rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }

    .btn + .btn {
        margin-left: 0.5rem;
    }

    .badge {
        border-radius: 0.6rem;
        padding: 0.35rem 0.6rem;
        font-size: 0.85rem;
    }
    /* ===== MUTED PALETTE & STANDARDIZATION OVERRIDES ===== */
    :root{
        --muted-bg: #f7f8fa;
        --muted-card: #ffffff;
        --muted-border: #eceeef;
        --muted-text: #394149;
        --muted-subtext: #6c757d;
        --muted-accent: #6b7f8f; /* muted blue-gray */
        --sidebar-bg-soft: #8b7d6b; /* brown */
        --sidebar-color-soft: #ffffff;
    }

    /* Sidebar: lighter, low-contrast background */
    .sidebar {
        background-color: var(--sidebar-bg-soft) !important;
        color: var(--sidebar-color-soft) !important;
    }
    .sidebar .nav-link {
        color: rgba(255,255,255,0.9) !important;
    }
    .sidebar .nav-link.active {
        background-color: #ffffff !important;
        color: #8b7d6b !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12) !important;
    }
    .sidebar .nav-link.active::before {
        background-color: #8b7d6b !important;
    }
    .sidebar .nav-link.active i {
        color: #8b7d6b !important;
    }

    /* Soften header and page background */
    .top-header, .main-layout, .dashboard-content {
        background-color: var(--muted-bg) !important;
    }

    /* Cards: subtle border, smaller shadow, consistent radius */
    .card, .stat-card, .chart-card, .mini-card, .table-card, .form-card {
        background-color: var(--muted-card) !important;
        border: 1px solid var(--muted-border) !important;
        box-shadow: 0 1px 6px rgba(18,24,28,0.06) !important;
        border-radius: 10px !important;
        padding: 18px !important;
    }

    /* Stat icons and bars: use muted accent, remove heavy gradients */
    .stat-icon, .bar, .event-badge, .status-indicator {
        background: var(--muted-accent) !important;
        color: #ffffff !important;
        box-shadow: none !important;
    }

    /* Buttons: muted primary, outline for secondary actions */
    .view-calendar-btn, .add-testimonial-btn, .add-staff-btn, .add-event-btn,
    .upload-btn, .assign-btn, .btn-approve, .btn-edit,
    .btn-primary {
        background-color: var(--muted-accent) !important;
        color: white !important;
        box-shadow: none !important;
    }
    .icon-btn, .filter-select, .filter-dropdown, .form-input, .form-select, .form-control {
        border-color: var(--muted-border) !important;
    }

    /* Notifications & toasts: softer border */
    .notification-menu, .toast-notification {
        border: 1px solid var(--muted-border) !important;
        box-shadow: 0 6px 20px rgba(18,24,28,0.06) !important;
    }

    /* Tables: cleaner, more subtle row hover */
    .table tr:hover td {
        background-color: #fbfcfd !important;
    }

    /* Reduce prominence of floating notification button */
    .mobile-menu-toggle, .spinner-border {
        background: var(--muted-accent) !important;
        box-shadow: 0 6px 16px rgba(27,38,46,0.06) !important;
        border: 2px solid #fff !important;
    }

    /* Status badge subtle tones */
    .status-paid { background-color: #e9f7ef !important; color: #17643a !important; }
    .status-pending { background-color: #fff7e6 !important; color: #7a5b00 !important; }
    .status-refunded { background-color: #fff1f2 !important; color: #772029 !important; }

</style>
