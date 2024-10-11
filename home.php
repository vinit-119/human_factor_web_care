<?php
// home.php
// Start session to access user data
session_start();
include 'navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaRe Wellness App - Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <!-- Intro Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-details">
                <h2>Welcome to CaRe Wellness App</h2>
                <p>Your partner in mental wellness, providing you with the tools and support you need to enhance your mental health journey. Whether you are a patient, therapist, or wellness professional, CaRe is here to support you.</p>
                <blockquote>"The journey to wellness begins with a single step. Let us walk this path together."</blockquote>
                <a href="login.php" class="learn-more-btn">Get Started</a>
            </div>
            <div class="intro-image">
                <img src="images/tracking activity.png" alt="Mental Wellness">
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <h2 class="services-heading">Our Services</h2>
        <div class="services-slider">
            <div class="service-item">
                <img src="images/therapy.jpeg" alt="Therapy Session">
                <h3>Therapy Sessions</h3>
                <p>One-on-one sessions with certified therapists to guide you on your mental health journey.</p>
            </div>
            <div class="service-item">
                <img src="images/group_therapy.jpeg" alt="Group Therapy">
                <h3>Group Therapy</h3>
                <p>Join group therapy sessions for collective support and shared experiences.</p>
            </div>
            <div class="service-item">
                <img src="images/wellmess coaching.jpeg" alt="Wellness Coaching">
                <h3>Wellness Coaching</h3>
                <p>Personalized wellness coaching to help you set and achieve your mental health goals.</p>
            </div>
        </div>
    </section>

    <!-- Group Chat Showcase Section -->
    <section class="group-chat-showcase">
        <h2 class="group-chat-heading">Connect with Your Community</h2>
        <div class="chat-container">
            <div class="chat-messages">
                <div class="message patient-message">
                    <div class="message-content">"I found the group sessions really helpful, everyone is so supportive!"</div>
                    <span class="message-author">- John Doe, Patient</span>
                </div>
                <div class="message therapist-message">
                    <div class="message-content">"We are all in this together. Keep sharing your thoughts!"</div>
                    <span class="message-author">- Dr. Emily White, Therapist</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Motivational Footer -->
    <footer class="motivational-footer">
        <div class="footer-container">
            <div class="footer-left">
                <h4>Contact Us</h4>
                <p>Email: support@carewellness.com</p>
                <p>Phone: +1 800 123 456</p>
            </div>
            <div class="footer-middle">
                <blockquote>"Wellness is a journey, not a destination. Take it one step at a time."</blockquote>
            </div>
            <div class="footer-right">
                <p>Follow Us:</p>
                <div class="social-icons">
                    <a href="#"><img src="images/facebook-icon.png" alt="Facebook"></a>
                    <a href="#"><img src="images/twitter-icon.png" alt="Twitter"></a>
                    <a href="#"><img src="images/instagram-icon.png" alt="Instagram"></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
