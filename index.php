<?php

require_once "vendor/autoload.php";

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(["templates"]);
$twig = new Environment($loader);

$urlData = array_filter(explode("/", substr(explode("?", $_SERVER["REQUEST_URI"])[0], 1)));
$comic = count($urlData) > 0 ? $urlData[0] : null;
$page = count($urlData) > 1 ? $urlData[1] : null;

$json = isset($_GET["json"]) && $_GET["json"] === "1";
if ($json) {
    header('Content-Type: application/json; charset=utf-8');
}

function parseMetadata($path) {
    $id = basename($path);
    $metadata = json_decode(file_get_contents("$path/info.json"), true);
    $metadata["preview"] = "comics/" . $id . "/assets/" . $metadata["preview"];

    $pages = array_filter(glob("comics/" . $id . "/pages/*"), 'is_file');
    $pagesInfo = array();
    foreach ($pages as $page)
    {
        $data = pathinfo($page);
        $pagesInfo[intval($data["filename"])] = $data;
    }
    ksort($pagesInfo);
    $lastPage = end($pagesInfo);
    $metadata["last_update"] = filemtime($lastPage["dirname"] . "/" . $lastPage["basename"]);
    $metadata["page_format"] = array_map(function(array $page): string { return $page["extension"]; }, $pagesInfo);
    $metadata["page_count"] = count($pagesInfo);
    
    $thumbnailExtension = pathinfo($metadata["preview"])["extension"];
    $metadata["preview_small"] = "comics/" . $id . "/previews/thumbnail." . $thumbnailExtension;

    return [
        "id" => basename($path),
        "metadata" => $metadata
    ];
}

if ($comic !== null)
{
    $metadata = parseMetadata("comics/". $comic)["metadata"];
    if ($page === null)
    {
        if ($json) {
            echo json_encode([
                "metadata" => $metadata,
                "comic" => $comic
            ]);
        } else {
            echo $twig->render("overview.html.twig", [
                "metadata" => $metadata,
                "comic" => $comic,
                "css" => "overview"
            ]);
        }
    }
    else
    {
        if ($json) {
            echo json_encode([
                "metadata" => $metadata,
                "comic" => $comic,
                "page" => $page
            ]);
        } else {
            echo $twig->render("page.html.twig", [
                "metadata" => $metadata,
                "comic" => $comic,
                "page" => $page,
                "css" => "page"
            ]);
        }
    }
}
else
{
    $comics = glob("comics/*", GLOB_ONLYDIR);
    if ($json) {
        echo json_encode(array_map("parseMetadata", $comics));
    } else {
        echo $twig->render("index.html.twig", [
            "metadata" => null,
            "comics" => array_map("parseMetadata", $comics),
            "css" => "index"
        ]);
    }
}