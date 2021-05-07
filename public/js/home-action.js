// /*
// *
// *  MECÂNICA DE TROCA DE PLANO DE FUNDO DE ACORDO COM CADA DATA
// *
// */
//
// const backgroundElement = document.querySelector('.img-background');
// const backgroundData = backgroundElement.dataset.background;
//
// backgroundElement.style.backgroundImage = `url(${backgroundData})`;
// console.log(backgroundData);
//
// /*
// *
// *  ANIMAÇÃO DOS CARDS E MECÂNICA DE REQUISIÇÃO
// *
// */
// function alterClassList(element)
// {
//     const status=element.classList.value;
//     if (status == 'box closed'){
//         element.classList.remove('closed');
//         element.classList.add('open');
//     } else if(status == 'box open') {
//         element.classList.remove('open');
//         element.classList.add('closed');
//     }
// }
//
// function openFullCard(fullcardElement){
//
//     alterClassList(fullcardElement.parentElement);
//
//     fullcardElement.classList.toggle('open');
//
//     for (const elementChild of fullcardElement.childNodes) {
//         if (elementChild.classList) {
//             elementChild.classList.toggle('open');
//         }
//     }
// }
//
// function setLoadAnimationStatus( status ) {
//     const loadDiv = document.getElementsByClassName('ajax_load')[0];
//     status = ( status == "start" ? "flex" : "none" );
//     loadDiv.style.display = status;
//     return;
// }
//
// function setMessage(type, message) {
//     html = `<div class ="trigger ${type}">${message}
//                 <div class= "progress-bar" ></div>
//             </div>`;
//     return html;
// }
//
// function insertMessageAtTheBody(where = "beforeend", messageHTML) {
//     return document.body.insertAdjacentHTML(where, messageHTML);
// }
//
// document.addEventListener('click', (event) => {
//
//     const eventPath = event.composedPath();
//
//     //for of permite utilizar o break statement
//     for (const eventNode of eventPath) {
//
//         if (eventNode.classList && eventNode.classList.contains('fullcard')) {
//
//             const fullCard = eventNode;
//
//             openFullCard(fullCard);
//             return;
//
//         } else if (eventNode.classList && eventNode.className == 'requested not') {
//
//             const buttonRequestElement = eventNode;
//
//             const eventId = buttonRequestElement.dataset.eventid;
//
//             setLoadAnimationStatus('start');
//
//             fetch(document.URL, {
//                 method: "POST",
//                 body: JSON.stringify({
//                     id: eventId
//                 })
//             })
//             .then( response => response.json())
//             .then(data => {
//                 console.log(data);
//                 setLoadAnimationStatus('stop');
//
//                 const messageHTML = setMessage(data.type, data.message);
//                 insertMessageAtTheBody("beforeend", messageHTML);
//                 const popUpElement = document.querySelector('.trigger');
//                 setPopUpFadeOut(popUpElement, 5000);
//
//                 if (!data.type.includes('error')) {
//                     buttonRequestElement.innerHTML = 'Solicitado !';
//                     buttonRequestElement.classList.remove('not');
//                 }
//             });
//             break;
//         }else {
//             continue;
//         }
//     }
// // false indica que o evento está em fase de propagação.
// }, false);


changeUserBackground();

document.addEventListener('click', (event) => {

    const eventPath = event.composedPath();

    //for of permite utilizar o break statement
    for (const eventNode of eventPath) {

        if (eventNode.classList && eventNode.classList.contains('fullcard')) {

            const fullCard = eventNode;

            openFullCard(fullCard);
            return;

        } else if (eventNode.classList && eventNode.className == 'requested not') {

            const buttonRequestElement = eventNode;

            const eventId = buttonRequestElement.dataset.eventid;

            setLoadAnimationStatus('start');

            const formData = new FormData();
            formData.append('id', eventId);

            fetch(document.URL, {
                method: "POST",
                // body: JSON.stringify({ id: eventId })
                body: formData
            }).then( response => response.json()).then(data => {
                console.log(data);
                setLoadAnimationStatus('stop');

                const messageHTML = setMessage(data.type, data.message);
                insertMessageAtTheBody("beforeend", messageHTML);

                const popUpElement = document.querySelector('.trigger');
                setPopUpFadeOut(popUpElement, 5000);

                if (!data.type.includes('error')) {
                    const boxElement = buttonRequestElement.parentElement.parentElement.parentElement.parentElement;
                    boxElement.style = '';
                    buttonRequestElement.innerHTML = 'Solicitado !';
                    buttonRequestElement.classList.remove('not');
                }

            });
            break;
        }
    }
// false indica que o evento está em fase de propagação.
}, false);


