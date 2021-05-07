<?php $this->layout('_theme', ['pageTitle' => $pageTitle]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/forgot.css"); ?>">

<?php $this->stop(); ?>

<div class="support-container">

    <div class="support-content">
        <div class="support-title">
            <div class="support-header">
                <h3 class="icon-lock2">Recuperação de Senha</h3>
            </div>
            <div class="support-body">
                <p>Será enviado um e-mail para a recuperação da sua senha</p><br />
                <p><strong>Obs:</strong> Caso não encontre o e-mail na sua caixa de entrada verifique no lixo eletrônico.</p><br />
                <p>Lembre-se: o link de recuperação será enviado para o seu e-mail da mauá e não para seu e-mail pessoal !</p>
            </div>
        </div>
        <div class="support-form-container">
            <form method="POST" action="<?= $formPostRoute; ?>">
                <label for="iEmail">Email registrado para recuperação</label>
                <input type="email" name="email" id="iEmail" placeholder="Insira seu email da mauá" required>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
</div>

<?php $this->start('js'); ?>

    <script src="<?= theme("/js/form.js"); ?>"></script>

<?php $this->stop(); ?>