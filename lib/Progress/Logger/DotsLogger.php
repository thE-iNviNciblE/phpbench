<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Progress\Logger;

use PhpBench\Benchmark\Iteration;
use PhpBench\Benchmark\Metadata\BenchmarkMetadata;
use PhpBench\Benchmark\Metadata\SubjectMetadata;
use PhpBench\Benchmark\SuiteDocument;

class DotsLogger extends PhpBenchLogger
{
    private $showBench;

    private $buffer;

    public function __construct($showBench = false)
    {
        $this->showBench = $showBench;
    }

    public function benchmarkStart(BenchmarkMetadata $benchmark)
    {
        static $first = true;

        if ($this->showBench) {
            // do not output a line break on the first run
            if (false === $first) {
                $this->output->writeln('');
            }
            $first = false;

            $this->output->writeln($benchmark->getClass());
        }
    }

    public function subjectEnd(SubjectMetadata $subject)
    {
        $this->buffer .= '.';
        $this->output->write(sprintf(
            "\x0D%s ",
            $this->buffer
        ));
    }

    public function iterationStart(Iteration $iteration)
    {
        $state = $iteration->getIndex() % 4;
        $states = array(
            0 => '|',
            1 => '/',
            2 => '-',
            3 => '\\',
        );

        $this->output->write(sprintf(
            "\x0D%s%s",
            $this->buffer,
            $states[$state]
        ));
    }

    public function endSuite(SuiteDocument $suiteDocument)
    {
        $this->output->write(PHP_EOL . PHP_EOL);
        parent::endSuite($suiteDocument);
    }
}
