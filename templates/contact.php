<!-- Contact Section -->
<section id="contact" class="section">
    <h2>Contact Me</h2>
    <div class="contact-grid">
        <div class="contact-info">
            <?php if (!empty($about['address'])): ?>
            <div class="contact-item"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($about['address']); ?></div>
            <?php endif; ?>
            <?php if (!empty($about['email'])): ?>
            <div class="contact-item"><i class="fas fa-envelope"></i> <a href="mailto:<?php echo htmlspecialchars($about['email']); ?>"><?php echo htmlspecialchars($about['email']); ?></a></div>
            <?php endif; ?>
            <?php if (!empty($about['facebook_url'])): ?>
            <div class="contact-item"><i class="fab fa-facebook"></i> <a href="<?php echo htmlspecialchars($about['facebook_url']); ?>" target="_blank">Facebook</a></div>
            <?php endif; ?>
            <?php if (!empty($about['tiktok_url'])): ?>
                <div class="contact-item"><i class="fab fa-tiktok"></i> <a href="<?php echo htmlspecialchars($about['tiktok_url']); ?>" target="_blank">TikTok</a></div>
            <?php endif; ?>
             <?php if (!empty($about['youtube_url'])): ?>
                <div class="contact-item"><i class="fab fa-youtube"></i> <a href="<?php echo htmlspecialchars($about['youtube_url']); ?>" target="_blank">YouTube</a></div>
            <?php endif; ?>
            <?php if (!empty($about['instagram_url'])): ?>
                <div class="contact-item"><i class="fab fa-instagram"></i> <a href="<?php echo htmlspecialchars($about['instagram_url']); ?>" target="_blank">Instagram</a></div>
            <?php endif; ?>
        </div>
        <div class="contact-form">
            <form action="api/contact.php" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</section>