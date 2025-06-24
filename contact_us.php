<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TicketKing</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .contact-card {
            transition: all 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
            <p class="text-lg text-gray-600">Saturday - Thursday (11 AM - 7 PM)</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <!-- Messenger Card -->
            <div class="bg-white rounded-lg shadow-md p-6 contact-card">
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Messenger</h3>
                    <p class="text-gray-600 mb-4">Connect with TicketKing's official page for prompt assistance.</p>
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        Chat on Messenger
                    </a>
                </div>
            </div>

            <!-- WhatsApp Card -->
            <div class="bg-white rounded-lg shadow-md p-6 contact-card">
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-green-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">WhatsApp</h3>
                    <p class="text-gray-600 mb-1">+8801715710035</p>
                    <p class="text-gray-600 mb-4">Chat with TicketKing on WhatsApp for quick support.</p>
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                        Chat on WhatsApp
                    </a>
                </div>
            </div>

            <!-- Phone Card -->
            <div class="bg-white rounded-lg shadow-md p-6 contact-card">
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Phone</h3>
                    <p class="text-gray-600 mb-1">+8801715710035</p>
                    <p class="text-gray-600 mb-4">Call TicketKing via phone for immediate assistance.</p>
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700">
                        Call Us
                    </a>
                </div>
            </div>

            <!-- Email Card -->
            <div class="bg-white rounded-lg shadow-md p-6 contact-card">
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-red-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600 mb-1">TicketKing.iwegammal.com</p>
                    <p class="text-gray-600 mb-4">Email TicketKing for detailed responses to your queries.</p>
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                        Send Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Address and Map Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Location</h2>
                <p class="text-gray-600 mb-6">Block-C, Road-5/A, Bashundhara R A, Dhaka</p>
                
                <!-- Map Container -->
                <div class="relative h-96 w-full overflow-hidden rounded-lg">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.793848634198!2d90.4233533153857!3d23.7908687932256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7a0e9e8a5a9%3A0x6f5a5e5b5e5b5e5b!2sBashundhara%20R%20A!5e0!3m2!1sen!2sbd!4v1620000000000!5m2!1sen!2sbd" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer would go here -->
     <?php
     include "./footer.php";
     ?>
</body>
</html>