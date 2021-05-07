<div class="table">
    <table>
        <thead>
            <tr class="header">
                <th scope="col">Evento</th>
                <th scope="col">Membro</th>
                <th scope="col">Presença</th>
                <th scope="col">Solicitação</th>
                <th class="icon-clock2" scope="col">Data da Solicitação</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($modelObject->count())) : ?>
                <tr>
                    <td data-label="Sem Eventos" colspan="6">
                        <p class="icon-sad">Não há eventos cadastrados</p>
                    </td>
                </tr>
                <?php else :
                foreach ($modelObject->all($limit, $offset) as $att) : ?>
                    <tr>
                        <td data-label="Evento"><?= event()->findById($att->event_id)->name; ?></td>
                        <td data-label="R.A"><?= user()->findById($att->user_id)->name; ?></td>
                        <?php if ($att->attendance == 1) : ?>
                            <td data-label="Presença">Presente</td>
                        <?php elseif ($att->attendance == 0) : ?>
                            <td data-label="Presença">Falta</td>
                        <?php else : ?>
                            <td data-label="Presença"></td>
                        <?php endif; ?>

                        <?php if ($att->requested == 1) : ?>
                            <td data-label="Solicitação">Solicitado</td>
                            <td class="icon-alarm" data-label="Data De Solicitação"><?= date_frmt($att->requested_at); ?></td>
                        <?php elseif ($att->requested == 0) : ?>
                            <td data-label="Solicitação">Não Solicitado</td>
                            <td data-label="Data De Solicitação">Não Solicitado</td>
                        <?php else : ?>
                            <td data-label="Solicitação"></td>
                            <td data-label="Data De Solicitação">Não Solicitado</td>
                        <?php endif; ?>

                        <td data-label="Ação" class="action">
                            <button class="btn edit <?= $att->id; ?>">Editar</button>
                        </td>
                    </tr>
            <?php endforeach;
            endif;
            ?>
        </tbody>
    </table>
</div>

