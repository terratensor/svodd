<?php

declare(strict_types=1);
use frontend\assets\ShowdownAsset;


ShowdownAsset::register($this);
?>

<div id="content-markdown-body"></div>

<script>
    loadContent();
    async function loadContent() {
        const response = await fetch('https://raw.githubusercontent.com/terratensor/svodd/main/userguide/README.md');
        let mdText = await response.text();

        // Используйте любое средство преобразования Markdown в HTML, например showdown.js
        var converter = new showdown.Converter();
        var htmlText = converter.makeHtml(mdText);

        document.getElementById("content-markdown-body").innerHTML = htmlText;
    }
</script>