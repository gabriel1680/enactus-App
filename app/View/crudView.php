<?php $this->layout('_theme', [
    'pageTitle' => $pageTitle,
    'sidebar' => $sidebar
]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/crud.css"); ?>">

<?php $this->stop(); ?>

<div class="table-content">
    <div class="title">
        <h2 class="title-txt"><?= $crudTitle; ?></h2>
    </div>

    <?= session()->flash(); ?>

    <div class="tbl">
        <div class="header-tbl">
            <div class="header-tbl-txt">Management</div>
            <div class="header-tbl-button">
                <a class="a-btn" href="<?= $addNewUrlPath; ?>">
                    <button class="click"><?= $addButtonText; ?></button>
                </a>
            </div>
        </div>

        <?php $this->insert('Assets::' . $tableName, ['modelObject' => $modelObject, 'limit' => $limit, 'offset' => $offset]); ?>

    </div>
</div>

    <?= $pager->render(); ?>

    <?php $this->insert('Shared::areYouSure'); ?>

<?php $this->start('js'); ?>

<script src="<?= theme("/js/crud.js"); ?>"></script>

<?php $this->stop(); ?>
