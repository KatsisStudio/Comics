<?php

require_once "vendor/autoload.php";

use Twig\Loader\FilesystemLoader;
use Twig\Environment;


$loader = new FilesystemLoader(["templates"]);
$twig = new Environment($loader);

function parseMetadata($path) {
    $id = basename($path);
    $metadata = json_decode(file_get_contents("$path/info.json"), true);
    $metadata["preview"] = "comics/" . $id . "/assets/" . $metadata["preview"];

    return [
        "id" => basename($path),
        "metadata" => $metadata
    ];
}

if (isset($_GET["comic"]))
{
    $metadata = json_decode(file_get_contents("comics/". $_GET["comic"] . "/info.json"), true);
    $metadata["preview"] = "comics/" . $_GET["comic"] . "/assets/" . $metadata["preview"];

    if (!isset($_GET["page"]))
    {
        echo $twig->render("redirect.html.twig", [
            "metadata" => $metadata,
            "target" => 'https://comic.katsis.net?comic=' . $_GET["comic"] . '&page=1'
        ]);
    }
    else
    {
        $pages = array_filter(glob("comics/" . $_GET["comic"] . "/pages/*"), 'is_file');    
        $metadata["page_count"] = count($pages);
        echo $twig->render("page.html.twig", [
            "metadata" => $metadata,
            "comic" => $_GET["comic"],
            "page" => $_GET["page"]
        ]);
    }
}
else
{
    $comics = glob("comics/*", GLOB_ONLYDIR);
    echo $twig->render("index.html.twig", [
        "metadata" => null,
        "comics" => array_map("parseMetadata", $comics)
    ]);
}