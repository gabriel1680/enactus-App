<?php $this->layout('_theme', ['pageTitle' => $pageTitle]);
?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/recovery.css"); ?>">

<?php $this->stop(); ?>

<div class="recovery-container">
    <div class="recovery-content">
        <div class="recovery-title">
            <div class="recovery-header">
                <h3 class="icon-lock2">Recuperação de Senha</h3>
            </div>
            <div class="recovery-body">
                <p>Insira sua nova senha</p>
            </div>
        </div>
        <div class="recovery-form-container">
            <form method="POST" action="<?= $formPostRoute; ?>">
                <label for="iNewPassword">Email registrado para recuperação</label>
                <input type="password" name="password" id="iNewPassword" placeholder="Insira sua nova senha" required>
                <label for="iConfPassword">Email registrado para recuperação</label>
                <input type="password" name="confPassword" id="iConfPassword" placeholder="Confirme sua nova senha" required>
                <button type="submit">Confirmar</button>
            </form>
        </div>
    </div>
</div>

<?php $this->start('js'); ?>

    <script src="<?= theme("/js/form.js"); ?>"></script>

<?php $this->stop(); ?>