<?php $this->layout('_theme', ['pageTitle' => $pageTitle]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/about.css"); ?>">

<?php $this->stop(); ?>

<div class="about-container">
    <div class="about-content">
        <div class="header-container">
            <header>
                <div class="header-content">
                    <h1>EnactusPassport</h1>
                    <h2>Gestão de Presenças</h2>
                </div>
            </header>
        </div>
        <div class="content">
            <section class="criation-contaier">
                <article class="criation-article-content">
                    <header>
                        <div class="header-criation-content">
                            <h3>Idealização</h3>
                        </div>
                    </header>
                    <div class="criation-content">
                        <p>
                            Idealizado e elaborado por Gabriel Pedersoli Lopes em colaboração com Victória Beatriz Dantas Moreira,
                            para Enactus Mauá do Instituto Mauá de Tecnologia com o intuito de melhor gerir e controlar a presença dos membros em enventos.
                        </p>
                    </div>
                </article>
            </section>
        </div>
    </div>
</div>