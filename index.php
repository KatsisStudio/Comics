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

    $pages = array_filter(glob("comics/" . $id . "/pages/*"), 'is_file');    
    $metadata["page_format"] = pathinfo($pages[0])["extension"];
    $metadata["page_count"] = count($pages);
    
    $thumbnailExtension = pathinfo($metadata["preview"])["extension"];
    $metadata["preview_small"] = "comics/" . $id . "/previews/thumbnail." . $thumbnailExtension;

    return [
        "id" => basename($path),
        "metadata" => $metadata
    ];
}

if (isset($_GET["comic"]))
{
    $metadata = parseMetadata("comics/". $_GET["comic"])["metadata"];
    if (!isset($_GET["page"]))
    {
        echo $twig->render("overview.html.twig", [
            "metadata" => $metadata,
            "comic" => $_GET["comic"],
            "css" => "overview"
        ]);
    }
    else
    {
        echo $twig->render("page.html.twig", [
            "metadata" => $metadata,
            "comic" => $_GET["comic"],
            "page" => $_GET["page"],
            "css" => "page"
        ]);
    }
}
else
{
    $comics = glob("comics/*", GLOB_ONLYDIR);
    echo $twig->render("index.html.twig", [
        "metadata" => null,
        "comics" => array_map("parseMetadata", $comics),
        "css" => "index"
    ]);
}