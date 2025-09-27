<!-- Home Section -->
<section id="home" class="section hero">
    <div class="hero-content">
        <h1><?php echo htmlspecialchars($about['name'] ?? 'Jane Doe'); ?></h1>
        <p>"<?php echo htmlspecialchars($about['tagline'] ?? 'Inspiring young minds through patience'); ?>"</p>
        <div class="cta-buttons">
            <a href="#download" class="cta-button">View Resume</a>
            <a href="#contact" class="cta-button">Contact Me</a>
        </div>
    </div>
    <!-- Mini highlights carousel can be added here later -->
</section>