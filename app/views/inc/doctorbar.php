<?php
$doctor = $_SESSION['current_doctor'] ?? null;
if (!$doctor || !is_array($doctor)) {
    redirect('pages/login');
    exit;
}

// Function to detect active page
function isActivePage($pagePath) {
    $currentURL = $_SERVER['REQUEST_URI'];
    $urlRoot = defined('URLROOT') ? URLROOT : '';
    
    // Remove URLROOT from comparison
    $cleanURL = str_replace($urlRoot, '', $currentURL);
    $cleanURL = strtok($cleanURL, '?'); // Remove query parameters
    $cleanURL = rtrim($cleanURL, '/');
    
    return strpos($cleanURL, $pagePath) !== false ||
           ($pagePath === '/pages/home' && ($cleanURL === '' || $cleanURL === '/'));
}

// Function to format time beautifully
function formatTime($time) {
    if (empty($time)) return '';
    
    // Try to parse the time
    $timestamp = strtotime($time);
    if ($timestamp === false) {
        // If strtotime fails, try to handle common formats
        return htmlspecialchars($time);
    }
    
    return date('g:i A', $timestamp);
}

// Function to get time period greeting (fallback for server-side)
function getTimeGreeting() {
    $hour = date('H');
    if ($hour < 12) return 'Good Morning';
    if ($hour < 17) return 'Good Afternoon';
    return 'Good Evening';
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bar.css?v=2">
    
    <style>
        .schedule-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .schedule-display:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .schedule-icon {
            font-size: 1.1rem;
            animation: pulse 2s infinite;
        }
        
        .time-range {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .schedule-label {
            opacity: 0.9;
            font-size: 0.85rem;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        .doctor-greeting {
            color: #667eea;
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .doctor-name {
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .schedule-display {
                font-size: 0.8rem;
                padding: 6px 12px;
            }
            
            .schedule-label {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header class="navbar">
        <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/doctor/dash" class="logo">
            Doctor's Dashboard
        </a>
        <button class="navbar-toggle" onclick="toggleNavbar()">
            <i class="fas fa-bars"></i>
        </button>
        <nav class="navbar-nav" id="navbarNav">
            <li>
                <div class="schedule-display">
                    <i class="fas fa-clock schedule-icon"></i>
                    <span class="schedule-label">Today's Schedule:</span>
                    <span class="time-range">
                        <?php 
                        $startTime = formatTime($doctor['start_time']);
                        $endTime = formatTime($doctor['end_time']);
                        echo $startTime . ' - ' . $endTime;
                        ?>
                    </span>
                </div>
            </li>
            
            <li>
                <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/doctor/profile"
                   class="<?php echo isActivePage('/pages/userprofile') ? 'active' : ''; ?>">
                    <i class="fas fa-user-md"></i>
                    <span class="doctor-greeting" id="doctorGreeting"></span>
                    <span class="doctor-name">Dr. <?php echo htmlspecialchars($doctor['name']); ?></span>
                </a>
            </li>
        </nav>
    </header>

    <script>
        function toggleNavbar() {
            const navbar = document.getElementById('navbarNav');
            navbar.classList.toggle('active');
        }
        
        // Update greeting based on current time
        function updateGreeting() {
            const hour = new Date().getHours();
            let greeting = 'Good Evening';
            
            if (hour < 12) greeting = 'Good Morning';
            else if (hour < 17) greeting = 'Good Afternoon';
            
            const greetingElement = document.getElementById('doctorGreeting');
            if (greetingElement) {
                greetingElement.textContent = greeting + ', ';
            }
        }
        
        // Set initial greeting when page loads
        document.addEventListener('DOMContentLoaded', updateGreeting);
        
        // Update greeting every minute
        setInterval(updateGreeting, 60000);
    </script>
</body>
</html>