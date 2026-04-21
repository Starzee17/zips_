
/**
 * ZIPS Chau E-Library - JavaScript
 */

// Initialize theme before DOM loads to prevent flash
(function() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    // ===== DARK MODE TOGGLE =====
    createThemeToggle();

    function createThemeToggle() {
        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'theme-toggle';
        toggleBtn.setAttribute('aria-label', 'Toggle dark mode');
        toggleBtn.innerHTML = '<i class="fas fa-moon"></i><i class="fas fa-sun"></i>';
        document.body.appendChild(toggleBtn);

        // Toggle theme on click
        toggleBtn.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Add animation class
            this.style.transform = 'scale(0.8) rotate(180deg)';
            setTimeout(() => {
                this.style.transform = '';
            }, 300);
        });
    }

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
        }
    });
    // Mobile Sidebar Toggle
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('adminSidebar');

    if (mobileSidebarToggle && adminSidebar) {
        mobileSidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
        });
    }

    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (adminSidebar && adminSidebar.classList.contains('active')) {
            if (!adminSidebar.contains(e.target) &&
                !mobileSidebarToggle?.contains(e.target)) {
                adminSidebar.classList.remove('active');
            }
        }
    });

    // Mobile Navigation Toggle (for public pages)
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }

    // Header scroll effect
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    // Form validation feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });
    });

    // Alert auto-dismiss
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Animate elements on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.stat-card, .feature-card, .resource-card');
        elements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight - 100;
            if (isVisible) {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }
        });
    };

    // Initialize animation styles
    document.querySelectorAll('.stat-card, .feature-card, .resource-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check

    // File upload preview (for admin)
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'No file selected';
            const label = this.parentElement.querySelector('.file-label');
            if (label) {
                label.textContent = fileName;
            }
        });
    });

    // Confirm delete actions
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const message = this.dataset.confirm || 'Are you sure?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Table row click handler
    document.querySelectorAll('.admin-table tbody tr[data-href]').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    console.log('ZIPS Chau E-Library loaded successfully');
});
