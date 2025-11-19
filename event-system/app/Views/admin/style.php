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
        .star{
            color: yellow;
        }

        .badge{
            color: black;
        }

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
        .sidebar.collapsed .quick-add-text,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .quick-add-btn {
            justify-content: center;
            padding: 10px;
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

        .sidebar-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: transform 0.3s;
        }

        .sidebar.collapsed .sidebar-toggle {
            transform: translateY(-50%) rotate(180deg);
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
            background-color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .quick-add-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: rgba(255,255,255,0.15);
            border: none;
            color: white;
            padding: 10px 12px;
            border-radius: 8px;
            width: 100%;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            margin-top: 15px;
            cursor: pointer;
        }

        .quick-add-btn:hover {
            background-color: rgba(255,255,255,0.25);
        }

        .quick-add-btn-icon {
            width: 28px;
            height: 28px;
            background-color: white;
            color: #8b7d6b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .quick-add-text {
            text-align: left;
            flex: 1;
            transition: opacity 0.3s;
        }

        .quick-add-text-title {
            font-weight: 600;
            font-size: 13px;
        }

        .quick-add-text-sub {
            font-size: 10px;
            opacity: 0.8;
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
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 400;
            transition: all 0.3s;
            position: relative;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(3px);
        }

        .nav-link.active {
            background-color: #6d5d4d;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background-color: white;
            border-radius: 0 4px 4px 0;
        }

        .nav-link.active i {
            color: white;
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
            padding: 30px 35px;
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

        /* ===== NOTIFICATION STYLES ===== */
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
            font-size: 11px;
            color: #8b7d6b;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .mark-all-read:hover {
            color: #6d5d4d;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            gap: 12px;
            padding: 15px 20px;
            border-bottom: 1px solid #f8f6f3;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f6f3;
        }

        .notification-item.unread {
            background-color: #f0f7ff;
        }

        .notification-item.unread:hover {
            background-color: #e6f2ff;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: #f8f6f3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .notification-item.unread .notification-icon {
            background-color: #e6f2ff;
        }

        .notification-content {
            flex: 1;
        }

        .notification-content p {
            font-size: 12px;
            color: #3b2a18;
            margin: 0 0 4px 0;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 10px;
            color: #8b7d6b;
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
            font-weight: 500;
        }

        .view-all-notifications:hover {
            color: #6d5d4d;
        }

        /* Floating Notification Button */
        .floating-notification-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #7c6a43, #6a5938);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(124, 106, 67, 0.3);
            z-index: 9998;
            transition: all 0.3s ease;
            border: 3px solid white;
        }

        .floating-notification-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(124, 106, 67, 0.4);
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 0.7rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Notification Panel */
        .notification-panel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 400px;
            max-height: 80vh;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .notification-panel.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        .notification-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9997;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .notification-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* ===== DASHBOARD STYLES ===== */
        .dashboard-content {
            padding: 25px 30px;
            background-color: #f5f3f0;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background-color: #8b7d6b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-info h3 {
            font-size: 12px;
            font-weight: 400;
            color: #8b7d6b;
            margin: 0 0 5px 0;
        }

        .stat-info p {
            font-size: 26px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        /* Chart Cards */
        .chart-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 25px;
        }

        .chart-card {
            background-color: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .chart-header h3 {
            font-size: 15px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-btn {
            padding: 6px 12px;
            border: 1px solid #d4cfc5;
            border-radius: 6px;
            background-color: white;
            font-size: 11px;
            color: #3b2a18;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }

        .filter-btn:hover {
            background-color: #f5f3f0;
        }

        .chart-stats {
            display: flex;
            gap: 25px;
            margin-bottom: 18px;
        }

        .chart-stat-item h4 {
            font-size: 11px;
            font-weight: 400;
            color: #8b7d6b;
            margin: 0 0 4px 0;
        }

        .chart-stat-item p {
            font-size: 20px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        .chart-container {
            height: 250px;
            position: relative;
        }

        /* Packages Bar Chart */
        .packages-chart {
            grid-column: 1 / -1;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 220px;
            gap: 8px;
            padding: 15px 0;
        }

        .bar-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .bar {
            width: 100%;
            background-color: #8b7d6b;
            border-radius: 4px 4px 0 0;
            transition: all 0.3s;
            position: relative;
            min-height: 20px;
        }

        .bar:hover {
            background-color: #7a6a58;
        }

        .bar-label {
            font-size: 9px;
            text-align: center;
            color: #5a4a3a;
            line-height: 1.2;
            max-width: 70px;
        }

        /* ===== PAGE HEADER STYLES ===== */
        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 20px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0 0 8px 0;
        }

        .page-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #8b7d6b;
            margin: 0 0 30px 0;
        }

        .page-header-card {
            background-color: white;
            border-radius: 10px;
            padding: 22px 25px;
            margin-bottom: 22px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        .page-header-card h1 {
            font-size: 20px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        /* ===== FILTER & SEARCH STYLES ===== */
        .filter-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 500;
            color: #3b2a18;
        }

        .filter-dropdown {
            padding: 8px 35px 8px 15px;
            border: 1px solid #d4cfc5;
            border-radius: 8px;
            background-color: white;
            font-size: 13px;
            color: #3b2a18;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233b2a18' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            min-width: 140px;
            transition: all 0.3s;
        }

        .filter-dropdown:focus {
            outline: none;
            border-color: #8b7d6b;
        }

        .search-box-bookings,
        .search-box-payments,
        .search-box-staff {
            position: relative;
            flex: 1;
            max-width: 320px;
        }

        .search-box-bookings input,
        .search-box-payments input,
        .search-box-staff input {
            width: 100%;
            padding: 8px 15px 8px 40px;
            border: 1px solid #d4cfc5;
            border-radius: 8px;
            background-color: white;
            font-size: 13px;
            color: #3b2a18;
            transition: all 0.3s;
        }

        .search-box-bookings input::placeholder,
        .search-box-payments input::placeholder,
        .search-box-staff input::placeholder {
            color: #a89b88;
        }

        .search-box-bookings input:focus,
        .search-box-payments input:focus,
        .search-box-staff input:focus {
            outline: none;
            border-color: #8b7d6b;
        }

        .search-box-bookings i,
        .search-box-payments i,
        .search-box-staff i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a89b88;
            font-size: 14px;
        }

        /* ===== BUTTON STYLES ===== */
        .view-calendar-btn,
        .add-testimonial-btn,
        .add-staff-btn,
        .add-event-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #3b2a18;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
            margin-left: auto;
        }

        .view-calendar-btn:hover,
        .add-testimonial-btn:hover,
        .add-staff-btn:hover,
        .add-event-btn:hover {
            background-color: #2a1f12;
        }

        .view-calendar-btn i,
        .add-testimonial-btn i,
        .add-staff-btn i,
        .add-event-btn i {
            font-size: 14px;
        }

        .upload-btn,
        .assign-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #3b2a18;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-btn:hover,
        .assign-btn:hover {
            background-color: #2a1f12;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .assign-btn:disabled {
            background-color: #c4b9a8;
            cursor: not-allowed;
            transform: none;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-approve,
        .btn-reject,
        .btn-edit,
        .btn-delete {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-approve,
        .btn-edit {
            background-color: #3b2a18;
            color: white;
        }

        .btn-approve:hover,
        .btn-edit:hover {
            background-color: #2a1f12;
        }

        .btn-reject,
        .btn-delete {
            background-color: #d9534f;
            color: white;
        }

        .btn-reject:hover,
        .btn-delete:hover {
            background-color: #c9302c;
        }

        .btn-approve-testimonial {
            background-color: rgba(92, 184, 92, 0.9);
            color: white;
        }

        .btn-approve-testimonial:hover {
            background-color: #449d44;
        }

        .btn-brown{
            background-color:  hsla(30, 22%, 29%, 1.00);
            color: #e8e3db;
        }

        /* ===== TABLE STYLES ===== */
        .table-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            overflow-x: auto;
        }

        .bookings-table,
        .payments-table,
        .venues-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 800px;
        }

        .bookings-table thead,
        .payments-table thead,
        .venues-table thead {
            background-color: #e8e3db;
        }

        .bookings-table thead th,
        .payments-table thead th,
        .venues-table thead th {
            padding: 12px 18px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #3b2a18;
            border: none;
        }

        .bookings-table thead th:first-child,
        .payments-table thead th:first-child,
        .venues-table thead th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .bookings-table thead th:last-child,
        .payments-table thead th:last-child,
        .venues-table thead th:last-child {
            border-radius: 0 8px 8px 0;
        }

        .bookings-table tbody tr,
        .payments-table tbody tr,
        .venues-table tbody tr {
            border-bottom: 1px solid #f0ede8;
            transition: background-color 0.3s;
        }

        .bookings-table tbody tr:hover,
        .payments-table tbody tr:hover,
        .venues-table tbody tr:hover {
            background-color: #faf8f5;
        }

        .bookings-table tbody tr:last-child,
        .payments-table tbody tr:last-child,
        .venues-table tbody tr:last-child {
            border-bottom: none;
        }

        .bookings-table tbody td,
        .payments-table tbody td,
        .venues-table tbody td {
            padding: 15px 18px;
            font-size: 13px;
            color: hsla(30, 22%, 29%, 1.00);
        }

        .booking-id,
        .payment-id {
            font-weight: 500;
            color: #8b7d6b;
        }

        .client-name,
        .venue-name {
            font-weight: 500;
            color: #3b2a18;
        }

        .amount {
            font-weight: 600;
            color: #3b2a18;
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-refunded {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-available {
            background-color: rgba(76, 175, 80, 0.15);
            color: #4caf50;
        }

        .status-assigned {
            background-color: rgba(255, 152, 0, 0.15);
            color: #ff9800;
        }

        .status-unavailable {
            background-color: rgba(244, 67, 54, 0.15);
            color: #f44336;
        }

        /* Category Badges */
        .category-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-corporate {
            background-color: #8b7d6b;
            color: white;
        }

        .badge-birthday {
            background-color: #7a9cc6;
            color: white;
        }

        .badge-wedding {
            background-color: #d4a5a5;
            color: white;
        }

        /* ===== CALENDAR STYLES ===== */
        .calendar-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            max-width: 1100px;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .calendar-nav-btn {
            background-color: transparent;
            border: none;
            color: #8b7d6b;
            font-size: 20px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .calendar-nav-btn:hover {
            background-color: #f5f3f0;
            color: #3b2a18;
        }

        .calendar-month-year {
            font-size: 18px;
            font-weight: 600;
            color: #8b7d6b;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            background-color: #e8e3db;
            border-radius: 8px;
            overflow: hidden;
        }

        .calendar-day-header {
            background-color: #f5f3f0;
            padding: 15px 10px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: #8b7d6b;
            text-transform: uppercase;
        }

        .calendar-day {
            background-color: white;
            min-height: 100px;
            padding: 12px 10px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }

        .calendar-day:hover {
            background-color: #faf8f5;
        }

        .calendar-day.other-month {
            background-color: #faf8f5;
            opacity: 0.5;
        }

        .calendar-day-number {
            font-size: 14px;
            font-weight: 500;
            color: #8b7d6b;
            margin-bottom: 8px;
        }

        .calendar-day.other-month .calendar-day-number {
            color: #c4b9a8;
        }

        /* Event Badge */
        .event-badge {
            background-color: #8b7d6b;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            margin-top: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.3s;
        }

        .event-badge:hover {
            background-color: #7a6a58;
            transform: scale(1.02);
        }

        /* Today Highlight */
        .calendar-day.today {
            background-color: #e8e3db;
        }

        .calendar-day.today .calendar-day-number {
            background-color: #8b7d6b;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== TESTIMONIALS STYLES ===== */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .testimonial-card {
            background-color: #e8dcc8;
            border-radius: 10px;
            padding: 30px 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 200px;
            position: relative;
            cursor: pointer;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .testimonial-quote {
            font-size: 15px;
            font-style: italic;
            color: #3b2a18;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .testimonial-author {
            font-size: 14px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        /* Action Buttons (Hidden by default, shown on hover) */
        .testimonial-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .testimonial-card:hover .testimonial-actions {
            opacity: 1;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-size: 13px;
        }

        .btn-delete-testimonial {
            background-color: rgba(217,83,79,0.9);
            color: white;
        }

        .btn-delete-testimonial:hover {
            background-color: #c9302c;
        }

        /* Add Testimonial Button */
        .add-testimonial-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }

        /* ===== GALLERY STYLES ===== */
        .upload-section {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .upload-area {
            flex: 1;
            min-width: 300px;
            background-color: #faf8f5;
            border: 2px dashed #d4cfc5;
            border-radius: 10px;
            padding: 50px 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-area:hover {
            border-color: #8b7d6b;
            background-color: #f5f3f0;
        }

        .upload-area.dragover {
            border-color: #8b7d6b;
            background-color: #e8e3db;
        }

        .upload-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .upload-icon i {
            font-size: 32px;
            color: #8b7d6b;
        }

        .upload-text {
            font-size: 15px;
            font-weight: 500;
            color: #3b2a18;
            margin: 0;
        }

        .upload-input {
            display: none;
        }

        .filter-upload-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            min-width: 250px;
        }

        .category-filter {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .gallery-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: all 0.3s;
            position: relative;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .gallery-info {
            padding: 15px;
        }

        .gallery-title {
            font-size: 14px;
            font-weight: 500;
            color: #3b2a18;
            margin: 0 0 10px 0;
        }

        .gallery-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Action Icons */
        .action-icons {
            display: flex;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gallery-card:hover .action-icons {
            opacity: 1;
        }

        .icon-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .icon-edit {
            background-color: #f5f3f0;
            color: #3b2a18;
        }

        .icon-edit:hover {
            background-color: #3b2a18;
            color: white;
        }

        .icon-delete {
            background-color: #f8d7da;
            color: #721c24;
        }

        .icon-delete:hover {
            background-color: #d9534f;
            color: white;
        }

        /* ===== STAFF MANAGEMENT STYLES ===== */
        .staff-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .staff-stats {
            display: flex;
            gap: 20px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: rgba(139, 125, 107, 0.15);
            padding: 12px 18px;
            border-radius: 50px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #8b7d6b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .stat-info h3 {
            font-size: 11px;
            font-weight: 500;
            color: #8b7d6b;
            margin: 0 0 2px 0;
        }

        .stat-info p {
            font-size: 20px;
            font-weight: 600;
            color: #3b2a18;
            margin: 0;
        }

        .controls-right {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .status-filter {
            padding: 10px 35px 10px 15px;
            border: 1px solid #c4b9a8;
            border-radius: 8px;
            background-color: white;
            font-size: 13px;
            color: #3b2a18;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233b2a18' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            min-width: 120px;
        }

        /* Two Card Layout */
        .two-card-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #8b7d6b;
            margin: 0;
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .card-action-btn {
            background: none;
            border: none;
            color: #8b7d6b;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .card-action-btn:hover {
            color: #3b2a18;
        }

        /* Staff List */
        .staff-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-height: 500px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .staff-list::-webkit-scrollbar {
            width: 6px;
        }

        .staff-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .staff-list::-webkit-scrollbar-thumb {
            background: #c4b9a8;
            border-radius: 10px;
        }

        .staff-list::-webkit-scrollbar-thumb:hover {
            background: #8b7d6b;
        }

        .staff-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background-color: #faf8f5;
            border-radius: 10px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .staff-item:hover {
            background-color: #f0ede7;
            transform: translateY(-2px);
        }

        .staff-item.selected {
            background-color: #e8e3db;
            border: 1px solid #8b7d6b;
        }

        .staff-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #8b7d6b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            flex-shrink: 0;
        }

        .staff-info {
            flex: 1;
        }

        .staff-name {
            font-size: 14px;
            font-weight: 600;
            color: #3b2a18;
            margin-bottom: 4px;
        }

        .staff-details {
            display: flex;
            gap: 15px;
        }

        .staff-detail {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #8b7d6b;
        }

        .staff-detail i {
            font-size: 12px;
        }

        .staff-status {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* Assignment Card */
        .assignment-card {
            display: flex;
            flex-direction: column;
        }

        .assignment-form {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #8b7d6b;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #c4b9a8;
            border-radius: 8px;
            background-color: white;
            font-size: 13px;
            color: #3b2a18;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #a89b88;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #8b7d6b;
            background-color: #faf8f5;
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233b2a18' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Selected Staff Info */
        .selected-staff-info {
            background-color: #faf8f5;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            display: none;
        }

        .selected-staff-info.active {
            display: block;
        }

        .selected-staff-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .selected-staff-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #8b7d6b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .selected-staff-name {
            font-size: 16px;
            font-weight: 600;
            color: #3b2a18;
        }

        .selected-staff-details {
            display: flex;
            gap: 15px;
            font-size: 13px;
            color: #8b7d6b;
        }

        /* ===== MOBILE MENU TOGGLE ===== */
        .mobile-menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #8b7d6b;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        /* ===== RESPONSIVE STYLES ===== */
        @media (max-width: 1200px) {
            .chart-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .sidebar.collapsed {
                width: 220px;
            }
            .sidebar.collapsed .sidebar-title,
            .sidebar.collapsed .quick-add-text,
            .sidebar.collapsed .nav-section-title,
            .sidebar.collapsed .nav-link span {
                display: block;
            }
            .sidebar.collapsed .quick-add-btn {
                justify-content: flex-start;
                padding: 10px 12px;
            }
            .sidebar.collapsed .nav-link {
                justify-content: flex-start;
                padding: 10px 12px;
            }
            .main-layout {
                margin-left: 0;
            }
            .main-layout.expanded {
                margin-left: 0;
            }
            .stats-row {
                grid-template-columns: 1fr;
            }
            .mobile-menu-toggle {
                display: flex;
            }
            .notification-menu {
                width: 300px;
                right: -50px;
            }
            .two-card-layout {
                grid-template-columns: 1fr;
            }
            .testimonials-grid {
                grid-template-columns: 1fr;
            }
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .top-header {
                padding: 15px 20px;
                flex-wrap: wrap;
            }
            .search-box {
                order: 3;
                max-width: 100%;
                width: 100%;
                flex-basis: 100%;
            }
            .dashboard-content {
                padding: 20px 15px;
            }
            .notification-menu {
                width: 280px;
                right: -80px;
            }
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-dropdown {
                width: 100%;
            }
            .search-box-bookings,
            .search-box-payments {
                max-width: 100%;
            }
            .view-calendar-btn,
            .add-event-btn {
                width: 100%;
                justify-content: center;
                margin-left: 0;
            }
            .upload-section {
                flex-direction: column;
            }
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            .staff-controls {
                flex-direction: column;
                align-items: stretch;
            }
            .staff-stats {
                flex-wrap: wrap;
            }
            .controls-right {
                flex-wrap: wrap;
            }
            .search-box-staff input {
                width: 100%;
            }
            .staff-details {
                flex-direction: column;
                gap: 5px;
            }
            .card {
                padding: 20px;
            }
            .calendar-container {
                padding: 20px 15px;
            }
            .calendar-day {
                min-height: 80px;
                padding: 8px 6px;
            }
            .event-badge {
                font-size: 9px;
                padding: 4px 6px;
            }
            .testimonial-card {
                padding: 25px 20px;
            }
        }

        @media (max-width: 576px) {
            .floating-notification-btn {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .notification-panel {
                width: 95%;
                max-height: 70vh;
            }
            
            .calendar-day-header {
                font-size: 10px;
                padding: 10px 5px;
            }
            .calendar-day-number {
                font-size: 12px;
            }
            .calendar-month-year {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .notification-menu {
                width: 250px;
                right: -100px;
            }
        }
    </style>