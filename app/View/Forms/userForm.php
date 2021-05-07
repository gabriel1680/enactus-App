<?php $this->layout('_theme', [
    'pageTitle' => $pageTitle,
    'sidebar' => $sidebar
]); ?>

<?php $this->start('css'); ?>

    <link rel="stylesheet" href="<?= theme("/style/style-event.css"); ?>">

<?php $this->stop(); ?>

<form method="POST" action="<?= $formPostRoute; ?>">
    <input type="hidden" name="id" value="<?= (!isset($user) ? null : $user->id) ?>">
    <h2><?= $formTitle ?></h2>
    <label for="iName">Nome</label>
    <input type="text" name="name" id="iName" placeholder="Digite o nome e sobrenome" required="required" value="<?= !isset($user) ?  null : $user->name; ?>">
    <label for="iPost">Cargo</label>
    <select name="office" id="iPost">
        <?php

        $offices = ["membro", ALLOWED_OFFICE];

        if (!isset($user)) :
        ?>
            <option selected disabled label="-- Selecione uma Opção --"></option>
            <?php foreach ($offices as $office) : ?>
                <option value="<?= $office ?>"><?= $office ?></option>
            <?php endforeach; ?>

            <?php else :
            foreach ($offices as $office) :
                if ($office == $user->office) : ?>
                    <option selected style="font-weight: bolder;" value="<?= $office; ?>"><?= $office; ?></option>
                <?php else : ?>
                    <option value="<?= $office; ?>"><?= $office; ?></option>
        <?php
                endif;
            endforeach;
        endif; ?>
    </select>
    <label for="iEmail">Email</label>
    <input <?= (!isset($user) ? null : 'readonly') ?> style="background:<?= (isset($user)) ? "gray" : null; ?>;" type="text" name="email" id="iEmail" placeholder="Insira o Email da mauá" required="required" maxlength="100" value="<?= !isset($user) ? null : $user->email; ?>">
    <p><?= !isset($user) ? "Inrsia o Email da mauá" : "Esse campo não pode ser alterado"; ?></p>
    <button type="submit" id="iSubmit">Confirmar</button>
</form>

<?php $this->start('js'); ?>

    <script src="<?= theme("/js/form.js"); ?>"></script>

<?php $this->stop(); ?>