<?php
/**
 * @var League\Plates\Template\Template $this
 * @var App\Exceptions\AppException $error
 */
?>
<?php $this->layout("Layouts/error"); ?> 
<h1>An Exception Occurred</h1>
<p><?= $error->getMessage() ?></p>