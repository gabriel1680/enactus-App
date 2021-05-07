<?php
$this->layout('_theme', [
    'pageTitle' => $pageTitle,
    'sidebar' => $sidebar,
    'background' => $background
]); ?>

<?php $this->start('css'); ?>

    <link rel="stylesheet" href="<?= theme("/style/home.css"); ?>">

<?php $this->stop(); ?>

<div class="container">
    <div class="content">

        <?php if ($eventList) : ?>

            <?php foreach ($eventList as $event) : ?>

                <?php
                $dateTime = explode(" ", $event->date);
                $event->date = $dateTime[0];
                $event->time = $dateTime[1];

                $currentAttendance = $attendanceObject->findAttendance($event->id, $userId);
                $attendanceStatus = $currentAttendance->attendance;
                $requestedStatus = $currentAttendance->requested;

                ?>

                <div class="box closed">
                    <div class="fullcard">
                        <div class="type-circle <?= $event->type; ?>"></div>
                        <div class="resume-title">
                            <h2><?= $event->name; ?></h2>
                        </div>
                        <div class="resume-info">
                            <p>Local: <?= $event->local; ?></p>
                            <p>Data: <?= date_frmt($event->date, "d/m/Y"); ?></p>
                            <p>Horário: <?= $event->time; ?></p>
                        </div>
                        <div class="discription">
                            <div class="type-content">
                                <h4>Tipo: </h4>
                                <p><?= $event->type; ?></p>
                            </div>
                            <div class="mandatory-content">
                                <h4>Obrigatório: </h4>
                                <p><?= $event->mandatory; ?></p>
                            </div>
                            <h3>Descrição:</h3>
                            <p><?= $event->description; ?></p>
                        </div>
                        <div class="check <?= (!$attendanceStatus ? "not" : null); ?>">
                            <img src="<?= MAIN_URL . "/public/img/selo/logo_selo-removebg-preview.png"; ?>" alt="presente">
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class=" empty">
                <h2>Não há eventos cadastrados no momento =(</h2>
                <br />
                <p>Cadastre um evento ou peça para a equipe de Gestão de Talentos</p>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php $this->start('js'); ?>

    <script>

        changeUserBackground();

        document.addEventListener('click', (event) => {

            const eventPath = event.composedPath();

            for (const eventNode of eventPath) {

                if (eventNode.classList && eventNode.classList.contains('fullcard')) {

                    const fullCard = eventNode;

                    openFullCard(fullCard);
                    return;

                } else {
                    continue;
                }
            }
        }, false);

    </script>

<?php $this->stop(); ?>