<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="discription" content="Aplicativo de controle de prensença para membros da Enactus Mauá." />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="canonical" href="<?= url(""); ?>">

    <?= $this->section('css'); ?>

<!--    <link rel="stylesheet" href="--><?//= theme("/style/style-sheet.min.css"); ?><!--">-->
    <link rel="stylesheet" href="<?= theme("/style/menu-sidebar.css"); ?>">
    <link rel="stylesheet" href="<?= theme("/style/fonticon.css"); ?>">
    <link rel="stylesheet" href="<?= theme("/style/default.css"); ?>">

    <link rel="shortcut icon" type="image/x-icon" href="<?= theme("/img/logo.ico") ?>">

    <title>EnactusPassport | <?= $pageTitle; ?></title>

</head>

<body>

<nav class="nav-bar">
    <div class="nav-logo">
        <a href="<?= url(""); ?>"><img src="<?= theme("/img/EnactusMaua.jpeg"); ?>" alt="EnactusPassport"></a>
    </div>
    <div class="navigation">
        <ul>
            <li id="insta"><a href="https://www.instagram.com/enactusmaua/" target="_blank">instagram</a></li>
            <li id="fb"><a href="https://www.facebook.com/pg/enactusmaua/posts/" target="_blank">facebook</a></li>
            <li id="mauanet"><a href="https://maua.br/graduacao/atividades-eventos/enactus-maua" target="_blank">Sobre</a></li>
        </ul>
    </div>
</nav>

<div class="img-background" <?= (!isset($background) ? null : 'data-background= "'. theme("/img/background/") . $background.'"');?>></div>

<?= session()->flash(); ?>

<?php if (isset($sidebar)) :
    if ($sidebar == 'gt') : ?>
        <?= $this->insert('Shared::sidebar-gt'); ?>
    <?php else : ?>
        <?= $this->insert('Shared::sidebar-default'); ?>
    <?php endif; ?>
<?php endif; ?>

<?= $this->section('content'); ?>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <div class="ajax_load_box_title">Aguarde, carregando!</div>
    </div>
</div>

</body>

<script src="<?= theme("/js/functions.js"); ?>"></script>
<script src="<?= theme("/js/menu-sidebar.js"); ?>"></script>

<?= $this->section('js'); ?>

</html>