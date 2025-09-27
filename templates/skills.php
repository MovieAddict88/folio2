<!-- Skills Section -->
<section id="skills" class="section">
    <h2>Skills</h2>
    <div class="skills-container">
        <div class="skills-category">
            <h3>Soft Skills</h3>
            <ul class="skills-list">
                <?php foreach ($skills as $skill): ?>
                    <?php if ($skill['category'] === 'Soft'): ?>
                        <li class="skill-item"><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="skills-category">
            <h3>Hard Skills</h3>
            <ul class="skills-list">
                <?php foreach ($skills as $skill): ?>
                    <?php if ($skill['category'] === 'Hard'): ?>
                        <li class="skill-item"><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!-- Animated radar chart can be added here later -->
</section>