<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - TicketKing</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .tech-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        .tech-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .frontend-card {
            border-left-color: #3B82F6;
        }
        .backend-card {
            border-left-color: #10B981;
        }
        .feature-card {
            border-left-color: #8B5CF6;
        }
        .gradient-text {
            background: linear-gradient(90deg, #3B82F6, #8B5CF6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header/Navbar would go here -->
    <nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">About <span class="gradient-text">TicketKing</span></h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">A modern event ticketing platform built with cutting-edge technologies</p>
        </div>

        <!-- Developer Profile -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-16 max-w-4xl mx-auto">
            <div class="md:flex">
                <div class="md:w-1/3 bg-gradient-to-br from-blue-500 to-purple-600 p-8 flex items-center justify-center">
                    <div class="text-center text-white">
                        <img src="./images/developer.jpg" alt="Md Tasmim Al Tahsin" class=" h-40 rounded-full mx-auto mb-4 border-4 border-white">
                        <h2 class="text-2xl font-bold">Md Tasmim Al Tahsin</h2>
                        <p class="text-blue-100">BSc in CSE</p>
                        <p class="text-blue-100">American International University-Bangladesh (AIUB)</p>
                        <div class="flex justify-center space-x-4 mt-4">
                            <a href="https://github.com/YOUR_GITHUB" class="text-white hover:text-blue-200">
                                <i class="fab fa-github fa-lg"></i>
                            </a>
                            <a href="https://linkedin.com/in/YOUR_LINKEDIN" class="text-white hover:text-blue-200">
                                <i class="fab fa-linkedin fa-lg"></i>
                            </a>
                            <a href="https://facebook.com/YOUR_FACEBOOK" class="text-white hover:text-blue-200">
                                <i class="fab fa-facebook fa-lg"></i>
                            </a>
                            <a href="https://instagram.com/YOUR_INSTAGRAM" class="text-white hover:text-blue-200">
                                <i class="fab fa-instagram fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="md:w-2/3 p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">About the Developer</h3>
                    <p class="text-gray-600 mb-4">
                        As a Computer Science and Engineering student from AIUB, I specialize in full-stack web development with a passion for creating efficient, user-friendly applications.
                    </p>
                    <p class="text-gray-600 mb-4">
                        TicketKing was developed as a comprehensive solution for event organizers and attendees, combining modern web technologies with practical features for seamless event management.
                    </p>
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-800 mb-2">Connect with me:</h4>
                        <div class="flex flex-wrap gap-2">
                            <a href="mailto:tahsinniyan@gmail.com" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                <i class="fas fa-envelope mr-2"></i> Email
                            </a>
                            <a href="https://github.com/YOUR_GITHUB" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                <i class="fab fa-github mr-2"></i> GitHub
                            </a>
                            <a href="https://linkedin.com/in/YOUR_LINKEDIN" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                <i class="fab fa-linkedin mr-2"></i> LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technologies Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Technologies Powering TicketKing</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Frontend Technologies -->
                <div class="bg-white p-6 rounded-lg shadow-md tech-card frontend-card">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-laptop-code fa-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Frontend Technologies</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-2"></i>
                            <span><strong>HTML5</strong> – Structure of web pages</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-2"></i>
                            <span><strong>Tailwind CSS</strong> – Utility-first CSS framework for responsive and modern UI</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-2"></i>
                            <span><strong>JavaScript (ES6)</strong> – For interactivity, modal handling, AJAX requests</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-2"></i>
                            <span><strong>Font Awesome</strong> – Icons (e.g., for download, location, clock, etc.)</span>
                        </li>
                    </ul>
                </div>

                <!-- Backend Technologies -->
                <div class="bg-white p-6 rounded-lg shadow-md tech-card backend-card">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-server fa-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Backend Technologies</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span><strong>PHP (Core PHP)</strong> – Server-side scripting language</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span><strong>MySQL</strong> – Relational database management system</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span><strong>AJAX</strong> – Dynamic data loading without page reload</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span><strong>FPDF</strong> – PHP library to generate PDF tickets</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span><strong>phpqrcode</strong> – To generate QR codes for tickets</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span><strong>PHPMailer</strong> – To send emails with attachments</span>
                        </li>
                    </ul>
                </div>

                <!-- Features/Technologies -->
                <div class="bg-white p-6 rounded-lg shadow-md tech-card feature-card">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                            <i class="fas fa-star fa-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Key Features</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check text-purple-500 mt-1 mr-2"></i>
                            <span><strong>Sessions & Auth</strong> – User authentication, session handling</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-purple-500 mt-1 mr-2"></i>
                            <span><strong>Organizer Dashboard</strong> – CRUD operations using modals and AJAX</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-purple-500 mt-1 mr-2"></i>
                            <span><strong>File Uploads</strong> – Profile image uploads, PDF storage</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-purple-500 mt-1 mr-2"></i>
                            <span><strong>QR Code Integration</strong> – Used in PDF ticket to verify authenticity</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-purple-500 mt-1 mr-2"></i>
                            <span><strong>PDF Ticket Generation</strong> – Stored and downloadable</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-purple-500 mt-1 mr-2"></i>
                            <span><strong>Role-Based Access</strong> – Organizer vs regular user dashboards</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Project Description -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-4xl mx-auto p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">About TicketKing</h2>
            <p class="text-gray-600 mb-4">
                TicketKing is a comprehensive event ticketing platform designed to streamline the process of event management and ticket purchasing. TicketKing is inspired from <a href="https://tickify.live/" class="underline font-bold">tickify.live</a> .The platform offers:
            </p>
            <ul class="list-disc pl-5 space-y-2 text-gray-600 mb-6">
                <li>Seamless event creation and management for organizers</li>
                <li>Secure ticket purchasing and PDF generation for attendees</li>
                <li>QR code verification for event entry</li>
                <li>Responsive design that works across all devices</li>
                <li>Role-based access control for different user types</li>
            </ul>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <p class="text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i> This project showcases full-stack development skills with a focus on security, performance, and user experience.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer would go here -->
     <?php
     include "./footer.php";
     ?>
</body>
</html>