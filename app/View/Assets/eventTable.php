<div class="table">
    <table>
        <thead>
            <tr class="header">
                <th scope="col">Tipo</th>
                <th scope="col">Nome</th>
                <th scope="col">Obrigatório</th>
                <th scope="col">Local</th>
                <th scope="col">Data</th>
                <th scope="col">Descrição</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($modelObject->count())) : ?>
                <tr>
                    <td colspan="8">
                        <p class="icon-sad">Não há eventos cadastrados</p>
                    </td>
                </tr>
                <?php else :
                foreach (($modelObject->all($limit, $offset) ?? []) as $event) : ?>
                    <tr>
                        <td data-label="Tipo"><?= $event->type; ?></td>
                        <td data-label="Nome"><?= $event->name; ?></td>
                        <td data-label="Obrigatório" class="tblMand"><?= $event->mandatory; ?></td>
                        <td data-label="Local"><?= $event->local; ?></td>
                        <td data-label="Data"><?= date_frmt($event->date); ?></td>
                        <td data-label="Descrição" class="tblDescription">
                            <div class="description-container">
                                <?= str_limit_chars($event->description, 25); ?>
                            </div>
                        </td>
                        <td data-label="Ação" class="action">
                            <button class="btn edit <?= $event->id; ?>">Editar</button>
                            <button class="btn exclude <?= $event->id; ?>">Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>