const controllerLocation = window.location.origin + '/users/books'

async function getBookInfo() {
    const response = await fetch(controllerLocation);
    return response.json();
}
function showModal() {
    let modal = document.querySelector('.modal');
    let header = document.querySelector('header')
    let navSecondary = document.querySelector('.navSecondary')
    let h2 = document.querySelector('h2')
    let books = document.querySelector('.books')
    modal.style.display = 'block';
    header.style.display = "none";
    books.style.display = "none";
    navSecondary.style.display = "none";
    h2.style.display = "none";
}

document.addEventListener('DOMContentLoaded', () => {
    let btnModal = document.querySelector('.viewBook');
    console.log(btnModal)

    btnModal.addEventListener('click', () => {
        showModal();

        getBookInfo().then(response => {
            console.log(response);
        }).catch(function () {
            console.error('Livre non trouvé !')
        })
    });

});