<?php

namespace Aponahmed\Cmsstatic\Utilities;

class MetaBox
{
    public  $properties;

    public  function __construct($args)
    {
        $default = [
            'sidebar' => false,
            'priority' => 'default',
            'type' => ['page'],
            'collapsed' => false,
        ];

        $this->properties = $args + $default;
    }

    public function ui($args)
    {
        if (!in_array('all', $this->properties['type']) && !in_array($args[0]->type, $this->properties['type'])) {
            return;
        }
        $collapse = $this->properties['collapsed'] ? "hide" : "";
        $cls = 'meta-box controll-box';
        if (isset($this->properties['templates'])) {
            if (!in_array('all', $this->properties['templates'])) {
                $cls .= " template-meta-box";
            }
            $cls .= " " . implode(" ", $this->properties['templates']);
        }
        echo '<div class="' . $cls . '">';
        echo '<div class="meta-box-header controll-box-title"><span>' . $this->properties['title'] . '</span></div>';
        echo '<div class="meta-box-container ' . $collapse . '">';
        if (isset($this->properties['callback'])) {
            call_user_func($this->properties['callback'], ...$args);
        }
        echo '</div>';
        echo '</div>';
    }
}
