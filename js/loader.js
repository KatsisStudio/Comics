window.addEventListener("load", _ => {
    const page = document.getElementById("page");
    const img = page.querySelector("img");
    page.addEventListener("click", (e) => {
        var rect = e.target.getBoundingClientRect();
        var x = e.clientX - rect.left;

        const index = parseInt(page.dataset.page);

        console.log(x > rect.width / 2);
        if (x > rect.width / 2 && index < parseInt(page.dataset.count))
        {
            page.dataset.page = index + 1;
            img.src = `comics/${page.dataset.comic}/pages/${index + 1}.png`;
            window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${index + 1}`);
        }
        else if (x < rect.width && index > 1)
        {
            page.dataset.page = index - 1;
            img.src = `comics/${page.dataset.comic}/pages/${index - 1}.png`;
            window.history.pushState({}, page.dataset.name, `?comic=${page.dataset.comic}&page=${index - 1}`);
        }
    });
});

window.addEventListener("popstate", _ => {
    const page = document.getElementById("page");
    const img = page.querySelector("img");

    const urlParams = new URLSearchParams(window.location.search);
    const pageParam = urlParams.get('page');

    page.dataset.page = pageParam;
    img.src = `comics/${page.dataset.comic}/pages/${pageParam}.png`;
});