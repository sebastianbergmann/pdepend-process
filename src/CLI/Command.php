<?php
/*
 * This file is part of pdepend-process.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PDEPEND\Process\CLI;

use Symfony\Component\Console\Command\Command as AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use SebastianBergmann\PDEPEND\Process\Parser;
use SebastianBergmann\PDEPEND\Process\Renderer\HtmlRenderer;

class Command extends AbstractCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('pdepend-process')
             ->setDescription('')
             ->addArgument(
                 'logfile',
                 InputArgument::REQUIRED,
                 'The PHP_Depend XML logfile to process'
             )
             ->addOption(
                 'dashboard-html',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Generate dashboard in HTML format in specified directory'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dashboardHtml = $input->getOption('dashboard-html');

        $parser = new Parser;
        $data   = $parser->parse($input->getArgument('logfile'));

        if ($dashboardHtml) {
            $renderer = new HtmlRenderer($dashboardHtml);
            $renderer->render($data);
        }
    }
}
