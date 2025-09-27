<!-- Testimonials Section -->
<section id="testimonials" class="section">
    <h2>Testimonials</h2>
    <div class="testimonial-carousel-container">
        <div class="testimonial-carousel">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-item">
                        <p>"<?php echo htmlspecialchars($testimonial['quote']); ?>"</p>
                        <span>- <?php echo htmlspecialchars($testimonial['author']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="testimonial-item">
                    <p>"No testimonials yet. Check back later!"</p>
                    <span>- Admin</span>
                </div>
            <?php endif; ?>
        </div>
        <button class="carousel-btn prev-btn">‹</button>
        <button class="carousel-btn next-btn">›</button>
    </div>
</section>