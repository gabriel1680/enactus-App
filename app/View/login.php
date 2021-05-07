<?php $this->layout('_theme', ['pageTitle' => $pageTitle]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/login.css"); ?>">

<?php $this->stop(); ?>

<div class="container">
    <div class="loginbox">
        <div class="login-header">
            <h2>LOGIN</h2>
        </div>
        <form method="POST" action="<?= $formPostUri ?>">
            <input type="hidden" name="option" value="login">
            <p>Usuário</p>
            <input type="email" id="iUsername" name="nUsername" placeholder="Insira seu e-mail da Mauá" required="required" />
            <p>Senha</p>
            <input id="iPassword" name="nPassword" placeholder="Insira sua senha" type="password" required="required" />
            <a class="forgot-link" href="<?= url("/forgot"); ?>">
                <div class="forgot">Esqueci minha senha</div>
            </a>
            <button id="sAcessar" type="submit" />Acessar</button>
        </form>
    </div>
</div>
<footer>
    <div class="footer-container">
        <a href="<?= $aboutPath; ?>">Sobre EnactusPassport®</a>
    </div>
</footer>

<?php $this->start('js'); ?>

<script src="<?= theme("/js/form.js"); ?>"></script>

<?php $this->stop(); ?>
