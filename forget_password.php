<?php
session_start();
include 'DB/database.php'; // Your database connection file

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_otp'])) {
        // Step 1: Send OTP to email
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        
        // Check if email exists
        $result = mysqli_query($conn, "SELECT id, name FROM users WHERE email = '$email'");
        if (mysqli_num_rows($result)) {
            $user = mysqli_fetch_assoc($result);
            
            // Generate 6-digit OTP
            $otp = rand(100000, 999999);
            
            // Store OTP in session with expiration (10 minutes)
            $_SESSION['password_reset'] = [
                'user_id' => $user['id'],
                'otp' => $otp,
                'expires' => time() + 600, // 10 minutes
                'email' => $email
            ];
            
            // Send OTP email
            $subject = "Your Tickify Password Reset OTP";
            $message = "Hi {$user['name']},\n\n";
            $message .= "Your OTP for password reset is: $otp\n";
            $message .= "This code is valid for 10 minutes.\n\n";
            $message .= "If you didn't request this, please ignore this email.\n";
            $message .= "Best regards,\nTickify Team";
            
            $headers = "From: tahsinniyan@gmail.com\r\n";
            
            if (mail($email, $subject, $message, $headers)) {
                $success = "OTP sent to your email!";
            } else {
                $error = "Failed to send OTP. Please try again.";
            }
        } else {
            $error = "No account found with that email address.";
        }
    }
    elseif (isset($_POST['reset_password'])) {
        // Step 2: Verify OTP and reset password
        $otp_entered = $_POST['otp'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (isset($_SESSION['password_reset'])) {
            $reset_data = $_SESSION['password_reset'];
            
            // Check expiration
            if (time() > $reset_data['expires']) {
                $error = "OTP has expired. Please request a new one.";
                unset($_SESSION['password_reset']);
            }
            // Verify OTP
            elseif ($otp_entered != $reset_data['otp']) {
                $error = "Invalid OTP. Please try again.";
            }
            // Check password match
            elseif ($new_password !== $confirm_password) {
                $error = "Passwords do not match.";
            }
            // All valid - reset password
            else {
                $user_id = $reset_data['user_id'];
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
                if (mysqli_query($conn, $update_query)) {
                    $success = "Password reset successfully!";
                    unset($_SESSION['password_reset']);
                    
                    // Redirect to login after 3 seconds
                    header("Refresh: 3; url=login.php");
                } else {
                    $error = "Error updating password: " . mysqli_error($conn);
                }
            }
        } else {
            $error = "OTP session expired. Please start over.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset | Tickify</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
        }
        
        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            text-align: center;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            margin: 0 5px;
        }
        
        .otp-input:focus {
            border-color: #4f46e5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(90deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        
        .countdown {
            font-size: 1.2rem;
            font-weight: 600;
            color: #ef4444;
        }
        
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="w-full max-w-md mx-4">
        <div class="card bg-white">
            <div class="card-header py-6 px-8">
                <div class="flex items-center justify-center">
                    <div class="bg-white p-2 rounded-full">
                        <i class="fas fa-lock text-indigo-600 text-3xl"></i>
                    </div>
                    <h1 class="ml-4 text-2xl font-bold text-white">Password Reset</h1>
                </div>
            </div>
            
            <div class="py-8 px-8">
                <?php if (isset($error)): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!isset($_SESSION['password_reset'])): ?>
                    <!-- Step 1: Email Form -->
                    <form method="POST">
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="email">
                                Enter your email address
                            </label>
                            <div class="relative">
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="you@example.com"
                                >
                                <i class="fas fa-envelope absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                We'll send a 6-digit OTP to your email.
                            </p>
                        </div>
                        
                        <button 
                            type="submit" 
                            name="send_otp"
                            class="w-full btn-primary text-white font-semibold py-3 px-4 rounded-lg shadow"
                        >
                            Send OTP
                            <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                    
                    <div class="mt-6 text-center">
                        <a href="login.php" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Login
                        </a>
                    </div>
                    
                <?php else: ?>
                    <!-- Step 2: OTP and New Password Form -->
                    <form method="POST">
                        <div class="mb-6 text-center">
                            <p class="text-gray-600 mb-4">
                                We've sent a 6-digit OTP to:<br>
                                <span class="font-semibold"><?php echo $_SESSION['password_reset']['email']; ?></span>
                            </p>
                            
                            <div class="flex justify-center mb-4">
                                <?php for ($i = 0; $i < 6; $i++): ?>
                                    <input 
                                        type="text" 
                                        maxlength="1"
                                        name="otp[]"
                                        class="otp-input"
                                        required
                                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); moveToNext(this)"
                                        onkeyup="if(event.key === 'Backspace') moveToPrev(this)"
                                    >
                                <?php endfor; ?>
                                <input type="hidden" name="otp" id="full-otp">
                            </div>
                            
                            <p class="text-sm text-gray-500">
                                OTP expires in: 
                                <span class="countdown" id="countdown">10:00</span>
                            </p>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="new_password">
                                New Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="new_password" 
                                    name="new_password" 
                                    required
                                    minlength="8"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="At least 8 characters"
                                >
                                <span class="password-toggle" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2" for="confirm_password">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="confirm_password" 
                                    name="confirm_password" 
                                    required
                                    minlength="8"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Re-enter your password"
                                >
                                <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mb-6">
                            <button 
                                type="submit" 
                                name="reset_password"
                                class="w-full btn-primary text-white font-semibold py-3 px-4 rounded-lg shadow"
                            >
                                Reset Password
                                <i class="fas fa-redo-alt ml-2"></i>
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <a href="#" onclick="resendOTP()" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                <i class="fas fa-sync-alt mr-2"></i> Resend OTP
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 text-center text-gray-600 text-sm">
            <p>© <?php echo date('Y'); ?> Tickify. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Combine OTP inputs into a single field
        document.querySelector('form').addEventListener('submit', function(e) {
            if (document.getElementById('full-otp')) {
                const otpInputs = document.querySelectorAll('input[name="otp[]"]');
                let fullOtp = '';
                otpInputs.forEach(input => fullOtp += input.value);
                document.getElementById('full-otp').value = fullOtp;
            }
        });
        
        // Move to next OTP field on input
        function moveToNext(input) {
            if (input.value.length >= input.maxLength) {
                let next = input.nextElementSibling;
                if (next) next.focus();
            }
        }
        
        // Move to previous OTP field on backspace
        function moveToPrev(input) {
            if (input.value.length === 0) {
                let prev = input.previousElementSibling;
                if (prev) prev.focus();
            }
        }
        
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.nextElementSibling.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }
        
        // OTP expiration countdown
        <?php if (isset($_SESSION['password_reset'])): ?>
            let timeLeft = <?php echo $_SESSION['password_reset']['expires'] - time(); ?>;
            const countdownEl = document.getElementById('countdown');
            
            function updateCountdown() {
                if (timeLeft <= 0) {
                    countdownEl.textContent = '00:00';
                    // Optionally redirect or refresh
                    return;
                }
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timeLeft--;
                
                setTimeout(updateCountdown, 1000);
            }
            
            updateCountdown();
        <?php endif; ?>
        
        // Resend OTP
        function resendOTP() {
            if (confirm("Resend OTP to your email?")) {
                // In a real app, you would make an AJAX request here
                // For simplicity, we'll simulate with a reload
                window.location.href = "forget_password.php?resend=1";
            }
        }
    </script>
</body>
</html>