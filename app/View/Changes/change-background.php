<?php $this->layout('_theme', [
    'pageTitle' => $pageTitle,
    'sidebar'=> $sidebar
]); ?>

<?php $this->start('css'); ?>

    <link rel="stylesheet" href="<?= theme("/style/style-event.css"); ?>">

<?php $this->stop(); ?>

<form method="POST" action="<?= $formPostRoute ?>">
    <input type="hidden" name="option" value="change-password">
    <h2>Alterar Plano de Fundo</h2>
    <label for="iBackground">Selecione um plano de fundo</label>
    <select name="background" id="iBackground">

        <option selected disabled label="-- Selecione uma Opção --"></option>

        <?php foreach ($imageFiles as $imageFile): ?>

            <option value="<?= $imageFile; ?>"><?= extensionRemove($imageFile); ?></option>

        <?php endforeach; ?>
    </select>
    <button type="submit" id="iSubmit">Salvar</button>
</form>

<?php $this->start('js'); ?>

    <script src="<?= SCRIPT_DIR; ?>form.js"></script>

<?php $this->stop(); ?>