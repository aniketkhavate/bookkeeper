<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="High-quality printing services for businesses and individuals.">
    <meta name="keywords" content="printing, print shop, design, custom prints, banners, business cards">
    <meta name="author" content="PrintID">
    <title>PrintID - Quality Printing Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }
        .navbar {
            background-color: #333;
        }
        .navbar a {
            color: white !important;
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
        }
        .navbar a:hover {
            background-color: #555;
            border-radius: 5px;
        }
        .hero {
            background-image: url('https://bookkeeper.printid.in/banner.png');
            background-size: cover;
            background-position: center;
            padding: 80px 0;
            color: white;
            text-align: center;
        }
        .hero h1 {
            font-size: 60px;
            font-weight: bold;
        }
        .hero p {
            font-size: 22px;
            margin-bottom: 30px;
        }
        .cta-button {
            background-color: #f39c12;
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 18px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease;
        }
        .cta-button:hover {
            background-color: #e67e22;
        }
        .service-section {
            background-color: #fff;
            padding: 60px 0;
        }
        .service-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-10px);
        }
        .service-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .service-card .card-body {
            padding: 30px;
        }
        .service-card .card-title {
            font-size: 24px;
            margin-bottom: 15px;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 30px 0;
            text-align: center;
        }
        .footer p {
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">PrintID</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Your Trusted Printing Partner</h1>
            <p>We offer top-quality printing solutions for your business and personal needs.</p>
            <a href="#contact" class="cta-button">Get a Quote</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="service-section">
        <div class="container text-center">
            <h2 class="mb-5">Our Printing Services</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card service-card">
                        <img src="https://bookkeeper.printid.in/500by300.png" class="card-img-top" alt="Business Cards">
                        <div class="card-body">
                            <h5 class="card-title">Business Cards</h5>
                            <p>Professional business cards that make a lasting impression. Customizable designs for any industry.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card service-card">
                        <img src="https://bookkeeper.printid.in/500by300.png" class="card-img-top" alt="Banners & Posters">
                        <div class="card-body">
                            <h5 class="card-title">Banners & Posters</h5>
                            <p>High-quality banners and posters for your business or event. Designed for visibility and impact.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card service-card">
                        <img src="https://bookkeeper.printid.in/500by300.png" class="card-img-top" alt="Flyers & Brochures">
                        <div class="card-body">
                            <h5 class="card-title">Flyers & Brochures</h5>
                            <p>Custom flyers and brochures that help you communicate effectively with your audience.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="text-center py-5">
        <div class="container">
            <h2 class="mb-4">Get In Touch</h2>
            <p>If you have any questions or need a custom quote, don't hesitate to contact us.</p>
            <a href="mailto:info@printid.in" class="cta-button">Contact Us</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 PrintID | Quality Printing Services</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
