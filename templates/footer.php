</main> <!-- .main-panel -->

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($about['name'] ?? 'Portfolio'); ?>. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Lightbox Modal for Projects -->
    <div id="lightbox-modal" class="lightbox">
        <span class="close-btn">&times;</span>
        <div class="lightbox-content">
            <img id="lightbox-img" src="">
            <a class="prev-btn">&#10094;</a>
            <a class="next-btn">&#10095;</a>
        </div>
    </div>

    <script src="public/new-script.js"></script>
</body>
</html>