<?php

$dom = new DOMDocument();
$dom->loadHTMLFile(
    PLUGIN_PATH . '/src/views/frontend/build/index.html',
    LIBXML_HTML_NODEFDTD
);

$scripts = $dom->getElementsByTagName('script');

foreach ($scripts as $script) {
    wp_enqueue_script(
        'vtigerwp-react-app',
        PLUGIN_URL . '/src/views/frontend/build/' . $script->getAttribute('src')
    );
}

$links = $dom->getElementsByTagName('links');

foreach ($links as $link) {
    if($link->getAttribute('rel') !== 'stylesheet') {
        continue;
    }

    wp_enqueue_style(
        'vtigerwp-react-css',
        PLUGIN_URL . '/src/views/frontend/build/' . $link->getAttribute('href')
    );
}

$body = $dom->getElementsByTagName('body')->item(0);

foreach ($body->childNodes as $child){
    echo $child->C14N(); //Note this cannonicalizes the representation of the node, but that's not necessarily a bad thing
}

// echo $dom->saveHTML($body);
