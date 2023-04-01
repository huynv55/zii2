<?php
/**
 * @var League\Plates\Template\Template $this
 */
?>
<?php if(flash()->has('message')): ?>
<p class="text-info"><?= flash()->get('message')[0] ?? '' ?></p>
<?php endif; ?>
<?php if(flash()->has('success')): ?>
<p class="text-success"><?= flash()->get('success')[0] ?? '' ?></p>
<?php endif; ?>
<?php if(flash()->has('error')): ?>
<p class="text-error"><?= flash()->get('error')[0] ?? '' ?></p>
<?php endif; ?>