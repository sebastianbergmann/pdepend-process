<?php
/*
 * This file is part of pdepend-process.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PDEPEND\Process\Renderer;

use SebastianBergmann\PDEPEND\Process\RuntimeException;
use Text_Template;

class HtmlRenderer implements Renderer
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * @param string $directory
     *
     * @throws RuntimeException
     */
    public function __construct($directory)
    {
        $this->templatePath = \sprintf(
            '%s%sTemplate%s',

            __DIR__,
            DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR
        );

        $this->directory = $this->getDirectory($directory);
    }

    /**
     * @param array $data
     *
     * @throws RuntimeException
     */
    public function render(array $data)
    {
        $this->copyFiles($this->directory);

        $classLengthChart = $this->renderBarChart(
            $this->partitionData($data['classLength'])
        );

        $methodLengthChart = $this->renderBarChart(
            $this->partitionData($data['methodLength'])
        );

        $ccnChart = $this->renderBarChart(
            $this->partitionData($data['ccn'])
        );

        $npathChart = $this->renderBarChart(
            $this->partitionData($data['npath'])
        );

        $template = new Text_Template(
            $this->templatePath . 'dashboard.html',
            '{{',
            '}}'
        );

        $template->setVar(
            [
                'classes_table'        => $this->renderClassesTable($data),
                'methods_table'        => $this->rendermethodsTable($data),
                'class_length_labels'  => $classLengthChart['labels'],
                'class_length_values'  => $classLengthChart['values'],
                'method_length_labels' => $methodLengthChart['labels'],
                'method_length_values' => $methodLengthChart['values'],
                'ccn_labels'           => $ccnChart['labels'],
                'ccn_values'           => $ccnChart['values'],
                'npath_labels'         => $npathChart['labels'],
                'npath_values'         => $npathChart['values']
            ]
        );

        $template->renderTo($this->directory . 'index.html');
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function renderBarChart(array $data)
    {
        $result = [
            'labels' => \json_encode(\array_keys($data)),
            'values' => \json_encode(\array_values($data))
        ];

        return $result;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function renderClassesTable(array $data)
    {
        $buffer = '';

        foreach ($data['classes'] as $class => $classData) {
            $buffer .= \sprintf(
                '<tr><td>%s</td><td class="text-right">%d</td><td class="text-right">%.2f</td><td class="text-right">%.2f</td></tr>',
                $class,
                $classData['lloc'],
                $classData['cr'],
                $classData['rcr']
            );
        }

        return $buffer;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function renderMethodsTable(array $data)
    {
        $buffer = '';

        foreach ($data['methods'] as $method => $methodData) {
            $npath = \sprintf('%g', $methodData['npath']);

            if (\strpos($npath, 'e+')) {
                $npath = \sprintf(
                    '<abbr title="%s">%s</abbr>',
                    $methodData['npath'],
                    $npath
                );
            }

            $buffer .= \sprintf(
                '<tr><td>%s</td><td class="text-right">%d</td><td class="text-right">%d</td><td class="text-right">%s</td></tr>',
                $method,
                $methodData['lloc'],
                $methodData['ccn'],
                $npath
            );
        }

        return $buffer;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function partitionData(array $data)
    {
        $partitions    = [];
        $numPartitions = \ceil(2 * \pow(\count($data), (1 / 3)));
        $width         = \floor(\max($data) / $numPartitions);

        foreach ($data as $value) {
            $key = \floor($value / $width) * $width;
            $key = $key . '-' . ($key + $width);

            if (!isset($partitions[$key])) {
                $partitions[$key] = 1;
            } else {
                $partitions[$key]++;
            }
        }

        \ksort($partitions, SORT_NATURAL);

        return $partitions;
    }

    /**
     * @param string $target
     *
     * @throws RuntimeException
     */
    private function copyFiles($target)
    {
        $dir = $this->getDirectory($target . 'css');
        \copy($this->templatePath . 'css/bootstrap.min.css', $dir . 'bootstrap.min.css');
        \copy($this->templatePath . 'css/nv.d3.min.css', $dir . 'nv.d3.css');
        \copy($this->templatePath . 'css/style.css', $dir . 'style.css');

        $dir = $this->getDirectory($target . 'fonts');
        \copy($this->templatePath . 'fonts/glyphicons-halflings-regular.eot', $dir . 'glyphicons-halflings-regular.eot');
        \copy($this->templatePath . 'fonts/glyphicons-halflings-regular.svg', $dir . 'glyphicons-halflings-regular.svg');
        \copy($this->templatePath . 'fonts/glyphicons-halflings-regular.ttf', $dir . 'glyphicons-halflings-regular.ttf');
        \copy($this->templatePath . 'fonts/glyphicons-halflings-regular.woff', $dir . 'glyphicons-halflings-regular.woff');
        \copy($this->templatePath . 'fonts/glyphicons-halflings-regular.woff2', $dir . 'glyphicons-halflings-regular.woff2');

        $dir = $this->getDirectory($target . 'js');
        \copy($this->templatePath . 'js/bootstrap.min.js', $dir . 'bootstrap.min.js');
        \copy($this->templatePath . 'js/d3.min.js', $dir . 'd3.min.js');
        \copy($this->templatePath . 'js/holder.min.js', $dir . 'holder.min.js');
        \copy($this->templatePath . 'js/html5shiv.min.js', $dir . 'html5shiv.min.js');
        \copy($this->templatePath . 'js/jquery.js', $dir . 'jquery.js');
        \copy($this->templatePath . 'js/jquery.tablesorter.min.js', $dir . 'jquery.tablesorter.min.js');
        \copy($this->templatePath . 'js/nv.d3.min.js', $dir . 'nv.d3.min.js');
        \copy($this->templatePath . 'js/respond.min.js', $dir . 'respond.min.js');
    }

    /**
     * @param string $directory
     *
     * @return string
     *
     * @throws RuntimeException
     */
    private function getDirectory($directory)
    {
        if (\substr($directory, -1, 1) != DIRECTORY_SEPARATOR) {
            $directory .= DIRECTORY_SEPARATOR;
        }

        if (\is_dir($directory)) {
            return $directory;
        }

        if (@\mkdir($directory, 0777, true)) {
            return $directory;
        }

        throw new RuntimeException(
            \sprintf(
                'Directory "%s" does not exist.',
                $directory
            )
        );
    }
}
