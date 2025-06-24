<?php
session_start();
require('./DB/database.php');
require('./phpqrcode/phpqrcode.php');

// Check if admin or organizer is logged in
$is_verifier = isset($_SESSION['admin']) || isset($_SESSION['organizer']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_verifier) {
    // Handle QR code scan submission
    $scanned_data = trim($_POST['scanned_data']);
    header("Location: verify_ticket.php?data=" . urlencode($scanned_data));
    exit;
}

// Get data from URL if coming from scan
$verification_data = $_GET['data'] ?? '';
$verification_result = null;

if (!empty($verification_data)) {
    // Parse the QR code data - more flexible parsing
    $parts = explode('|', $verification_data);
    $data = [];
    foreach ($parts as $part) {
        $key_value = explode(':', trim($part), 2);
        if (count($key_value) === 2) {
            $key = strtolower(trim($key_value[0]));
            $key = str_replace(' ', '_', $key); // Convert "order id" to "order_id"
            $data[$key] = trim($key_value[1]);
        }
    }

    // Check for order_id in different possible formats
    $order_id = null;
    if (isset($data['orderid'])) {
        $order_id = intval($data['orderid']);
    } elseif (isset($data['order_id'])) {
        $order_id = intval($data['order_id']);
    } elseif (isset($data['order'])) {
        $order_id = intval($data['order']);
    }

    if ($order_id) {
        // Verify the ticket in database
        $stmt = $conn->prepare("
            SELECT o.*, e.title as event_title, e.date as event_date, 
                   e.location as event_location, t.name as ticket_name,
                   u.name as user_name, u.email as user_email
            FROM orders o
            JOIN events e ON o.event_id = e.id
            JOIN tickets t ON o.ticket_id = t.id
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ticket = $result->fetch_assoc();
        $stmt->close();

        if ($ticket) {
            // Check if ticket is already used
            $already_used = ($ticket['attended'] == 1);
            
            $verification_result = [
                'valid' => !$already_used,
                'ticket' => $ticket,
                'scanned_name' => $data['name'] ?? '',
                'scanned_ticket' => $data['ticket'] ?? '',
                'already_used' => $already_used
            ];
            
            if ($already_used) {
                $verification_result['error'] = 'This ticket has already been used';
            }
        } else {
            $verification_result = [
                'valid' => false,
                'error' => 'Ticket not found in database'
            ];
        }
    } else {
        $verification_result = [
            'valid' => false,
            'error' => 'Invalid QR code format - Order ID not found',
            'debug_data' => $data // For debugging purposes
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Verification | TicketKing</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        .debug-info {
            font-size: 0.8rem;
            color: #666;
            background-color: #f5f5f5;
            padding: 0.5rem;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>

    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-qrcode mr-2"></i> Ticket Verification
            </h1>

            <?php if ($is_verifier): ?>
                <div class="mb-8">
                    <div id="qr-reader" class="mb-4 border-2 border-dashed border-gray-300 rounded-lg p-4"></div>
                    
                    <form method="POST" class="mb-6">
                        <div class="flex">
                            <input type="text" name="scanned_data" placeholder="Or enter QR code data manually" 
                                   class="flex-1 px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#003C2F]"
                                   value="<?= htmlspecialchars($verification_data) ?>">
                            <button type="submit" class="bg-[#003C2F] text-white px-4 py-2 rounded-r-lg hover:bg-[#00291f]">
                                <i class="fas fa-check"></i> Verify
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if (!empty($verification_data)): ?>
                <div class="border-t pt-6">
                    <?php if ($verification_result['valid']): ?>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-green-800">Valid Ticket</h3>
                                    <p class="text-green-700">This ticket is valid for entry</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-lg mb-3 text-gray-800">
                                    <i class="fas fa-ticket-alt mr-2"></i> Ticket Details
                                </h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Order ID:</span> <?= $verification_result['ticket']['id'] ?></p>
                                    <p><span class="font-medium">Ticket Holder:</span> <?= htmlspecialchars($verification_result['ticket']['user_name']) ?></p>
                                    <p><span class="font-medium">Email:</span> <?= htmlspecialchars($verification_result['ticket']['user_email']) ?></p>
                                    <p><span class="font-medium">Ticket Type:</span> <?= htmlspecialchars($verification_result['ticket']['ticket_name']) ?></p>
                                    <p><span class="font-medium">Quantity:</span> <?= $verification_result['ticket']['quantity'] ?></p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-lg mb-3 text-gray-800">
                                    <i class="fas fa-calendar-alt mr-2"></i> Event Details
                                </h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Event:</span> <?= htmlspecialchars($verification_result['ticket']['event_title']) ?></p>
                                    <p><span class="font-medium">Date:</span> <?= date('F j, Y', strtotime($verification_result['ticket']['event_date'])) ?></p>
                                    <p><span class="font-medium">Time:</span> <?= date('g:i A', strtotime($verification_result['ticket']['event_date'])) ?></p>
                                    <p><span class="font-medium">Location:</span> <?= htmlspecialchars($verification_result['ticket']['event_location']) ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($is_verifier): ?>
                            <form method="POST" action="mark_attended.php" class="mt-6">
                                <input type="hidden" name="order_id" value="<?= $verification_result['ticket']['id'] ?>">
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                    <i class="fas fa-check-circle mr-2"></i> Mark as Attended
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-red-800">Invalid Ticket</h3>
                                    <p class="text-red-700"><?= $verification_result['error'] ?></p>
                                    <?php if (isset($verification_result['debug_data'])): ?>
                                        <div class="debug-info">
                                            <p>Scanned data: <?= htmlspecialchars($verification_data) ?></p>
                                            <p>Parsed as: <?= print_r($verification_result['debug_data'], true) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif ($is_verifier): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-qrcode text-4xl mb-3"></i>
                    <p>Scan a ticket QR code to verify</p>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-lock text-4xl mb-3"></i>
                    <p>You need to be logged in as an admin or organizer to verify tickets</p>
                    <a href="login.php" class="mt-4 inline-block bg-[#003C2F] text-white px-4 py-2 rounded-lg hover:bg-[#00291f]">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($is_verifier): ?>
    <script>
    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning
        html5QrcodeScanner.clear();
        
        // Submit the form with scanned data
        document.querySelector('input[name="scanned_data"]').value = decodedText;
        document.querySelector('form').submit();
    }

    function onScanFailure(error) {
        // Handle scan failure
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { 
            fps: 10,
            qrbox: { width: 250, height: 250 },
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        },
        /* verbose= */ false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
    <?php endif; ?>

    <?php include "./footer.php"; ?>
</body>
</html>