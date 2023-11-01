<?php

function add_meta_box($arg)
{
    global $metabox;
    $metabox->add($arg);
    return $metabox;
}

function meta_box($position = '',...$arg)
{
    global $metabox;
    $metabox->buildInstances()->render($position,$arg);
}
