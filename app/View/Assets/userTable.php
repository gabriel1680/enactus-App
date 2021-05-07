<div class="table">
    <table>
        <thead>
            <tr class="header">
                <th scope="col">Email</th>
                <th scope="col">Nome</th>
                <th scope="col">Cargo</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($modelObject->count())) : ?>
                <tr>
                    <td colspan="4">
                        <p class="icon-sad">Não há usuários cadastrados</p>
                    </td>
                </tr>
                <?php else :
                foreach ($modelObject->all($limit, $offset) as $user) : ?>
                    <tr>
                        <td data-label="Email"><?= $user->email; ?></td>
                        <td data-label="Membro"><?= $user->name; ?></td>
                        <td data-label="Cargo"><?= $user->office; ?></td>
                        <td data-label="Ação" class="action">
                            <button class="btn edit <?= $user->id; ?>">Editar</button>
                            <button class="btn exclude <?= $user->id; ?>">Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
