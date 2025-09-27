<!-- About Me Section -->
<section id="about" class="section">
    <h2>About Me</h2>
    <div class="about-content">
        <p><?php echo nl2br(htmlspecialchars($about['bio'] ?? '')); ?></p>
        <h3>My Philosophy</h3>
        <p><?php echo htmlspecialchars($about['philosophy'] ?? ''); ?></p>
        <!-- Optional video embed can be added here -->
    </div>
</section>