<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickify - Features & FAQs</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-text {
            background: linear-gradient(90deg, #4f46e5, #10b981);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .feature-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-left-color: #3B82F6;
        }
        .faq-item {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        .faq-item:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .payment-method {
            filter: grayscale(100%);
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        .payment-method:hover {
            filter: grayscale(0);
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Our Offerings Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                Our <span class="bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">Offerings</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Explore the key features that make Tickify the perfect choice for event organizers!
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            <!-- Feature 1 -->
            <div class="feature-card bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-100 p-3 rounded-full mr-4">
                        <i class="fas fa-ticket-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Easy Ticket Purchase</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Browse and purchase tickets for a variety of events, from concerts to conferences, all from your device with ease and convenience.
                </p>
                <div class="flex justify-center space-x-4">
                    <div class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-mobile-alt mr-2"></i> Mobile Friendly
                    </div>
                    <div class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-clock mr-2"></i> 24/7 Access
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-bolt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Instant Ticket Delivery</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Receive your tickets immediately upon purchase via email. If preferred, users can also opt to receive their tickets on WhatsApp.
                </p>
                <div class="flex space-x-4">
                    <div class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-envelope mr-2"></i> Email
                    </div>
                    <div class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </div>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <i class="fas fa-wallet text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Multiple Payment Methods</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Enjoy flexible payment options ensuring secure and smooth transactions.
                </p>
                <div class="grid grid-cols-4 gap-4">
                    <img src="https://logo.clearbit.com/bkash.com" alt="bKash" class="payment-method h-12 object-contain">
                    <img src="https://logo.clearbit.com/nagad.com.bd" alt="Nagad" class="payment-method h-12 object-contain">
                    <img src="https://logo.clearbit.com/upay.com" alt="Upay" class="payment-method h-12 object-contain">
                    <img src="https://logo.clearbit.com/visa.com" alt="Visa" class="payment-method h-12 object-contain">
                    <img src="https://logo.clearbit.com/mastercard.com" alt="Mastercard" class="payment-method h-12 object-contain">
                    <img src="https://logo.clearbit.com/pay.google.com.bd" alt="Google Pay" class="payment-method h-12 object-contain">
                    <img src="https://logo.clearbit.com/americanexpress.com" alt="Amex" class="payment-method h-12 object-contain">
                    <img src="https://logo.clearbit.com/paypal.com" alt="PayPal" class="payment-method h-12 object-contain">
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="feature-card bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-6">
                    <div class="bg-pink-100 p-3 rounded-full mr-4">
                        <i class="fas fa-star text-pink-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Tickipass Feature</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-green-100 p-2 rounded-full mr-4 mt-1">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Comprehensive Dashboard</h4>
                            <p class="text-gray-600 text-sm">Real-time analytics and event management tools</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-full mr-4 mt-1">
                            <i class="fas fa-qrcode text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Smooth Scanning</h4>
                            <p class="text-gray-600 text-sm">Fast and reliable QR code validation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto bg-white">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                <span class="bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">FAQs</span>
            </h2>
            <p class="text-xl text-gray-600">
                Our FAQs are here to help you get the most out of TicketKing
            </p>
        </div>

        <div class="space-y-4">
            <!-- FAQ 1 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg border border-gray-200">
                <button class="flex justify-between items-center w-full text-left focus:outline-none group">
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600">
                        How do I pay for the tickets?
                    </h3>
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600 transform group-focus:-rotate-180 transition-transform" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="mt-4 text-gray-600">
                    <p>
                        Depending on the organizers, you can pay for tickets using VISA & Mastercard, bKash, Nagad, Upay, and other supported payment methods.
                    </p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">VISA</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Mastercard</span>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">bKash</span>
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">Nagad</span>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Upay</span>
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg border border-gray-200">
                <button class="flex justify-between items-center w-full text-left focus:outline-none group">
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600">
                        I can't access the email of my TicketKing account. What to do?
                    </h3>
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600 transform group-focus:-rotate-180 transition-transform" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="mt-4 text-gray-600 hidden">
                    <p>
                        If you're having trouble accessing the email associated with your TicketKing account:
                    </p>
                    <ul class="mt-2 space-y-2 list-disc list-inside">
                        <li>Check your spam or junk folder</li>
                        <li>Try resetting your password using your phone number</li>
                        <li>Contact our support team at tahsinniyan@gmail.com</li>
                        <li>Verify you're checking the correct email address</li>
                    </ul>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg border border-gray-200">
                <button class="flex justify-between items-center w-full text-left focus:outline-none group">
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600">
                        Can I transfer my ticket to someone else?
                    </h3>
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600 transform group-focus:-rotate-180 transition-transform" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="mt-4 text-gray-600 hidden">
                    <p>
                        Ticket transfer availability depends on the event organizer's policy. You can check the event details page or contact the organizer directly.
                    </p>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg border border-gray-200">
                <button class="flex justify-between items-center w-full text-left focus:outline-none group">
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600">
                        What is your refund policy?
                    </h3>
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600 transform group-focus:-rotate-180 transition-transform" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="mt-4 text-gray-600 hidden">
                    <p>
                        Refund policies are set by each event organizer and may vary:
                    </p>
                    <ul class="mt-2 space-y-2 list-disc list-inside">
                        <li>Most events offer refunds up to 7 days before the event</li>
                        <li>Some special events may have non-refundable tickets</li>
                        <li>Processing time for refunds is typically 5-10 business days</li>
                        <li>Contact the organizer through the event page for specific policies</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        // FAQ Accordion functionality
        document.querySelectorAll('.faq-item button').forEach(button => {
            button.addEventListener('click', () => {
                const faqItem = button.closest('.faq-item');
                const content = button.nextElementSibling;
                
                // Toggle the hidden class
                content.classList.toggle('hidden');
                
                // Close other open FAQs
                document.querySelectorAll('.faq-item button').forEach(otherButton => {
                    if (otherButton !== button) {
                        otherButton.nextElementSibling.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>