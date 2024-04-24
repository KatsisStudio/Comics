let page;
let img;

function updatePage(pageIndex)
{
    page.dataset.page = pageIndex;
    img.src = `comics/${page.dataset.comic}/pages/${pageIndex}.${page.dataset.format}`;
    window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${pageIndex}`);
    document.getElementById("page-current").innerHTML = pageIndex;
}

function previousPage()
{
    const index = parseInt(page.dataset.page);

    if (index > 1)
    {
        updatePage(index - 1);
    }
}

function nextPage()
{
    const index = parseInt(page.dataset.page);

    if (index < parseInt(page.dataset.count))
    {
        updatePage(index + 1);
    }
}

function firstPage()
{
    updatePage(1);
}

function lastPage()
{
    const last = parseInt(page.dataset.count);

    updatePage(last);
}

window.addEventListener("load", _ => {
    page = document.getElementById("page");

    document.getElementById("page-count").innerHTML = page.dataset.count;

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
    img.src = `comics/${page.dataset.comic}/pages/${pageParam}.${page.dataset.format}`;
    document.getElementById("page-current").innerHTML = pageParam;
});