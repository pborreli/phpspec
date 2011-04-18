<?php
/**
 * PHPSpec
 *
 * LICENSE
 *
 * This file is subject to the GNU Lesser General Public License Version 3
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/lgpl-3.0.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phpspec.org so we can send you a copy immediately.
 *
 * @category   PHPSpec
 * @package    PHPSpec
 * @copyright  Copyright (c) 2007 P�draic Brady, Travis Swicegood
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 */
namespace PHPSpec\Console;

/** @see PHPSpec\Framework */
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Framework.php';

/**
 * @category   PHPSpec
 * @package    PHPSpec
 * @copyright  Copyright (c) 2007 P�draic Brady, Travis Swicegood
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 */
class Command
{
	/**
	 * @var PHPSpec\Extensions\Autotest
	 */
	protected $autotest;
	
	/**
	 * @var PHPSpec\Runner
	 */
	protected $runner;
	
	/**
	 * @var PHPSpec\Console\Getopt
	 */
	protected $options;
	
    const USAGE = "Usage: phpspec (FILE|DIRECTORY) + [options]
    -c, --colour, --color            Show coloured (red/green) output
    -a, --autospec                   Run all tests continually, every 10 seconds\r\n";

    /**
     * @param PHPSpec_Console_Getopt $options
     */
    public function __construct(\PHPSpec\Console\Getopt $options = null)
    {
        if (is_null($options)) {
            $options = new \PHPSpec\Console\Getopt;
        }
        $this->options = $options;
    }

    public function run()
    {
		if ($this->options->noneGiven()) {
			$this->printUsage();
			return;
		}
		
	    if ($this->options->getOption('a') || $this->options->getOption('autotest')) {
            $this->getAutotest()->run($this);
            return;
        }
 
		$this->getRunner()->run($this->options);
    }
    
    /**
     * @return \PHPSpec\Extensions\Autotest
     */
    public function getAutotest()
    {
        if (!$this->autotest instanceof \PHPSpec\Extensions\Autotest) {
            $this->autotest = new \PHPSpec\Extensions\Autotest;
        }
        return $this->autotest;
    } 
    
	/**
	 * @param \PHPSpec\Extensions\Autotest $autotest
	 */
    public function setAutotest(\PHPSpec\Extensions\Autotest $autotest)
    {
        $this->autotest = $autotest;
    }

    /**
     * @return PHPSpec_Runner
     */ 
    public function getRunner()
    {
        if (!$this->runner instanceof \PHPSpec\Runner) {
            $this->runner = new \PHPSpec\Runner;
        }
        return $this->runner;
    } 

	/**
	 * @param PHPSpec_Runner $runner
	 */
    public function setRunner(\PHPSpec\Runner $runner)
    {
        $this->runner = $runner;
    }

    public function printUsage()
    {
        echo self::USAGE;
    }
    
}