let page;
let img;
let formats;

function updatePage(pageIndex)
{
    page.dataset.page = pageIndex;
    img.src = `/comics/${page.dataset.comic}/pages/${pageIndex}.${formats[pageIndex - 1]}`;
    img.alt = `Page ${pageIndex}`;
    window.history.pushState({}, page.dataset.name, `/${page.dataset.comic}/${pageIndex}`);
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

document.onreadystatechange = function () {
    if (document.readyState == "interactive") {
        page = document.getElementById("page");

        document.getElementById("page-count").innerHTML = page.dataset.count;
        formats = page.dataset.formats.split(";");

        img = page.querySelector("img");
        img.addEventListener("click", (e) => {
            img.removeAttribute("src");
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
    }
};

window.addEventListener("keyup", e => {
    if (e.key == "ArrowLeft") {
        previousPage();
    }
    else if (e.key == "ArrowRight") {
        nextPage();
    }
});

window.addEventListener("popstate", _ => {
    const pageParam = window.location.pathname.split("/").filter((e) => e)[1];

    page.dataset.page = pageParam;
    img.src = `/comics/${page.dataset.comic}/pages/${pageParam}.${formats[pageParam - 1]}`;
    img.alt = `Page ${pageParam}`;
    document.getElementById("page-current").innerHTML = pageParam;
});