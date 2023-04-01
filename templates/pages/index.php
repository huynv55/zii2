<?php
/**
 * @var League\Plates\Template\Template $this
 */
$form = $this->form();
/**
 * @var App\Responses\HtmlExtensions\FormHelper $form
 */
 
$this->layout('../layouts/main', $this->data());
?>
<h1>Home page</h1>
<?= $form->open(['action' => '/login', 'class' => 'form-login', 'method' => 'post']) ?>
<?= $form->input('username', 'text', ['label' => 'Username']) ?>
<?= $form->input('password', 'password', ['label' => 'Password']) ?>
<?= $form->button('Submit', ['type' => 'submit']) ?>
<?= $form->close() ?>