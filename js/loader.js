let page;
let img;

function previousPage()
{
    const index = parseInt(page.dataset.page);

    if (index > 1)
    {
        page.dataset.page = index - 1;
        img.src = `comics/${page.dataset.comic}/pages/${index - 1}.png`;
        window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${index - 1}`);
    }
}

function nextPage()
{
    const index = parseInt(page.dataset.page);

    if (index < parseInt(page.dataset.count))
    {
        page.dataset.page = index + 1;
        img.src = `comics/${page.dataset.comic}/pages/${index + 1}.png`;
        window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${index + 1}`);
    }
}

function firstPage()
{
    page.dataset.page = 1;
    img.src = `comics/${page.dataset.comic}/pages/${1}.png`;
    window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${1}`);
}

function lastPage()
{
    const last = parseInt(page.dataset.count);

    page.dataset.page = last;
    img.src = `comics/${page.dataset.comic}/pages/${last}.png`;
    window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${last}`);
}

window.addEventListener("load", _ => {
    page = document.getElementById("page");
    img = page.querySelector("img");
    img.addEventListener("click", (e) => {
        var rect = e.target.getBoundingClientRect();
        var x = e.clientX - rect.left;

        if (x < rect.width / 2)
        {
            previousPage();
        }
        else if (x > rect.width / 2)
        {
            nextPage();
        }
    });
});

window.addEventListener("keyup", e => {
    if (e.key == "ArrowLeft") {
        previousPage();
    }
    else if (e.key == "ArrowRight") {
        nextPage();
    }
});

window.addEventListener("popstate", _ => {
    const urlParams = new URLSearchParams(window.location.search);
    const pageParam = urlParams.get('page');

    page.dataset.page = pageParam;
    img.src = `comics/${page.dataset.comic}/pages/${pageParam}.png`;
});