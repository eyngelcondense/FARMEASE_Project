    <style>
        /* ===== RESET & BASE STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f3f0;
            color: #3b2a18;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        /* ===== SIDEBAR STYLES ===== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background-color: #8b7d6b;
            color: white;
            overflow-y: auto;
            z-index: 1000;
            padding-bottom: 20px;
            transition: all 0.3s ease;
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

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .sidebar-header {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .sidebar-logo-icon {
            width: 40px;
            height: 40px;
            background-color: transparent;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            cursor: pointer;
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: transparent;
        }

        .sidebar-logo-icon i {
            color: #8b7d6b;
            font-size: 20px;
        }

        .sidebar-title {
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
            color: white;
            transition: opacity 0.3s;
        }

        .nav-section {
            padding: 15px 12px 10px;
        }

        .nav-section-title {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 8px;
            padding: 0 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: opacity 0.3s;
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
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 400;
            transition: all 0.3s;
            position: relative;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.12);
            color: white;
            transform: translateX(3px);
        }

        .nav-link.active {
            background-color: #ffffff;
            color: #6f4e37;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
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
            transform: scale(1.1);
        }

        .nav-link i {
            font-size: 16px;
            width: 18px;
            text-align: center;
            transition: transform 0.3s;
        }

        /* ===== MAIN LAYOUT STYLES ===== */
        .main-layout {
            margin-left: 220px;
            margin-right: 0;
            min-height: 100vh;
            transition: all 0.3s ease;
            background-color: #f5f3f0;
        }

        .main-layout.expanded {
            margin-left: 70px;
        }

        /* ===== HEADER STYLES ===== */
        .top-header {
            background-color: #f5f3f0;
            padding: 18px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: none;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .welcome-section {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .admin-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #8b7d6b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            overflow: hidden;
            border: 2px solid #d4cfc5;
        }

        .admin-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .welcome-text h2 {
            font-size: 18px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        .welcome-text p {
            font-size: 12px;
            color: #8b7d6b;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            justify-content: flex-end;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #d4cfc5;
            border-radius: 8px;
            background-color: white;
            font-size: 13px;
            color: #3b2a18;
        }

        .search-box input::placeholder {
            color: #a89b88;
        }

        .search-box input:focus {
            outline: none;
            border-color: #8b7d6b;
            background-color: white;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a89b88;
            font-size: 14px;
        }

        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background-color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            flex-shrink: 0;
        }

        .icon-btn:hover {
            background-color: #e8e3db;
        }

        .icon-btn i {
            font-size: 16px;
            color: #3b2a18;
        }

        .icon-btn .badge {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 16px;
            height: 16px;
            background-color: #d9534f;
            color: white;
            border-radius: 50%;
            font-size: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* ===== DASHBOARD CONTENT ===== */
        .dashboard-content {
            padding: 30px 35px;
        }

        /* ===== STATS ROW ===== */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 1400px) {
            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e8e3db;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border-color: #8b7d6b;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #8b7d6b, #6d5d4d);
            color: white;
        }

        .stat-info h3 {
            font-size: 13px;
            font-weight: 500;
            color: #8b7d6b;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-info p {
            font-size: 24px;
            font-weight: 700;
            color: #3b2a18;
            margin: 0;
        }

        /* ===== CHART ROW ===== */
        .chart-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 1024px) {
            .chart-row {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #e8e3db;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0ede8;
        }

        .chart-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-controls {
            display: flex;
            gap: 10px;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #d4cfc5;
            border-radius: 6px;
            background-color: white;
            color: #3b2a18;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select:hover,
        .filter-select:focus {
            border-color: #8b7d6b;
            outline: none;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        .chart-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .chart-stats {
                grid-template-columns: 1fr;
            }
        }

        .chart-stat-item {
            background: #f9f8f6;
            padding: 12px;
            border-radius: 8px;
            border-left: 3px solid #8b7d6b;
        }

        .chart-stat-item h4 {
            font-size: 11px;
            color: #8b7d6b;
            margin: 0 0 6px 0;
            font-weight: 500;
        }

        .chart-stat-item p {
            font-size: 18px;
            font-weight: 700;
            color: #3b2a18;
            margin: 0;
        }

        /* ===== BOTTOM ROW ===== */
        .bottom-row {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .bottom-row {
                grid-template-columns: 1fr;
            }
        }

        .packages-chart {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #e8e3db;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .bar-chart {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .bar-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .bar-label {
            font-size: 12px;
            font-weight: 500;
            color: #3b2a18;
        }

        .bar-container {
            display: flex;
            align-items: center;
            height: 25px;
            background-color: #f0ede8;
            border-radius: 6px;
            overflow: hidden;
        }

        .bar {
            height: 100%;
            background: linear-gradient(90deg, #8b7d6b, #6d5d4d);
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-size: 11px;
            font-weight: 600;
        }

        .side-cards {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .mini-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e8e3db;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .mini-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0ede8;
        }

        .mini-card-header h4 {
            font-size: 14px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        .view-all {
            font-size: 11px;
            color: #8b7d6b;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .view-all:hover {
            color: #3b2a18;
        }

        .mini-card-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 300px;
            overflow-y: auto;
        }

        .booking-item,
        .event-item {
            padding: 12px;
            background-color: #f9f8f6;
            border-radius: 8px;
            border-left: 3px solid #8b7d6b;
            transition: all 0.3s;
        }

        .booking-item:hover,
        .event-item:hover {
            background-color: #f0ede8;
            transform: translateX(3px);
        }

        .booking-info,
        .event-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 8px;
        }

        .booking-info strong,
        .event-info strong {
            font-size: 12px;
            color: #3b2a18;
        }

        .booking-package,
        .event-venue {
            font-size: 11px;
            color: #8b7d6b;
        }

        .booking-meta,
        .event-meta {
            display: flex;
            gap: 12px;
            font-size: 10px;
            color: #8b7d6b;
        }

        .booking-status {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .booking-status.status-pending,
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .booking-status.status-confirmed,
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .booking-status.status-completed,
        .status-completed {
            background-color: #cfe2ff;
            color: #084298;
        }

        /* ===== MOBILE MENU TOGGLE ===== */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #8b7d6b;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 999;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background-color: #6d5d4d;
            transform: scale(1.1);
        }

        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar {
                position: fixed;
                left: -220px;
                transition: all 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .main-layout {
                margin-left: 0;
            }

            .top-header {
                margin-left: 0;
            }

            .dashboard-content {
                padding: 20px;
            }
        }

        /* ===== LOADING OVERLAY ===== */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .loading-overlay.show {
            display: flex;
        }

        .spinner-border {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ===== TOAST NOTIFICATIONS ===== */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            z-index: 3000;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            max-width: 350px;
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast-notification.toast-success {
            border-left: 3px solid #28a745;
        }

        .toast-notification.toast-error {
            border-left: 3px solid #dc3545;
        }

        .toast-notification.toast-info {
            border-left: 3px solid #17a2b8;
        }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #8b7d6b;
            font-size: 16px;
            margin-left: auto;
            padding: 0;
        }

        /* ===== NOTIFICATION MENU ===== */
        .notification-dropdown {
            position: relative;
        }

        .notification-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 350px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: 1px solid #e8e3db;
            padding: 0;
            margin-top: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .notification-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            padding: 15px 20px;
            border-bottom: 1px solid #f0ede8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h4 {
            font-size: 14px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        .mark-all-read {
            background: none;
            border: none;
            color: #8b7d6b;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.3s;
        }

        .mark-all-read:hover {
            color: #3b2a18;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0ede8;
            display: flex;
            gap: 12px;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f9f8f6;
        }

        .notification-item.unread {
            background-color: #f9f8f6;
            font-weight: 500;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f0ede8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-content p {
            font-size: 12px;
            color: #3b2a18;
            margin: 0 0 6px 0;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 10px;
            color: #a89b88;
        }

        .notification-footer {
            padding: 12px 20px;
            border-top: 1px solid #f0ede8;
            text-align: center;
        }

        .view-all-notifications {
            font-size: 12px;
            color: #8b7d6b;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .view-all-notifications:hover {
            color: #3b2a18;
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
    .card, .stat-card, .chart-card, .mini-card, .table-card {
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
    .icon-btn, .filter-select, .filter-dropdown, .form-input, .form-select {
        border-color: var(--muted-border) !important;
    }

    /* Notifications & toasts: softer border */
    .notification-menu, .toast-notification {
        border: 1px solid var(--muted-border) !important;
        box-shadow: 0 6px 20px rgba(18,24,28,0.06) !important;
    }

    /* Tables: cleaner, more subtle row hover */
    .bookings-table tbody tr:hover,
    .payments-table tbody tr:hover,
    .venues-table tbody tr:hover,
    .table tr:hover td {
        background-color: #fbfcfd !important;
    }

    /* Reduce prominence of floating notification button */
    .mobile-menu-toggle, .floating-notification-btn {
        background: var(--muted-accent) !important;
        box-shadow: 0 6px 16px rgba(27,38,46,0.06) !important;
        border: 2px solid #fff !important;
    }

    /* Status badge subtle tones */
    .status-paid { background-color: #e9f7ef !important; color: #17643a !important; }
    .status-pending { background-color: #fff7e6 !important; color: #7a5b00 !important; }
    .status-refunded { background-color: #fff1f2 !important; color: #772029 !important; }

    </style>
