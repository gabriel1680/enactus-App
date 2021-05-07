<?php $this->layout('_theme', [
        'pageTitle' => $pageTitle,
    'sidebar' => $sidebar
    ]); ?>

<?php $this->start('css'); ?>

<link rel="stylesheet" href="<?= theme("/style/style-event.css"); ?>">

<?php $this->stop(); ?>

<form method="POST" action="<?= $formPostRoute ?>">
    <input type="hidden" name="option" value="change-password">
    <h2>Alterar Senha</h2>
    <label for="iPassword">Nova Senha</label>
    <input type="password" name="nPassord" id="iPassword" placeholder="Insira a nova senha" required="required">
    <label for="iConfPassword">Confirme a Senha</label>
    <input type="password" name="nConfPassword" id="iConfPassword" placeholder="Confirme sua senha" required="required">
    <p id="txt"></p>
    <button type="submit" id="iSubmit">Salvar</button>
</form>

<?php $this->start('js'); ?>

<script>
    const newPassword=document.getElementById('iConfPassword');
    const password=document.getElementById('iPassword');
    const txt=document.getElementById('txt');
    const btn=document.getElementById('iSubmit');

    newPassword.addEventListener('keyup', function(){
        if(this.value==password.value){
            txt.textContent='';
        }else{
            txt.textContent='As senhas não coincidem !';
        }
    });

    btn.addEventListener('click',function(e){
        if ( password.value != newPassword.value ) {
            e.preventDefault();
            window.alert("As senhas não coincidem !");
        }

    });
</script>

<?php $this->stop(); ?>
