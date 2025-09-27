<!-- Projects Section -->
<section id="projects" class="section">
    <h2>Projects</h2>
    <div class="projects-grid">
        <?php foreach ($projects as $project): ?>
        <div class="project-card">
            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
            <?php if (!empty($project['media_url'])):
                $media_files = explode(',', $project['media_url']);
                $first_image = trim($media_files[0]);
            ?>
                <img src="<?php echo htmlspecialchars($first_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="project-thumbnail" data-media='<?php echo htmlspecialchars(json_encode(array_map('trim', $media_files)), ENT_QUOTES, 'UTF-8'); ?>'>
            <?php endif; ?>
            <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>