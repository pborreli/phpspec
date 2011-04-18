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
namespace PHPSpec\Runner;

/**
 * @category   PHPSpec
 * @package    PHPSpec
 * @copyright  Copyright (c) 2007 Pádraic Brady, Travis Swicegood
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 */
class Example
{

    protected $_context = null;
    protected $_methodName = null;
    protected $_specificationText = null;
    protected $_failedMessage = null;

    public function __construct(\PHPSpec\Context $context, $methodName)
    {
        $this->_context = $context;
        $this->_methodName = $methodName;
        $this->_specificationText = $this->_setSpecificationText($this->_methodName);
    }

    public function getMethodName()
    {
        return $this->_methodName;
    }

    public function getSpecificationText()
    {
        return $this->_specificationText;
    }

    public function getContextDescription()
    {
        return $this->_context->getDescription();
    }

    public function getSpecificationBeingExecuted()
    {
        if (is_null($this->_specificationBeingExecuted)) {
            throw new \PHPSpec\Exception('cannot return a PHPSpec_Specification until the example is executed');
        }
        return $this->_specificationBeingExecuted;
    }

    public function getFailedMessage()
    {
        if (is_null($this->_failedMessage)) {
            throw new \PHPSpec\Exception('cannot return a failure message until the example is executed');
        }
        return $this->_failedMessage;
    }

    public function execute()
    {
        $this->_context->clearCurrentSpecification();

        /**
         * Spec execution
         * *Each methods are reserved for internal stepping setup/teardown
         */
        if (method_exists($this->_context, 'beforeEach')) {
            $this->_context->beforeEach();
        }
        if (method_exists($this->_context, 'before')) {
            $this->_context->before();
        }
        
        $line = '';
        try {
            $this->_context->{$this->_methodName}();
        } catch (FailedMatcherException $e) {
            $line = $e->getFormattedLine();
        }

        if (method_exists($this->_context, 'after')) {
            $this->_context->after();
        }
        if (method_exists($this->_context, 'afterEach')) {
            $this->_context->afterEach();
        }

        /**
         * Result collection
         */
        $this->_specificationBeingExecuted = $this->_context->getCurrentSpecification();
        if (is_null($this->_specificationBeingExecuted)) {
            return;
        }

        $expected = $this->_specificationBeingExecuted->getExpectation()->getExpectedMatcherResult();
        $actual = $this->_specificationBeingExecuted->getMatcherResult();
        if ($expected !== $actual) { // ===
            if ($expected === true) {
                $this->_failedMessage = $this->_specificationBeingExecuted->getMatcherFailureMessage();
            } else {
                $this->_failedMessage = $this->_specificationBeingExecuted->getMatcherNegativeFailureMessage();
            }
            $e =  new FailedMatcherException();
            $e->setFormattedLine($line);
            throw $e; // add spec data later
        }
    }

    protected function _setSpecificationText($methodName)
    {
        $methodName = substr($methodName, 2);
        $terms = preg_split("/(?=[[:upper:]])/", $methodName, -1, PREG_SPLIT_NO_EMPTY);
        $termsLowercase = array_map('strtolower', $terms);
        return implode(' ', $termsLowercase);
    }
}
