<!-- Education Section -->
<section id="education" class="section">
    <h2>Education</h2>
    <div class="education-list">
        <?php
        if (!empty($about['education'])) {
            $education_items = explode("\n", trim($about['education']));
            foreach ($education_items as $item) {
                if (!empty(trim($item))) {
                    echo '<div class="education-item">' . htmlspecialchars(trim($item)) . '</div>';
                }
            }
        }
        ?>
    </div>
</section>