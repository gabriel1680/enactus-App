
/**
 *  JAVASCRIPT FILE FOR CRUD EDIT AND EXCLUDE BUTTONS ACTION
 */

const excludeButtons = document.querySelectorAll( '.btn.exclude' );
const editButtons = document.querySelectorAll( '.btn.edit' );

const excludeButtonsArray = Array.from( excludeButtons );
const editButtonsArray = Array.from( editButtons );

const urlParse = document.URL.split( "/" );

const areYouSure = document.getElementById( 'areYouSure' );
const yes = document.getElementById( 'yes' );
const no = document.getElementById( 'no' );

const urlFinalIsANumber = !isNaN(urlParse[urlParse.length-1]);
const controllerName = (urlFinalIsANumber ? urlParse[urlParse.length - 2] : urlParse[urlParse.length - 1]);

let urlBase;

if (urlFinalIsANumber) {
    urlParse.pop();
    urlParse.pop();
    urlBase = urlParse.toLocaleString().replaceAll(',','/');
} else {
    urlParse.pop();
    urlBase = urlParse.toLocaleString().replaceAll(',','/');
}

no.addEventListener( 'click', (e) => {
    e.preventDefault();
    areYouSure.classList.remove( 'show' );
});

let id;
let buttonElement;

const yesButtonEvent = yes.addEventListener( 'click', event => {
    const data = new FormData();
    data.append('id', id);

    console.log(id, buttonElement);

    sendData(data).then( response => (response ? removeTableRowByButton(buttonElement) : null)).catch();

    areYouSure.classList.remove( 'show' );
});

excludeButtonsArray.forEach(button => {
    button.addEventListener( 'click', ( e ) => {

        e.preventDefault();

        id = button.className.split( " " )[ 2 ];

        areYouSure.classList.add( 'show' );

        no.addEventListener( 'click', (e) => {
            e.preventDefault();
            areYouSure.classList.remove( 'show' );
        });

        console.log(urlBase + '/excluir/' + id);

        buttonElement = button;

    });
});


editButtonsArray.forEach(button => {
    button.addEventListener( 'click', ( e ) => {

        e.preventDefault();

        const id = button.className.split( " " )[ 2 ];

        console.log(urlBase  + '/' + controllerName + '/editar/' + id);

        // console.log(urlParse[urlParse.length -1]);
        // console.log(urlParse);
        // console.log(urlBase);
        // console.log(controllerName);

        window.location.href = urlBase  + '/' + controllerName + '/editar/' + id;
    });
});