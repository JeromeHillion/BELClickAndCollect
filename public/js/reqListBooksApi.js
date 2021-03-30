const controllerLocation = window.location.origin + '/admin/getBooks/'


async function getRequest() {
    let value = document.getElementById('search_book_name').value;
    const response = await fetch(controllerLocation + value);
    return response.json();
}

function fillTable(books) {
    let html = "";
    console.log(books);
    document.getElementById('cardSearch')
    for (let line of books.items) {
        html += "<tr><td class='col'>" +
            line.volumeInfo.title +
            "</td><td class='col'>" +
            line.volumeInfo.authors + "</td>" +
            "<td class='col-2'>" + line.volumeInfo.categories + "</td>"
            + "</td>" + "<td class='col-2'>" + line.volumeInfo.publishedDate + "</td>" +
            "<td class='col-2'><a href='/admin/getBook/" + line.id + "'" + "" + " class='btn btn-secondary me-2 btn-sm'>Voir</a><a href='#' class='btn btn-info text-light btn-sm'>Ajouter</a></td></tr>"

    }

    document.getElementById('tbody').innerHTML = html;

    if (html) {
        document.getElementById('tableData').classList.remove('d-none');
    }


}


document.addEventListener('DOMContentLoaded', () => {

    let btnSearch = document.getElementById('search_book_search');
    const loader = document.getElementById('loader');
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
