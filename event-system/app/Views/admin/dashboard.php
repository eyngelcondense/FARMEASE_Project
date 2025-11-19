<?php
    $current_page = isset($current_page) ? $current_page : 'dashboard'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - San Isidro Labrador Resort</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
  
   
</head>
   <?= $this->include('admin/style') ?>
<body>

    <?= $this->include('admin/sidebar') ?>

  <!-- Mobile Menu Toggle -->
  <button class="mobile-menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Main Content Area -->
  <div class="main-layout" id="mainLayout">
    <!-- Top Header -->
    <header class="top-header">
      <div class="welcome-section">
        <div class="admin-avatar">
          <img src="formal.jpg" alt="Admin">
        </div>
        <div class="welcome-text">
          <h2>Welcome back, Admin!</h2>
          <p>Management/Administrator</p>
        </div>
      </div>
      <div class="header-actions">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search ...">
        </div>
        
        <!-- Notification Button -->
        <div class="notification-dropdown">
          <button class="icon-btn" id="notificationBtn">
            <i class="fas fa-bell"></i>
            <span class="badge" id="notificationBadge">5</span>
          </button>
          <div class="notification-menu" id="notificationMenu">
            <div class="notification-header">
              <h4>Notifications</h4>
              <button class="mark-all-read" onclick="markAllAsRead()">Mark all as read</button>
            </div>
            <div class="notification-list" id="notificationList">
              <!-- Notifications will be populated by JavaScript -->
            </div>
            <div class="notification-footer">
              <a href="#" class="view-all-notifications">View All Notifications</a>
            </div>
          </div>
        </div>

        <button class="icon-btn">
          <i class="fas fa-cog"></i>
        </button>
      </div>
    </header>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
      <!-- Stats Cards -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <div class="stat-info">
            <h3>Total Events</h3>
            <p>280</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-ticket-alt"></i>
          </div>
          <div class="stat-info">
            <h3>Total Bookings</h3>
            <p>125</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="stat-info">
            <h3>Revenue</h3>
            <p>Php 22000</p>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="chart-row">
        <!-- Net Sales Chart -->
        <div class="chart-card">
          <div class="chart-header">
            <h3>
              Net Sales
              <i class="fas fa-chevron-down" style="font-size: 11px; color: #a89b88;"></i>
            </h3>
            <button class="filter-btn">
              <i class="fas fa-filter"></i>
              Filter: Weekly
            </button>
          </div>
          <div class="chart-stats">
            <div class="chart-stat-item">
              <h4>Total Revenue</h4>
              <p>156,500</p>
            </div>
            <div class="chart-stat-item">
              <h4>Total Tickets</h4>
              <p>2438</p>
            </div>
            <div class="chart-stat-item">
              <h4>Total Events</h4>
              <p>32</p>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="salesChart"></canvas>
          </div>
        </div>

        <!-- Venue Utilization Chart -->
        <div class="chart-card">
          <div class="chart-header">
            <h3>Venue Utilization</h3>
          </div>
          <div class="chart-container">
            <canvas id="venueChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Most Availed Packages -->
      <div class="chart-card packages-chart">
        <div class="chart-header">
          <h3>Most Availed Packages</h3>
        </div>
        <div class="bar-chart">
          <div class="bar-item">
            <div class="bar" style="height: 75%;"></div>
            <div class="bar-label">Enclosed Venue</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 90%;"></div>
            <div class="bar-label">Open Venue</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 55%;"></div>
            <div class="bar-label">Playground (Exclusive)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 30%;"></div>
            <div class="bar-label">Playground (Non-Exclusive)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 65%;"></div>
            <div class="bar-label">Cafe 2nd Floor (Basic)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 45%;"></div>
            <div class="bar-label">Cafe 2nd Floor (Premium)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 25%;"></div>
            <div class="bar-label">Café Meeting Room</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 35%;"></div>
            <div class="bar-label">Prep & Photoshoot (Basic)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 32%;"></div>
            <div class="bar-label">Prep & Photoshoot (Premium)</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  
  <script>
    // Sample notifications data
    const notifications = [
      {
        id: 1,
        type: 'payment',
        icon: 'fas fa-money-bill-wave',
        iconColor: '#52b788',
        message: 'Payment received from Angel Cortino (₱25,000)',
        time: '5 minutes ago',
        unread: true
      },
      {
        id: 2,
        type: 'booking',
        icon: 'fas fa-calendar-check',
        iconColor: '#4a5899',
        message: 'New booking request for Café 2nd Floor Venue',
        time: '1 hour ago',
        unread: true
      },
      {
        id: 3,
        type: 'reminder',
        icon: 'fas fa-user-clock',
        iconColor: '#ff6b35',
        message: '@Alan Walker Event in 3 days',
        time: '2 hours ago',
        unread: true
      },
      {
        id: 4,
        type: 'payment',
        icon: 'fas fa-receipt',
        iconColor: '#8b7d6b',
        message: 'Paycheck released for artists @Cynderex Event',
        time: '1 day ago',
        unread: false
      },
      {
        id: 5,
        type: 'payment',
        icon: 'fas fa-receipt',
        iconColor: '#8b7d6b',
        message: 'Paycheck released for artists @Get Together Event',
        time: '2 days ago',
        unread: false
      }
    ];

    // Toggle Sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainLayout = document.getElementById('mainLayout');
      
      sidebar.classList.toggle('collapsed');
      mainLayout.classList.toggle('expanded');
      
      // Save state to localStorage
      const isCollapsed = sidebar.classList.contains('collapsed');
      localStorage.setItem('sidebarCollapsed', isCollapsed);
    }

    // Initialize sidebar state
    function initSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainLayout = document.getElementById('mainLayout');
      const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
      
      if (isCollapsed) {
        sidebar.classList.add('collapsed');
        mainLayout.classList.add('expanded');
      }
    }

    // Toggle Mobile Sidebar
    function toggleMobileSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      const sidebar = document.getElementById('sidebar');
      const toggle = document.querySelector('.mobile-menu-toggle');
      
      if (window.innerWidth <= 992) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
          sidebar.classList.remove('active');
        }
      }
    });

    // Notification System
    function initNotifications() {
      const notificationBtn = document.getElementById('notificationBtn');
      const notificationMenu = document.getElementById('notificationMenu');
      const notificationList = document.getElementById('notificationList');
      const notificationBadge = document.getElementById('notificationBadge');

      // Populate notifications
      function renderNotifications() {
        const unreadCount = notifications.filter(n => n.unread).length;
        notificationBadge.textContent = unreadCount;
        
        notificationList.innerHTML = notifications.map(notification => `
          <div class="notification-item ${notification.unread ? 'unread' : ''}" 
               onclick="markAsRead(${notification.id})">
            <div class="notification-icon" style="color: ${notification.iconColor}">
              <i class="${notification.icon}"></i>
            </div>
            <div class="notification-content">
              <p>${notification.message}</p>
              <div class="notification-time">${notification.time}</div>
            </div>
          </div>
        `).join('');
      }

      // Toggle notification menu
      notificationBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationMenu.classList.toggle('show');
      });

      // Close notification menu when clicking outside
      document.addEventListener('click', (e) => {
        if (!notificationBtn.contains(e.target) && !notificationMenu.contains(e.target)) {
          notificationMenu.classList.remove('show');
        }
      });

      // Mark single notification as read
      window.markAsRead = function(id) {
        const notification = notifications.find(n => n.id === id);
        if (notification && notification.unread) {
          notification.unread = false;
          renderNotifications();
        }
      };

      // Mark all notifications as read
      window.markAllAsRead = function() {
        notifications.forEach(notification => {
          notification.unread = false;
        });
        renderNotifications();
      };

      // Simulate new notification (for demo)
      window.addNewNotification = function() {
        const newNotification = {
          id: notifications.length + 1,
          type: 'info',
          icon: 'fas fa-info-circle',
          iconColor: '#8b7d6b',
          message: 'New event booking just came in!',
          time: 'Just now',
          unread: true
        };
        notifications.unshift(newNotification);
        renderNotifications();
        
        // Show notification toast
        showNotificationToast(newNotification.message);
      };

      // Initial render
      renderNotifications();
    }

    // Notification toast
    function showNotificationToast(message) {
      const toast = document.createElement('div');
      toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        border-left: 4px solid #8b7d6b;
        z-index: 1002;
        max-width: 300px;
        font-size: 13px;
        color: #3b2a18;
        transform: translateX(100%);
        transition: transform 0.3s ease;
      `;
      toast.textContent = message;
      document.body.appendChild(toast);

      setTimeout(() => toast.style.transform = 'translateX(0)', 100);
      setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
      }, 4000);
    }

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
      initSidebar();
      initNotifications();
      
      // Demo: Add a new notification every 30 seconds
      setInterval(() => {
        if (Math.random() > 0.7) { // 30% chance
          addNewNotification();
        }
      }, 30000);

      // Initialize charts (your existing chart code)
      initCharts();
    });

    // Your existing chart initialization code
    function initCharts() {
      // Sales Line Chart
      const salesCtx = document.getElementById('salesChart');
      if (salesCtx) {
        new Chart(salesCtx, {
          type: 'line',
          data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
            datasets: [{
              label: 'Net Sales',
              data: [35000, 22000, 46000, 15000, 28000, 34000],
              borderColor: '#8b7d6b',
              backgroundColor: 'rgba(139, 125, 107, 0.05)',
              tension: 0.4,
              fill: true,
              pointBackgroundColor: '#8b7d6b',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointRadius: 5,
              pointHoverRadius: 7
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                backgroundColor: '#3b2a18',
                padding: 12,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#8b7d6b',
                borderWidth: 1,
                titleFont: {
                  size: 12,
                  family: 'Poppins'
                },
                bodyFont: {
                  size: 11,
                  family: 'Poppins'
                }
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  color: '#a89b88',
                  font: {
                    family: 'Poppins',
                    size: 10
                  },
                  callback: function(value) {
                    return value.toLocaleString();
                  }
                },
                grid: {
                  color: 'rgba(139, 125, 107, 0.08)'
                }
              },
              x: {
                ticks: {
                  color: '#a89b88',
                  font: {
                    family: 'Poppins',
                    size: 10
                  }
                },
                grid: {
                  display: false
                }
              }
            }
          }
        });
      }

      // Venue Utilization Doughnut Chart
      const venueCtx = document.getElementById('venueChart');
      if (venueCtx) {
        new Chart(venueCtx, {
          type: 'doughnut',
          data: {
            labels: ['Enclosed', 'Open', 'Playground', 'Cafe 2nd Floor'],
            datasets: [{
              data: [250, 170, 290, 370],
              backgroundColor: [
                '#8b7d6b',
                '#a89b88',
                '#7a6a58',
                '#6d5d4d'
              ],
              borderWidth: 0,
              hoverOffset: 8
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  color: '#3b2a18',
                  font: {
                    family: 'Poppins',
                    size: 10
                  },
                  padding: 12,
                  usePointStyle: true,
                  pointStyle: 'circle',
                  boxWidth: 8,
                  boxHeight: 8
                }
              },
              tooltip: {
                backgroundColor: '#3b2a18',
                padding: 10,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#8b7d6b',
                borderWidth: 1,
                titleFont: {
                  size: 11,
                  family: 'Poppins'
                },
                bodyFont: {
                  size: 10,
                  family: 'Poppins'
                },
                callbacks: {
                  label: function(context) {
                    let label = context.label || '';
                    if (label) {
                      label += ': ';
                    }
                    label += context.parsed;
                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                    label += ` (${percentage}%)`;
                    return label;
                  }
                }
              }
            },
            cutout: '65%'
          }
        });
      }

      // Animation effects
      const statCards = document.querySelectorAll('.stat-card');
      statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
      });

      // Bar chart hover effect
      document.querySelectorAll('.bar').forEach(bar => {
        bar.addEventListener('mouseenter', function() {
          this.style.transform = 'scaleY(1.05)';
          this.style.transformOrigin = 'bottom';
        });
        bar.addEventListener('mouseleave', function() {
          this.style.transform = 'scaleY(1)';
        });
      });
    }
  </script>

</body>
</html>