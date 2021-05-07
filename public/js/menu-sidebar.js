    
const menuIcon=document.querySelector('.menu-icon');

if(menuIcon!=null){
    menuIcon.addEventListener('click',(e)=>{
        document.querySelector('.sidebar').classList.toggle('show-menu');
    });
}

setPopUpFadeOut(document.querySelector('.trigger'), 5000);

document.addEventListener('click', (event) => {

    const eventElement = event.target;

    if ( eventElement.classList.contains('trigger')) {
        eventElement.remove();
    }
});

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


