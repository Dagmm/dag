<?php
require_once 'Portfolio.php';
require_once 'HtmlRenderer.php';

$portfolio = new Portfolio();
$about = $portfolio->getAboutSection();
$projects = $portfolio->getProjects();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <a href="#" class="logo">dag</a>
        <nav>
            <a href="#home">Home</a>
            <a href="#projects">Projects</a>
            <a href="#about">About</a>
        </nav>
        <a href="#contact" class="contact">Contact</a>
    </header>

    <section id="home">
        <div class="home-content">

            <h2>I am <span>Dag</span>, a C.S. student</h2>
            <p>Welcome to my portfolio!</p>
        </div>
    </section>

    <section id="about">
        <?php HtmlRenderer::renderAboutSection($about); ?>
    </section>

    <section id="projects">
        <h2>My Projects</h2>
        <?php HtmlRenderer::renderProjects($projects); ?>
    </section>

    <section id="contact">
        <h2>Contact Me</h2>
        <form action="submit_contact.php" method="POST">
            <div class="input-box">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="text" name="phone_number" placeholder="Phone Number">
                <input type="text" name="subject" placeholder="Subject">
            </div>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <input type="submit" value="Send Message" class="btn">
        </form>
    </section>

    <footer>
        <div class="social">
            <a href="#"><i class='bx bxl-linkedin-square'></i></a>
            <a href="#"><i class='bx bxl-facebook-circle'></i></a>
            <a href="#"><i class='bx bxl-instagram'></i></a>
            <a href="#"><i class='bx bxl-github'></i></a>
        </div>
        <ul>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Projects</a></li>
            <li><a href="#">About Me</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Privacy Policy</a></li>
        </ul>
    </footer>
</body>
</html>

