<?php
$this->layout('_theme', [
    'pageTitle' => $pageTitle,
    'sidebar' => $sidebar
]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/style-event.css"); ?>">

<?php $this->stop(); ?>

    <form action="<?= url("/home/passaportes"); ?>" method="POST">
        <h2>Passaportes</h2>
        <p style="margin-bottom: 10px; font-size: 1em;">selecione o membro que você deseja ver o passaporte</p>
        <label for="iSelectPassport">Membro</label>
        <select class="passports-combobox" name="userId" id="iSelectPassport" style="margin: 20px 0px;" required>
            <optgroup label="--Selecione um membro--"></optgroup>

            <?php foreach ($userObject->all() as $user): ?>
                <option value="<?= $user->id; ?>"><?= $user->name; ?></option>
            <?php endforeach; ?>

        </select>

        <button class="passports-btn" type="submit">Buscar</button>
    </form>

<?php $this->start('js'); ?>

<script>
    const sendButton = document.querySelector('.passports-btn');

    sendButton.addEventListener('click', e => {
        e.preventDefault();
        const userId = document.querySelector('.passports-combobox').value;

        const url = document.URL;

        console.log(url + '/' + userId);

        window.location = url + '/' + userId;

    });

</script>

<?php $this->stop(); ?>
