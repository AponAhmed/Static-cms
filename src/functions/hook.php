<?php

function add_action($tag, $callback, $priority = 10, $accepted_args = 1)
{
    global $hook;
    $hook->add_action($tag, $callback, $priority, $accepted_args);
    return $hook;
}

function do_action($tag, ...$args)
{
    global $hook;
    return $hook->do_action($tag, $args);
}

function add_filter($tag, $callback, $priority = 10, $accepted_args = 1)
{
    global $hook;
    $hook->add_filter($tag, $callback, $priority, $accepted_args);
    return $hook;
}

function apply_filters($tag, $value, ...$args)
{
    global $hook;
    return $hook->apply_filters($tag, $value, $args);
}
