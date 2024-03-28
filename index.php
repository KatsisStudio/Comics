<?php

require_once "vendor/autoload.php";

use Twig\Loader\FilesystemLoader;
use Twig\Environment;


$loader = new FilesystemLoader(["templates"]);
$twig = new Environment($loader);

if (isset($_GET["comic"]))
{
    $metadata = json_decode(file_get_contents("comics". $_GET["comic"] . "/info.json"), true);
    $metadata["preview"] = "comocs/" . $_GET["comic"] . "/" . $metadata["preview"];

    if (!isset($_GET["page"]))
    {
        echo $twig->render("redirect.html.twig", [
            "metadata" => $metadata,
            "target" => 'https://comics.katsis.net?comic=' . $_GET["comic"] . '&page=1'
        ]);
    }
    else
    {
        $pages = $files = glob("articles/*", GLOB_ONLYDIR);
        echo $twig->render("page.html.twig", [
            "metadata" => $metadata,
            "comic" => $_GET["comic"],
            "page" => $_GET["page"]
        ]);
    }
}
else
{
    echo $twig->render("redirect.html.twig", [
        "metadata" => null,
        "page" => 'https://katsis.net'
    ]);
}