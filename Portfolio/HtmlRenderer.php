<?php
class HtmlRenderer {
    public static function renderAboutSection($about) {
        if ($about) {
            echo "<div class='about-content'>
                    <h2>" . htmlspecialchars($about->title) . "</h2>
                    <h3>" . htmlspecialchars($about->subtitle) . "</h3>
                    <p>" . htmlspecialchars($about->description) . "</p>
                  </div>";
        } else {
            echo "<p>About section is under maintenance.</p>";
        }
    }

    public static function renderProjects($projects) {
        if ($projects) {
            foreach ($projects as $project) {
                echo "<div class='project-item'>
                        <h3>" . htmlspecialchars($project->title) . "</h3>
                        <p>" . htmlspecialchars($project->description) . "</p>
                        <img src='" . htmlspecialchars($project->image_url) . "' alt='" . htmlspecialchars($project->title) . "'>
                        <a href='" . htmlspecialchars($project->link) . "' class='btn'>View Project</a>
                      </div>";
            }
        } else {
            echo "<p>No projects available at the moment.</p>";
        }
    }
}
?>

