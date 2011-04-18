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
namespace PHPSpec\Matcher;

use \PHPSpec\Matcher;

/**
 * @category   PHPSpec
 * @package    PHPSpec
 * @copyright  Copyright (c) 2007 P�draic Brady, Travis Swicegood
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 */
class BeLessThan implements Matcher
{

    protected $_expected = null;

    protected $_actual = null;

    public function __construct($expected)
    {
        $this->_expected = $expected;
    }

    public function matches($actual)
    {
        $this->_actual = $actual;
        return $this->_expected > $this->_actual;
    }

    public function getFailureMessage()
    {
        return 'expected less than ' . var_export($this->_expected, true) . ', got ' . var_export($this->_actual, true) . ' (using beLessThan())';
    }

    public function getNegativeFailureMessage()
    {
        return 'expected ' . var_export($this->_actual, true) . ' not to be less than ' . var_export($this->_expected, true) . ' (using beLessThan())';
    }

    public function getDescription()
    {
        return 'be less than ' . var_export($this->_expected, true);
    }
}