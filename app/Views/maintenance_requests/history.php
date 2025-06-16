<?php
// File: app/views/maintenance_requests/history.php
?>
<div class="card mt-4 p-3">
    <h5><?= __('Request History') ?></h5>
    <?php if (!empty($history)): ?>
        <ul class="list-group">
            <?php foreach ($history as $log): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start flex-column flex-sm-row">
                    <div class="me-auto">
                        <strong><?= date('d M Y H:i', strtotime($log['timestamp'])) ?></strong><br>
                        <?= esc($log['action']) ?>
                    </div>
                    <span class="badge bg-primary mt-2 mt-sm-0"><?= esc($log['performed_by']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted"><?= __('No history available for this request.') ?></p>
    <?php endif; ?>
</div>
