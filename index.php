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
    $pagesInfo = array();
    foreach ($pages as $page)
    {
        $data = pathinfo($page);
        $pagesInfo[intval($data["filename"])] = $data;
    }
    ksort($pagesInfo);
    $metadata["page_format"] = array_map(function(array $page): string { return $page["extension"]; }, $pagesInfo);
    $metadata["page_count"] = count($pagesInfo);
    
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
    if (!file_exists(".metadata"))
    {
        file_put_contents(".metadata", "{}");
        $data = [];
    }
    else
    {
        $data = json_decode(file_get_contents(".metadata"), true);
    }

    $comics = glob("comics/*", GLOB_ONLYDIR);
    $finalComics = array_map("parseMetadata", $comics);
    foreach ($finalComics as $f)
    {
        if (array_key_exists($f["id"], $data))
        {
            $f["last_update"] = $data[$f["id"]];
        }
        else
        {
            $f["last_update"] = 0;
        }
    }

    echo $twig->render("index.html.twig", [
        "metadata" => null,
        "comics" => $finalComics,
        "css" => "index"
    ]);
}