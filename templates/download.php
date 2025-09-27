<!-- Download Center Section -->
<section id="download" class="section">
    <h2>Download Center</h2>
    <p>Access protected documents, such as my resume, by entering a password.</p>
    <div class="download-items">
        <?php if (!empty($documents)): ?>
            <?php foreach ($documents as $doc): ?>
                <div class="download-item">
                    <span><?php echo htmlspecialchars($doc['file_name']); ?></span>
                    <a href="download.php?file_id=<?php echo $doc['id']; ?>" class="download-link">Go to Download</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No documents are available for download at this time.</p>
        <?php endif; ?>
    </div>
</section>