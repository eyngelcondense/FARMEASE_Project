<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --bg-color: #F8F9F9;
    --surface-color: #FFFFFF;
    --text-main: #1A1D1F;
    --text-muted: #6F767E;
    --primary-color: #B59B75; /* Elegant Soft Gold/Brown */
    --primary-hover: #9A8363;
    --primary-light: #F8F5F1;
    --sidebar-bg: #FFFFFF;
    --sidebar-text: #6F767E;
    --border-color: #EFEFEF;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.03);
    --shadow-md: 0 12px 32px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 24px 48px rgba(0, 0, 0, 0.08);
    --radius-sm: 12px;
    --radius-md: 20px;
    --radius-lg: 24px;
    --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    
    --success-color: #27AE60;
    --success-bg: #EAF9F0;
    --warning-color: #F2994A;
    --warning-bg: #FEF5ED;
    --info-color: #2D9CDB;
    --info-bg: #EAF5FC;
    --pending-color: #F2C94C;
    --pending-bg: #FEF9EB;
}

/* ===== RESET & BASE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background-color: var(--bg-color);
    color: var(--text-main);
    overflow-x: hidden;
    transition: var(--transition);
    -webkit-font-smoothing: antialiased;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
}

/* ===== SIDEBAR STYLES ===== */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 280px;
    height: 100vh;
    background-color: var(--sidebar-bg);
    color: var(--sidebar-text);
    overflow-y: auto;
    z-index: 1000;
    padding: 32px 24px;
    transition: var(--transition);
    border-right: 1px solid var(--border-color);
}

.sidebar.collapsed {
    width: 96px;
    padding: 32px 16px;
}

.sidebar.collapsed .sidebar-title,
.sidebar.collapsed .nav-section-title,
.sidebar.collapsed .nav-link span {
    display: none;
    opacity: 0;
}

.sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 16px;
}

.sidebar.collapsed .nav-link i {
    margin-right: 0;
}

.sidebar-header {
    padding-bottom: 32px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 24px;
}

.sidebar-logo {
    display: flex;
    align-items: center;
    gap: 16px;
}

.sidebar-logo-icon {
    width: 48px;
    height: 48px;
    background-color: var(--primary-light);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--primary-color);
    font-size: 24px;
    box-shadow: inset 0 0 0 1px rgba(181, 155, 117, 0.15);
}

.sidebar-title {
    font-size: 16px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    line-height: 1.3;
    color: var(--text-main);
    transition: opacity 0.3s;
}

.nav-section {
    margin-bottom: 32px;
}

.nav-section-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #B4B4B4;
    margin-bottom: 12px;
    padding: 0 16px;
    transition: opacity 0.3s;
}

.nav-menu {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 16px;
    color: var(--sidebar-text);
    text-decoration: none;
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-weight: 600;
    transition: var(--transition);
}

.nav-link:hover {
    background-color: var(--bg-color);
    color: var(--text-main);
    transform: translateX(4px);
}

.nav-link.active {
    background-color: var(--primary-color);
    color: #FFFFFF;
    box-shadow: 0 8px 24px rgba(181, 155, 117, 0.25);
}

.nav-link.active i {
    color: #FFFFFF;
}

.nav-link i {
    font-size: 20px;
    width: 24px;
    display: flex;
    justify-content: center;
    transition: transform 0.3s;
}

/* ===== MAIN LAYOUT STYLES ===== */
.main-layout {
    margin-left: 280px;
    min-height: 100vh;
    transition: var(--transition);
    background-color: var(--bg-color);
}

.main-layout.expanded {
    margin-left: 96px;
}

/* ===== HEADER STYLES ===== */
.top-header {
    background-color: rgba(248, 249, 249, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 24px 48px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 999;
    border-bottom: 1px solid rgba(239, 239, 239, 0.6);
}

.welcome-section {
    display: flex;
    align-items: center;
    gap: 20px;
}

.admin-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background-color: var(--primary-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 22px;
    box-shadow: var(--shadow-sm);
    border: 2px solid #FFFFFF;
}

.welcome-text h2 {
    font-size: 22px;
    color: var(--text-main);
    margin: 0;
    letter-spacing: -0.01em;
}

.welcome-text p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.icon-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: var(--surface-color);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    color: var(--text-main);
    font-size: 16px;
}

.icon-btn:hover {
    background-color: var(--primary-light);
    color: var(--primary-color);
    border-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* ===== PAGE TITLES ===== */
.page-header {
    margin-bottom: 32px;
}

.page-title {
    font-size: 32px;
    color: var(--text-main);
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 15px;
    color: var(--text-muted);
    font-weight: 500;
}

.gold-line {
    width: 60px;
    height: 3px;
    background: var(--primary-color);
    border-radius: 3px;
    margin: 16px 0;
}

/* ===== DASHBOARD CONTENT ===== */
.dashboard-content {
    padding: 40px 48px;
    max-width: 1440px;
    margin: 0 auto;
}

/* ===== STATS ROW ===== */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--surface-color);
    border-radius: var(--radius-md);
    padding: 28px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 24px;
    position: relative;
    overflow: hidden;
}

.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
    opacity: 0;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.stat-card:hover::after {
    opacity: 1;
}

.stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    background: var(--primary-light);
    color: var(--primary-color);
}

.stat-info h3 {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin: 0 0 8px 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-info p {
    font-family: 'Outfit', sans-serif;
    font-size: 34px;
    font-weight: 700;
    color: var(--text-main);
    margin: 0;
    line-height: 1;
}

/* ===== PANELS LAYOUT ===== */
.bottom-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.mini-card {
    background: var(--surface-color);
    border-radius: var(--radius-md);
    padding: 32px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
}

.mini-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.mini-card-header h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-main);
    margin: 0;
}

.view-all {
    font-size: 13px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    background: var(--primary-light);
    padding: 8px 16px;
    border-radius: 24px;
    transition: var(--transition);
}

.view-all:hover {
    background: var(--primary-color);
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(181, 155, 117, 0.2);
}

/* Mini Card Content */
.mini-card-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.assignment-row, .booking-item {
    display: flex;
    flex-direction: column;
    padding: 20px;
    background-color: #FAFAFA;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.assignment-row:hover, .booking-item:hover {
    background-color: var(--surface-color);
    border-color: var(--primary-color);
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.assignment-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.assignment-info strong {
    font-size: 16px;
    color: var(--text-main);
    font-weight: 600;
}

.assignment-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 500;
}

/* Status Badges */
.assignment-status, .status-badge {
    padding: 6px 14px;
    border-radius: 24px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.status-pending { background-color: var(--pending-bg); color: var(--pending-color); }
.status-confirmed { background-color: var(--info-bg); color: var(--info-color); }
.status-approved { background-color: var(--success-bg); color: var(--success-color); }
.status-completed { background-color: var(--primary-light); color: var(--primary-color); }

/* Buttons */
.btn-outline-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    background: var(--surface-color);
    color: var(--text-main);
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    transition: var(--transition);
}

.btn-outline-primary:hover {
    border-color: var(--primary-color);
    background: var(--primary-light);
    color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* Loading Overlay & Toasts */
.loading-overlay {
    position: fixed; inset: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    display: none; align-items: center; justify-content: center;
    z-index: 2000;
}

.spinner-border {
    width: 56px; height: 56px;
    border: 4px solid var(--primary-light);
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

.toast-notification {
    position: fixed; bottom: 40px; right: 40px;
    background: var(--surface-color);
    border-radius: var(--radius-sm);
    padding: 20px 28px;
    box-shadow: var(--shadow-lg);
    display: flex; align-items: center; gap: 16px;
    font-size: 15px; font-weight: 600;
    z-index: 3000;
    opacity: 0; transform: translateY(20px);
    transition: var(--transition);
    border: 1px solid var(--border-color);
}

.toast-notification.show {
    opacity: 1; transform: translateY(0);
}

.toast-success { border-left: 4px solid var(--success-color); }
.toast-error { border-left: 4px solid #EB5757; }
.toast-info { border-left: 4px solid var(--info-color); }

.toast-close {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 18px;
    margin-left: auto;
    transition: var(--transition);
}

.toast-close:hover {
    color: var(--text-main);
    transform: rotate(90deg);
}

/* Responsive */
@media (max-width: 1024px) {
    .bottom-row { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .sidebar { left: -280px; }
    .sidebar.active { left: 0; box-shadow: var(--shadow-lg); }
    .main-layout { margin-left: 0; }
    .top-header { padding: 20px 24px; }
    .dashboard-content { padding: 24px 20px; }
    .stats-row { grid-template-columns: 1fr; }
}

/* Form inputs & Search */
.search-input {
    width: 100%;
    padding: 14px 20px 14px 48px;
    border: 1px solid var(--border-color);
    border-radius: 100px;
    font-size: 15px;
    background: var(--surface-color);
    color: var(--text-main);
    outline: none;
    transition: var(--transition);
    font-family: inherit;
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px var(--primary-light);
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 18px;
}
</style>
