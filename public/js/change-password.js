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