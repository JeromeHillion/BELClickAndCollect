const controllerLocation = window.location.origin + '/admin/getBooks/'



async function getRequest() {
    let value = document.getElementById('search_book_name').value;
    const response = await fetch(controllerLocation+value);
    return response.json();
}

function fillTable(books) {
    let html = "";
    console.log(books);
    for (let line of books.items) {
        /*const linkBook ='/admin/getBooks/book/'+line.id*/
        html += "<tr><td>" +
            line.volumeInfo.title +
            "</td><td>" +
            line.volumeInfo.authors +
            '</td><td><a href="/admin/getBook/'+
            line.id+
            '"  class="btn btn-secondary me-2">Voir</a><a href="#" class="btn btn-info text-light">Ajouter</a></td></tr>'

        /*document.getElementById('book').setAttribute("href", "admin/getBooks/book"+line.id+"");*/

    }

    document.getElementById('tbody').innerHTML = html;

    if (html) {
        document.getElementById("tableData").classList.remove("d-none")
    }

}

document.addEventListener('DOMContentLoaded', () => {

    let btnSearch = document.getElementById('search_book_search');
    const loader =document.getElementById('loader');
    btnSearch.addEventListener("click", function (event) {
        event.preventDefault();
        loader.classList.remove('visually-hidden');
        getRequest().then(response => {
            fillTable(response);
            loader.classList.add('visually-hidden');
        }).catch(function () {
            console.error('Livre non trouvé !')
            loader.classList.add('visually-hidden');
        })

    })
});
