/**
 *************************  AJAX POST REQUISITION
 */

function sendData(data) {

    setLoadAnimationStatus('start');

    return  fetch(document.URL, {
        method: "POST",
        body: data
    })
        .then( response => response.json())
        .then(data => {
            // console.log(data);
            setLoadAnimationStatus('stop');

            if (data.url) {
                window.location = data.url;
                return;
            }

            const messageHTML = setMessage(data.type, data.message);
            insertMessageAtTheBody("beforeend", messageHTML);
            const popUpElement = document.querySelector('.trigger');
            setPopUpFadeOut(popUpElement, 5000);

            if (data.type.includes('error')) {
                // console.log('erro');
                throw new Error(data.message);
            }

            return true;
        });
}

/**
 ************************* FORM CLEANING
 */

function resetForm(formElement)
{
    for (let element of formElement.elements) {
        if (element.tagName === 'SELECT') {
            element.selctedIndex = -1;
        }
    }

    formElement.reset();
}

/**
 *************************  MESSAGE
 */

/**
 * 
 * @param {node} element 
 * @param {number} timeOut 
 */
function setPopUpFadeOut(element, timeOut = 5000) {
    if ( element ) {
        setTimeout( ( e ) => {
                        
            if ( element.classList.contains == 'none' ) {
                element.remove();
                return;
            }
            
            element.classList = 'trigger fadeout';
                            
            setTimeout(() => {
                element.remove();
                return;
            }, 500);
            
        }, timeOut );
    }
}

/**
 * 
 * @param {string} type 
 * @param {string} message 
 * @returns {string}
 */
function setMessage(type, message) {
    html = `<div class ="trigger ${type}">${message}
    <div class= "progress-bar" ></div>
    </div>`;
    return html;
}

/**
 * 
 * @param {string} where 
 * @param {string} messageHTML 
 * @returns {void}
 */
function insertMessageAtTheBody(where = "beforeend", messageHTML) {
    return document.body.insertAdjacentHTML(where, messageHTML);
}

/**
 *************************  LOADING ANIMATION
 */

/**
 * 
 * @param {string} status 
 * @returns {void}
 */
function setLoadAnimationStatus( status ) {
    const loadDiv = document.querySelector('.ajax_load');
    status = ( status == "start" ? "flex" : "none" );
    loadDiv.style.display = status;
    return;
}


/**
 *
 ************************* HOME BACKGROUND CHANGE
 *
 */
function changeUserBackground() {

    const backgroundElement = document.querySelector('.img-background');
    const backgroundData = backgroundElement.dataset.background;

    backgroundElement.style.backgroundImage = `url(${backgroundData})`;
    console.log(backgroundData);

}


/**
 *
 ************************* HOME CARDS ACTION
 *
 */

function alterClassList(element)
{
    const status=element.classList.value;
    if (status == 'box closed'){
        element.classList.remove('closed');
        element.classList.add('open');
    } else if(status == 'box open') {
        element.classList.remove('open');
        element.classList.add('closed');
    }
}

function openFullCard(fullcardElement){

    alterClassList(fullcardElement.parentElement);

    fullcardElement.classList.toggle('open');

    for (const elementChild of fullcardElement.childNodes) {
        if (elementChild.classList) {
            elementChild.classList.toggle('open');
        }
    }
}


/**
 *
 ************************* CRUD FUNCTIONS
 *
 */

function removeTableRowByButton(buttonElement) {
    const tableRowElement = buttonElement.parentElement.parentElement;
    tableRowElement.classList.add('fadeout');

    setInterval(() =>{
        tableRowElement.remove();
    }, 200);
}

