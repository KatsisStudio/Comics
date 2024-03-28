let page;
let img;

function previousPage()
{
    const index = parseInt(page.dataset.page);
    page.dataset.page = index - 1;
    img.src = `comics/${page.dataset.comic}/pages/${index - 1}.png`;
    window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${index - 1}`);
}

function nextPage()
{
    const index = parseInt(page.dataset.page);
    page.dataset.page = index + 1;
    img.src = `comics/${page.dataset.comic}/pages/${index + 1}.png`;
    window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${index + 1}`);
}

window.addEventListener("load", _ => {
    page = document.getElementById("page");
    img = page.querySelector("img");
    page.addEventListener("click", (e) => {
        var rect = e.target.getBoundingClientRect();
        var x = e.clientX - rect.left;

        const index = parseInt(page.dataset.page);

        if (x < rect.width && index > 1)
        {
            previousPage();
        }
        else if (x > rect.width / 2 && index < parseInt(page.dataset.count))
        {
            nextPage();
        }
    });
});

window.addEventListener("keyup", e => {
    if (e.key == "ArrowLeft") {
        const index = parseInt(page.dataset.page);
        if (index > 1) {
            previousPage();
        }
    }
    else if (e.key == "ArrowRight") {
        const index = parseInt(page.dataset.page);
        if (index < parseInt(page.dataset.count)) {
            nextPage();
        }
    }
});

window.addEventListener("popstate", _ => {
    const urlParams = new URLSearchParams(window.location.search);
    const pageParam = urlParams.get('page');

    page.dataset.page = pageParam;
    img.src = `comics/${page.dataset.comic}/pages/${pageParam}.png`;
});