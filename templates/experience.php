<!-- Experience Section -->
<section id="experience" class="section">
    <h2>Experience</h2>
    <div class="timeline">
        <?php foreach ($experiences as $exp): ?>
        <div class="timeline-item">
            <div class="timeline-year"><?php echo htmlspecialchars($exp['start_year']); ?> - <?php echo htmlspecialchars($exp['end_year']); ?></div>
            <div class="timeline-content">
                <h3><?php echo htmlspecialchars($exp['title']); ?></h3>
                <h4><?php echo htmlspecialchars($exp['institution']); ?></h4>
                <p><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>