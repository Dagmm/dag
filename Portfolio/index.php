
<?php
require_once 'db_connection.php';
$db = new Database();
$conn = $db->conn;

$sections = $conn->query("SELECT * FROM sections ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$projects = $conn->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Portfolio</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php foreach ($sections as $section): ?>
        <section id="<?= strtolower($section['section_name']) ?>">
            <h2><?= htmlspecialchars($section['section_name']) ?></h2>
                  <div>
                <?= nl2br(htmlspecialchars($section['content'])) ?>
            </div>
        </section>
    <?php endforeach; ?>

    <section id="portfolio">
        <h2>Projects</h2>
        <div class="projects">
            <?php foreach ($projects as $project): ?>
                <div class="project">
                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                    <?php if (!empty($project['image_url'])): ?>
                        <img src="<?= htmlspecialchars($project['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($project['link']) ?>" target="_blank">View Project</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="testimonials">
        <h2>Testimonials</h2>
        <div class="testimonials">
            <?php foreach ($testimonials as $testimonial): ?>
                <blockquote>
                    <?= nl2br(htmlspecialchars($testimonial['feedback'])) ?>
                    <cite>— <?= htmlspecialchars($testimonial['name']) ?></cite>
                </blockquote>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="contact">
        <h2>Contact</h2>
        <form action="submit_contact.php" method="POST">
            <input type="text" name="full_name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="text" name="phone_number" placeholder="Your Phone">
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </section>
</body>
</html>




<?php
