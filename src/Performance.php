<?php

namespace Aponahmed\Cmsstatic;

class Performance
{
    public array $data;
    private $debug;

    public function __construct($debug = true)
    {
        $this->debug = $debug;
    }

    public function start(string $k, $label = "", $details = [])
    {
        if ($this->debug) {
            $this->data[$k] = ['start' => self::time(), 'label' => $label, 'details' => $details];
        }
    }

    public function end(string $k)
    {
        if ($this->debug) {
            if (array_key_exists($k, $this->data)) {
                $this->data[$k]['end'] = self::time();
            }
        }
    }

    /**
     * To measure the execution time
     * @param string $k name of execution
     * @return float the time in milliseconds
     */
    function measure(string $k)
    {
        if (array_key_exists($k, $this->data)) {
            return ($this->data[$k]['end'] - $this->data[$k]['start']) * 1000;
        }
    }

    function log()
    {
        //echo $this->measure('system');
        return $this->generateTable();
    }

    static function time()
    {
        return microtime(true);
    }

    private function generateTableRow($k, $step)
    {
        $timeConsumed = $this->measure($k);
        return '<tr>
        <td style="border: 1px solid #ccc; padding: 0px 4px;font-size:12px;line-height:1">' . htmlspecialchars($step['label']) . '</td>
        <td style="border: 1px solid #ccc; padding: 0px 4px;font-size:12px;line-height:1">' . number_format($timeConsumed, 3) . '</td>
        <td style="border: 1px solid #ccc; padding: 0px 4px;font-size:12px;line-height:1">' . htmlspecialchars($step['details']['file']) . ' (Line ' . $step['details']['line'] . ')</td>
    </tr>';
    }

    public function generateTable()
    {
        $tableHtml = '<table border="1" style="border-collapse: collapse;">
        <tr>
            <th style="border: 1px solid #ccc; padding: 0px 4px;font-size:12px;line-height:1">Breakpoint</th>
            <th style="border: 1px solid #ccc; padding: 0px 4px;font-size:12px;line-height:1">Time Consumed</th>
            <th style="border: 1px solid #ccc; padding: 0px 4px;font-size:12px;line-height:1">Starting Script Location (Line)</th>
        </tr>';

        foreach ($this->data as $k =>  $step) {
            $tableHtml .= $this->generateTableRow($k, $step);
        }

        $tableHtml .= '</table>';

        return $tableHtml;
    }
}
