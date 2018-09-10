<?php
/*
 * This file is part of pdepend-process.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PDEPEND\Process;

use XMLReader;

class Parser
{
    public function parse($file)
    {
        $classes      = [];
        $methods      = [];
        $ccn          = [];
        $npath        = [];
        $cr           = [];
        $rcr          = [];
        $classLength  = [];
        $methodLength = [];

        $reader = new XMLReader;
        $reader->open($file);

        while ($reader->read()) {
            switch ($reader->name) {
                case 'class':
                case 'trait': {
                    $class               = $reader->getAttribute('name');
                    $cr[$class]          = $reader->getAttribute('cr');
                    $rcr[$class]         = $reader->getAttribute('rcr');
                    $classLength[$class] = $reader->getAttribute('lloc');

                    $classes[$class] = [
                        'lloc' => $classLength[$class],
                        'cr'   => $cr[$class],
                        'rcr'  => $rcr[$class]
                    ];
                }
                break;

                case 'method': {
                    $method                = $class . '::' . $reader->getAttribute('name');
                    $ccn[$method]          = $reader->getAttribute('ccn2');
                    $npath[$method]        = $reader->getAttribute('npath');
                    $methodLength[$method] = $reader->getAttribute('lloc');

                    $methods[$method] = [
                        'lloc'  => $methodLength[$method],
                        'ccn'   => $ccn[$method],
                        'npath' => $npath[$method]
                    ];
                }
                break;
            }
        }

        $reader->close();

        \asort($ccn);
        \asort($npath);
        \asort($cr);
        \asort($rcr);
        \asort($classLength);
        \asort($methodLength);

        return [
            'ccn'          => \array_reverse($ccn),
            'npath'        => \array_reverse($npath),
            'cr'           => \array_reverse($cr),
            'rcr'          => \array_reverse($rcr),
            'classLength'  => \array_reverse($classLength),
            'methodLength' => \array_reverse($methodLength),
            'classes'      => $classes,
            'methods'      => $methods
        ];
    }
}
