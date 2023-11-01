<?php

namespace Aponahmed\Cmsstatic\Utilities;

use Aponahmed\Cmsstatic\Utilities\MetaBox;

class MetaBoxs extends Singleton
{
    private $boxesData = [];
    private $boxes = [];

    public function add($args = [])
    {
        if (!isset($args['id']) || !isset($args['title']) || !isset($args['sidebar']) || !isset($args['callback']) || !isset($args['templates'])) {
            throw new \InvalidArgumentException("Meta box arguments are missing.");
        }
        $this->boxesData[] = $args;
    }

    /**
     * Individual Instance of metaBox
     *   */
    public function buildInstances()
    {
        $this->boxes = [];
        foreach ($this->boxesData as $boxData) {
            $this->boxes[] = new MetaBox($boxData);
        }

        return $this;
    }

    public function render($position = 'sidebar', $arg = [])
    {
        $filteredBoxes = [];

        foreach ($this->boxes as $box) {
            if ($position === 'sidebar') {
                if (isset($box->properties['sidebar']) && $box->properties['sidebar'] === true) {
                    $filteredBoxes[] = $box;
                }
            } else {
                if (!isset($box->properties['sidebar']) || $box->properties['sidebar'] !== true) {
                    $filteredBoxes[] = $box;
                }
            }
        }
        //var_dump($filteredBoxes);

        usort($filteredBoxes, function ($box1, $box2) {
            $priority1 = $box1->properties['priority'] ?? 'default';
            $priority2 = $box2->properties['priority'] ?? 'default';
            return $this->comparePriority($priority1, $priority2);
        });

        foreach ($filteredBoxes as $filteredBox) {
            $filteredBox->ui($arg);
        }
    }

    private function comparePriority($priority1, $priority2)
    {
        $priorities = ['high' => 3, 'default' => 2, 'low' => 1];
        return ($priorities[$priority2] ?? 0) - ($priorities[$priority1] ?? 0);
    }
}
