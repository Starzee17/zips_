
<?php
/**
 * ZIPS Chau E-Library Helper Functions
 */

require_once __DIR__ . '/config.php';

/**
 * Check if user is authenticated (has entered correct PIN)
 */
function isAuthenticated() {
    return isset($_SESSION['library_access']) && $_SESSION['library_access'] === true;
}

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require authentication - redirect if not authenticated
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: auth.php');
        exit;
    }
}

/**
 * Require admin authentication
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Generate a unique share token
 */
function generateShareToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Format file size for display
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Get file icon based on extension
 */
function getFileIcon($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icons = [
        'pdf' => 'fa-file-pdf',
        'doc' => 'fa-file-word',
        'docx' => 'fa-file-word',
        'ppt' => 'fa-file-powerpoint',
        'pptx' => 'fa-file-powerpoint',
        'xls' => 'fa-file-excel',
        'xlsx' => 'fa-file-excel',
        'jpg' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'png' => 'fa-file-image',
        'gif' => 'fa-file-image',
        'webp' => 'fa-file-image',
        'mp4' => 'fa-file-video',
        'avi' => 'fa-file-video',
        'mov' => 'fa-file-video',
        'webm' => 'fa-file-video',
        'txt' => 'fa-file-alt',
    ];
    return $icons[$ext] ?? 'fa-file';
}

/**
 * Get resource type color
 */
function getTypeColor($type) {
    $colors = [
        'module' => '#3498db',
        'picture' => '#27ae60',
        'video' => '#e74c3c',
        'article' => '#9b59b6'
    ];
    return $colors[$type] ?? '#95a5a6';
}

/**
 * Get resource type badge class
 */
function getTypeBadgeClass($type) {
    $classes = [
        'module' => 'badge-module',
        'picture' => 'badge-picture',
        'video' => 'badge-video',
        'article' => 'badge-article'
    ];
    return $classes[$type] ?? 'badge-default';
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Log access
 */
function logAccess($page, $success = true) {
    $pdo = getDBConnection();
    if (!$pdo) return;

    try {
        $stmt = $pdo->prepare("INSERT INTO access_logs (ip_address, user_agent, page_accessed, success) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            $page,
            $success ? 1 : 0
        ]);
    } catch (PDOException $e) {
        // Silently fail for logging
    }
}

/**
 * Get all resources
 */
function getResources($type = null, $limit = null, $search = null) {
    $pdo = getDBConnection();
    if (!$pdo) return getDemoResources($type);

    try {
        $sql = "SELECT * FROM resources WHERE is_active = 1";
        $params = [];

        if ($type) {
            $sql .= " AND file_type = ?";
            $params[] = $type;
        }

        if ($search) {
            $sql .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY upload_date DESC";

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return getDemoResources($type);
    }
}

/**
 * Get demo resources for display without database
 */
function getDemoResources($type = null) {
    $resources = [
        [
            'id' => 1,
            'title' => 'Introduction to Procurement Management',
            'description' => 'A comprehensive guide to procurement principles and practices.',
            'file_name' => 'procurement-intro.pdf',
            'file_type' => 'module',
            'file_size' => 2456789,
            'share_token' => 'demo1234567890',
            'upload_date' => date('Y-m-d H:i:s', strtotime('-5 days')),
            'views' => 125
        ],
        [
            'id' => 2,
            'title' => 'Supply Chain Fundamentals',
            'description' => 'Understanding the basics of supply chain management.',
            'file_name' => 'supply-chain.pdf',
            'file_type' => 'module',
            'file_size' => 1234567,
            'share_token' => 'demo2345678901',
            'upload_date' => date('Y-m-d H:i:s', strtotime('-3 days')),
            'views' => 89
        ],
        [
            'id' => 3,
            'title' => 'ZIPS Chapter Meeting 2024',
            'description' => 'Photos from our annual chapter meeting.',
            'file_name' => 'meeting-2024.jpg',
            'file_type' => 'picture',
            'file_size' => 567890,
            'share_token' => 'demo3456789012',
            'upload_date' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'views' => 234
        ],
        [
            'id' => 4,
            'title' => 'Ethics in Procurement',
            'description' => 'Video lecture on ethical considerations in procurement.',
            'file_name' => 'ethics-procurement.mp4',
            'file_type' => 'video',
            'file_size' => 45678901,
            'share_token' => 'demo4567890123',
            'upload_date' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'views' => 67
        ],
        [
            'id' => 5,
            'title' => 'Research on Public Procurement in Zambia',
            'description' => 'Academic article on public procurement practices.',
            'file_name' => 'research-procurement.pdf',
            'file_type' => 'article',
            'file_size' => 789012,
            'share_token' => 'demo5678901234',
            'upload_date' => date('Y-m-d H:i:s'),
            'views' => 45
        ],
        [
            'id' => 6,
            'title' => 'Tender Management Best Practices',
            'description' => 'Learn how to manage tenders effectively.',
            'file_name' => 'tender-management.pptx',
            'file_type' => 'module',
            'file_size' => 3456789,
            'share_token' => 'demo6789012345',
            'upload_date' => date('Y-m-d H:i:s', strtotime('-4 days')),
            'views' => 156
        ]
    ];

    if ($type) {
        return array_filter($resources, fn($r) => $r['file_type'] === $type);
    }

    return $resources;
}

/**
 * Get resource by ID
 */
function getResourceById($id) {
    $pdo = getDBConnection();
    if (!$pdo) {
        $resources = getDemoResources();
        foreach ($resources as $r) {
            if ($r['id'] == $id) return $r;
        }
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM resources WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Get resource by share token
 */
function getResourceByToken($token) {
    $pdo = getDBConnection();
    if (!$pdo) {
        $resources = getDemoResources();
        foreach ($resources as $r) {
            if ($r['share_token'] === $token) return $r;
        }
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM resources WHERE share_token = ? AND is_active = 1");
        $stmt->execute([$token]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Increment view count
 */
function incrementViews($id) {
    $pdo = getDBConnection();
    if (!$pdo) return;

    try {
        $stmt = $pdo->prepare("UPDATE resources SET views = views + 1 WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Silently fail
    }
}

/**
 * Get graduates
 */
function getGraduates($year = null, $published_only = true) {
    $pdo = getDBConnection();
    if (!$pdo) return getDemoGraduates($year);

    try {
        $sql = "SELECT * FROM graduates WHERE 1=1";
        $params = [];

        if ($published_only) {
            $sql .= " AND is_published = 1";
        }

        if ($year) {
            $sql .= " AND graduation_year = ?";
            $params[] = $year;
        }

        $sql .= " ORDER BY graduation_year DESC, full_name ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return getDemoGraduates($year);
    }
}

/**
 * Get demo graduates
 */
function getDemoGraduates($year = null) {
    $graduates = [
        [
            'id' => 1,
            'full_name' => 'Chanda Mwamba',
            'student_id' => 'ZIPS2024001',
            'program' => 'Diploma in Procurement and Supply',
            'graduation_year' => 2024,
            'graduation_date' => '2024-07-15',
            'honors' => 'Distinction',
            'photo' => null,
            'is_published' => 1
        ],
        [
            'id' => 2,
            'full_name' => 'Bwalya Musonda',
            'student_id' => 'ZIPS2024002',
            'program' => 'Certificate in Purchasing Management',
            'graduation_year' => 2024,
            'graduation_date' => '2024-07-15',
            'honors' => 'Merit',
            'photo' => null,
            'is_published' => 1
        ],
        [
            'id' => 3,
            'full_name' => 'Mutale Kapembwa',
            'student_id' => 'ZIPS2023015',
            'program' => 'Diploma in Procurement and Supply',
            'graduation_year' => 2023,
            'graduation_date' => '2023-07-20',
            'honors' => 'Distinction',
            'photo' => null,
            'is_published' => 1
        ]
    ];

    if ($year) {
        return array_filter($graduates, fn($g) => $g['graduation_year'] == $year);
    }

    return $graduates;
}

/**
 * Get educational apps
 */
function getEduApps($category = null) {
    $pdo = getDBConnection();
    if (!$pdo) return getDemoEduApps($category);

    try {
        $sql = "SELECT * FROM edu_apps WHERE is_active = 1";
        $params = [];

        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }

        $sql .= " ORDER BY category, app_name";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return getDemoEduApps($category);
    }
}

/**
 * Get demo educational apps
 */
function getDemoEduApps($category = null) {
    $apps = [
        ['id' => 1, 'app_name' => 'Khan Academy', 'description' => 'Free world-class education for anyone, anywhere. Covers math, science, computing, and more.', 'app_url' => 'https://www.khanacademy.org', 'category' => 'Learning Platform'],
        ['id' => 2, 'app_name' => 'Coursera', 'description' => 'Access courses from top universities and companies worldwide.', 'app_url' => 'https://www.coursera.org', 'category' => 'Learning Platform'],
        ['id' => 3, 'app_name' => 'edX', 'description' => 'Free online courses from Harvard, MIT, and more.', 'app_url' => 'https://www.edx.org', 'category' => 'Learning Platform'],
        ['id' => 4, 'app_name' => 'Duolingo', 'description' => 'Learn languages for free with gamified lessons.', 'app_url' => 'https://www.duolingo.com', 'category' => 'Language Learning'],
        ['id' => 5, 'app_name' => 'Quizlet', 'description' => 'Study tools and flashcards to help you learn anything.', 'app_url' => 'https://quizlet.com', 'category' => 'Study Tools'],
        ['id' => 6, 'app_name' => 'Google Scholar', 'description' => 'Search scholarly literature across many disciplines.', 'app_url' => 'https://scholar.google.com', 'category' => 'Research'],
        ['id' => 7, 'app_name' => 'ResearchGate', 'description' => 'Professional network for scientists and researchers.', 'app_url' => 'https://www.researchgate.net', 'category' => 'Research'],
        ['id' => 8, 'app_name' => 'JSTOR', 'description' => 'Digital library of academic journals, books, and primary sources.', 'app_url' => 'https://www.jstor.org', 'category' => 'Research'],
        ['id' => 9, 'app_name' => 'Grammarly', 'description' => 'Writing assistant that helps improve your grammar and writing.', 'app_url' => 'https://www.grammarly.com', 'category' => 'Writing Tools'],
        ['id' => 10, 'app_name' => 'Notion', 'description' => 'All-in-one workspace for notes, tasks, and collaboration.', 'app_url' => 'https://www.notion.so', 'category' => 'Productivity']
    ];

    if ($category) {
        return array_filter($apps, fn($a) => $a['category'] === $category);
    }

    return $apps;
}

/**
 * Get statistics
 */
function getStats() {
    $pdo = getDBConnection();

    if (!$pdo) {
        return [
            'total_resources' => 6,
            'total_modules' => 3,
            'total_pictures' => 1,
            'total_videos' => 1,
            'total_articles' => 1,
            'total_graduates' => 3,
            'total_views' => 716,
            'recent_accesses' => 45
        ];
    }

    try {
        $stats = [];

        // Total resources
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM resources WHERE is_active = 1");
        $stats['total_resources'] = $stmt->fetch()['count'];

        // By type
        $stmt = $pdo->query("SELECT file_type, COUNT(*) as count FROM resources WHERE is_active = 1 GROUP BY file_type");
        $types = $stmt->fetchAll();
        $stats['total_modules'] = 0;
        $stats['total_pictures'] = 0;
        $stats['total_videos'] = 0;
        $stats['total_articles'] = 0;
        foreach ($types as $t) {
            $stats['total_' . $t['file_type'] . 's'] = $t['count'];
        }

        // Total graduates
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM graduates WHERE is_published = 1");
        $stats['total_graduates'] = $stmt->fetch()['count'];

        // Total views
        $stmt = $pdo->query("SELECT SUM(views) as total FROM resources");
        $stats['total_views'] = $stmt->fetch()['total'] ?? 0;

        // Recent accesses (last 7 days)
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM access_logs WHERE access_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stats['recent_accesses'] = $stmt->fetch()['count'];

        return $stats;
    } catch (PDOException $e) {
        return [
            'total_resources' => 0,
            'total_modules' => 0,
            'total_pictures' => 0,
            'total_videos' => 0,
            'total_articles' => 0,
            'total_graduates' => 0,
            'total_views' => 0,
            'recent_accesses' => 0
        ];
    }
}

/**
 * Get app categories
 */
function getAppCategories() {
    return [
        'Learning Platform',
        'Language Learning',
        'Study Tools',
        'Research',
        'Writing Tools',
        'Productivity'
    ];
}

/**
 * Time ago function
 */
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $time);
    }
}
