<?php $this->layout('_theme', [
    'pageTitle' => $pageTitle,
    'sidebar' => $sidebar
]); ?>

<?php $this->start('css'); ?>

    <link rel="stylesheet" href="<?= theme("/style/event-register.css"); ?>">

<?php $this->stop(); ?>

<form method="POST" action="<?= $formPostRoute; ?>">
    <h2><?= $formTitle ?></h2>
    <input type="hidden" name="id" value="<?= (!isset($event) ? null : $event->id); ?>">
    <label for="iType">Tipo do Evento</label>
    <select name="type" id="iType" required="required">

        <?php if (!isset($event)) :
            $typeList = ["TIME", "PROJETO", "CAPACITAÇÃO", "REDE", "EXTRAS"];
        ?>

            <option selected disabled label="-- Selecione uma Opção --"></option>

            <?php foreach ($typeList as $type) : ?>
                <option value="<?= $type; ?>"><?= $type; ?></option>
            <?php endforeach; ?>

            <?php else :
            $typeList = ["TIME", "PROJETO", "CAPACITAÇÃO", "REDE", "EXTRAS"];
            foreach ($typeList as $type) :

                if ($type == $event->type) : ?>
                    <option selected style="font-weight: bolder;" value="<?= $type; ?>"><?= $type; ?></option>
                <?php else : ?>
                    <option value="<?= $type; ?>"><?= $type; ?></option>
        <?php
                endif;
            endforeach;
        endif; ?>
    </select>
    <label for="iMand">Obrigatóriedade</label>
    <select name="mandatory" id="iMand" required="required">
        <?php if (!isset($event)) : ?>
            <option selected disabled label="-- Selecione uma Opção --"></option>
            <option value="sim">Obrigatório</option>
            <option value="não">Não Obrigatório</option>
        <?php else : ?>
            <?php if ($event->mandatory == "sim") : ?>
                <option style="font-weight: bolder;" selected value="sim">Obrigatório</option>
                <option value="não">Não Obrigatório</option>
            <?php elseif ($event->mandatory == "não") : ?>
                <option value="sim">Obrigatório</option>
                <option selected style="font-weight: bolder;" value="não">Não Obrigatório</option>
            <?php endif; ?>
        <?php endif; ?>
    </select>
    <label for="iEvent">Nome</label>
    <input <?= !isset($event) ? null : "readonly"; ?> style="background:<?= !isset($event)  ? null  : "gray"; ?>;" type="text" name="event" id="iEvent" placeholder="Digite o Nome do Evento" required="required" value="<?= !isset($event) ? null : $event->name; ?>">
    <label for="iLocal">Local</label>
    <input type="text" name="local" id="iLocal" placeholder="Digite o Local do Evento" required="required" value="<?= !isset($event) ? null : $event->local; ?>">
    <label for="iDateTime">Data e Hora</label>
    <?php

    if (isset($event)) :
        $date = $event->date;
        $dateTime = explode(" ", $date);
        $dateTime = $dateTime[0] . "T{$dateTime[1]}";
    else :
        $dateTime = null;
    endif;

    ?>
    <input type="datetime-local" name="datetime" id="iDateTime" placeholder="Digite a hora" required="required" value="<?= $dateTime ?>">
    <label for="iDescription">Descrição</label>
    <textarea type="text" name="description" id="iDescription" maxlength="150" placeholder="Insira a Descrição do Evento"><?= !isset($event) ? null : $event->description; ?></textarea>
    <button type="submit" id="iSubmit">Enviar</button>
</form>

<?php $this->start('js'); ?>

    <script src="<?= theme("/js/form.js"); ?>"></script>

<?php $this->stop(); ?>