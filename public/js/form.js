document.addEventListener('submit', event => {
    event.preventDefault();
    const form = document.getElementsByTagName('form')[0];

    const formData = new FormData(form);

    sendData(formData).then( response => (response ? resetForm(form) : null) ).catch(console.log());

});