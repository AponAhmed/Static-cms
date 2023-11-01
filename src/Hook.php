<?php

namespace Aponahmed\Cmsstatic;

use Aponahmed\Cmsstatic\Utilities\Singleton;

class Hook extends Singleton
{
    private $actions = [];
    public $filters = [];

    public function add_action($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        if (!isset($this->actions[$hook])) {
            $this->actions[$hook] = [];
        }
        $this->actions[$hook][] = [
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
    }

    public function do_action(String $hook, ...$args)
    {
        if (!isset($this->actions[$hook])) {
            return;
        }

        // Sort actions by priority before executing them
        usort($this->actions[$hook], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });

        foreach ($this->actions[$hook] as $action) {
            $callback = $action['callback'];
            $accepted_args = $action['accepted_args'];
            $args = array_slice($args, 0, $accepted_args);
            call_user_func_array($callback, ...$args);
        }
    }

    public function add_filter($tag, $callback, $priority = 10, $accepted_args = 1)
    {
        if (!isset($this->filters[$tag])) {
            $this->filters[$tag] = [];
        }
        $this->filters[$tag][] = [
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
    }

    public function apply_filters($tag, $value, ...$args)
    {
        if (!isset($this->filters[$tag])) {
            return $value;
        }

        // Sort filters by priority before applying them
        usort($this->filters[$tag], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });

        foreach ($this->filters[$tag] as $filter) {
            $callback = $filter['callback'];
            $accepted_args = $filter['accepted_args'];
            $args = array_merge([$value], array_slice($args, 0, $accepted_args - 1));
            $value = call_user_func_array($callback, $args);
        }

        return $value;
    }
}
