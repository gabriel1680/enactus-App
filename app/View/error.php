<?php $this->layout('_theme', ['pageTitle' => $pageTitle]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/erro.css"); ?>">

<?php $this->stop(); ?>

<div class="error-container">
    <div class="error-txt">
        <h1>Erro: <?= $errCode; ?></h1><br />
        <h2>Ops Algo deu errado !</h2>
        <p>Volte para o menu principal ou contate o administrador</p>
    </div>
</div>