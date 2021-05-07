<?php $this->layout('_theme', [
    'pageTitle' => $pageTitle,
    'sidebar' => $sidebar
]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/style-event.css"); ?>">

<?php $this->stop(); ?>

<form method="POST" action="<?= $formPostRoute ?>">
    <h2><?= $formTitle ?></h2>
    <label for="iEvent">Evento</label>
    <select name="eventId" id="iEvent" required>
        <?php if ($formTitle == "Alterar") : ?>

            <option selected disabled label="-- Selecione uma Opção --"></option>
            <?php
            foreach (event()->all() as $event) : ?>
                <option value="<?= $event->id; ?>"><?= $event->name; ?></option>
            <?php endforeach; ?>
        <?php else : ?>
            <option value="<?= $event->id; ?>"><?= $event->name; ?></option>
        <?php endif; ?>
    </select>
    <label for="iName">Membro</label>
    <select name="userId" id="iName" required>
        <?php if ($formTitle == "Alterar") : ?>

            <option selected disabled label="-- Selecione uma Opção --"></option>

            <?php foreach (user()->all() as $user) : ?>

                <option value="<?= $user->id; ?>"><?= $user->name; ?></option>

            <?php endforeach; ?>

        <?php else : ?>

            <option value="<?= $user->id; ?>"><?= $user->name; ?></option>

        <?php endif; ?>

    </select>
    <p class="rotulo">Presença</p>

    <?php if ($formTitle == "Alterar") : ?>
        <input type="radio" value="1" name="attendance" id="iPres">
        Presente
        <input type="radio" value="0" name="attendance" id="iPres">
        Falta
    <?php else : ?>
        <?php if ($attendance == 1) : ?>
            <input checked type="radio" value="1" name="attendance" id="iPres">
            Presente
            <input type="radio" value="0" name="attendance" id="iPres">
            Falta
        <?php else : ?>
            <input type="radio" value="1" name="attendance" id="iPres">
            Presente
            <input checked type="radio" value="0" name="attendance" id="iPres">
            Falta
        <?php endif; ?>
    <?php endif; ?>
    
    <button type="submit" id="iSubmit">Enviar</button>
</form>


<?php $this->start('js'); ?>

<script src="<?= theme("/js/form.js"); ?>"></script>

<?php $this->stop(); ?>
