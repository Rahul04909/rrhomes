<?php
// contact-us.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | RR Homes</title>
    <style>
        .page-hero {
            position: relative;
            height: 45vh;
            background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            margin-top: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5));
        }

        .page-hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #fff;
        }

        .page-hero-content h1 {
            font-size: 4rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            font-weight: 800;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .page-hero-content h1 span {
            color: #d4af37;
        }

        .contact-section {
            padding: 90px 5%;
            background: #ffffff;
        }

        .contact-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: stretch;
        }

        .contact-info-wrapper {
            background: #1a1a1a;
            color: #fff;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-left: 6px solid #d4af37;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .info-icon {
            background: #d4af37;
            color: #fff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 25px;
            flex-shrink: 0;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .info-content h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #eaeaea;
        }

        .info-content p,
        .info-content a {
            font-size: 1.1rem;
            color: #bbb;
            line-height: 1.6;
            text-decoration: none;
            transition: color 0.3s;
        }

        .info-content a:hover {
            color: #d4af37;
        }

        .contact-form-wrapper {
            background: #fff;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        }

        .form-title {
            font-size: 2.2rem;
            color: #111;
            margin-bottom: 20px;
            font-weight: 800;
            text-transform: uppercase;
            position: relative;
            padding-bottom: 15px;
        }

        .form-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 4px;
            background: #d4af37;
        }

        .form-subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-group-custom label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 15px 20px;
            border: 1px solid #ddd;
            background: #fdfdfd;
            color: #333;
            border-radius: 6px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #d4af37;
            outline: none;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
            background: #fff;
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 120px;
        }

        .skew-btn-gold {
            display: inline-block;
            background-color: #d4af37;
            color: #fff;
            text-decoration: none;
            padding: 16px 30px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 1rem;
            letter-spacing: 1.5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 6px;
            width: 100%;
            text-align: center;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .skew-btn-gold:hover {
            background-color: #1a1a1a;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        @media(max-width: 991px) {
            .contact-container {
                grid-template-columns: 1fr;
            }

            .page-hero h1 {
                font-size: 2.8rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="page-hero">
        <div class="page-hero-content">
            <h1>Get In <span>Touch</span></h1>
            <p
                style="font-size: 1.25rem; max-width: 650px; margin: auto; opacity: 0.9; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                We'd love to hear from you. Let's build something extraordinary together.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-info-wrapper">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-content">
                        <h3>Our Headquarters</h3>
                        <p>Sector 16, Faridabad<br>Delhi NCR, India 121002</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="info-content">
                        <h3>Phone Number</h3>
                        <a href="tel:+919876543210">+91 98765 43210</a><br>
                        <a href="tel:+919876543211">+91 98765 43211</a>
                    </div>
                </div>
                <div class="info-item" style="margin-bottom: 0;">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-content">
                        <h3>Email Address</h3>
                        <a href="mailto:info@rrhomes.in">info@rrhomes.in</a><br>
                        <a href="mailto:sales@rrhomes.in">sales@rrhomes.in</a>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper">
                <h2 class="form-title">Send a Message</h2>
                <p class="form-subtitle">Have a question regarding our projects or looking for a consultation? Feel free
                    to reach out to us using the form below.</p>

                <?php
                $msg = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
                    $msg = "<div style='color:#28a745; background:rgba(40,167,69,0.1); padding:15px; border-radius:5px; margin-bottom:25px; font-weight:bold; border-left:4px solid #28a745;'>Your message has been successfully sent! Our team will contact you soon.</div>";
                }
                ?>
                <?= $msg ?>

                <form class="contact-form" method="POST">
                    <div class="form-group-custom">
                        <label>Your Name *</label>
                        <input type="text" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group-custom">
                        <label>Email Address *</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group-custom">
                        <label>Phone Number *</label>
                        <input type="text" name="phone" placeholder="Enter your mobile number" required>
                    </div>
                    <div class="form-group-custom">
                        <label>Message / Inquiry</label>
                        <textarea name="message" placeholder="How can we help you today?" required></textarea>
                    </div>
                    <button type="submit" name="submit_contact" class="skew-btn-gold">SEND MESSAGE</button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>

</html>