<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{

    private $output;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun($command)
    {
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /**
     * @Then /^I should get:$/
     */
    public function iShouldGet(PyStringNode $string)
    {
        if ((string) $string !== $this->output) {
            throw new Exception(
                "Actual output is:\n" . $this->output
            );
        }
    }

    /**
     * @when /^I wait$/
     */
    public function iWait()
    {
        sleep(3);
    }


    /**
     * @When /^I press the xpath "([^"]*)"$/
     */
    public function iPressTheXpath($arg)
    {
        $node = $this->getSession()->getPage()->find('xpath', $arg);
        if($node) {
            $this->getSession()->getPage()->find('xpath', $arg)->press();
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * @WHEN /^I check the "([^"]*)" radio button$/
     */
    public function iCheckTheRadioButton($labelText)
    {
        foreach ($this->getMainContext()->getSession()->getPage()->findAll('css', 'label') as $label) {
            if ($labelText === $label->getText() && $label->has('css', 'input[type="radio"]')) {
                $this->getMainContext()->fillField($label->find('css', 'input[type="radio"]')->getAttribute('name'), $label->find('css', 'input[type="radio"]')->getAttribute('value'));
                return;
            }
        }
        throw new \Exception('Radio button not found');
    }



    /**
     * @When /^I click the xpath "([^"]*)"$/
     */
    public function iClickTheXpath($arg)
    {
        $node = $this->getSession()->getPage()->find('xpath', $arg);
        if($node) {
            $this->getSession()->getPage()->find('xpath', $arg)->click();
        } else {
            throw new Exception('Element not found');
        }
    }


    /**
     * @hidden
     *
     * @When /^I press the element "([^"]*)"$/
     */
    public function iPressTheElement($arg)
    {
        $node = $this->getSession()->getPage()->find('css', $arg);
        if($node) {
            $this->getSession()->getPage()->find('css', $arg)->press();
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * Destroy cookies
     *
     * @Then /^I destroy my cookies$/
     */
    public function iDestroyMyCookies() {
        $this->getSession()->reset();
    }

    /**
     * See if Element has style eg p.padL8 has style font-size= 12px
     *
     * @Then /^the element "([^"]*)" should have style "([^"]*)"$/
     */
    public function theElementShouldHaveStyle($arg1, $arg2)
    {

        $element = $this->getSession()->getPage()->find('css', $arg1);
        if($element) {
            if(strpos($element->getAttribute('style'), $arg2) === FALSE) {
                throw new Exception('Style not found');
            }
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * See if element is visible
     *
     * @Then /^element "([^"]*)" is visible$/
     */
    public function elementIsVisible($arg) {

        $el = $this->getSession()->getPage()->find('css', $arg);
        if($el) {
            if(!$el->isVisible()){
                throw new Exception('Element is not visible');
            }
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * See if element is not visible
     *
     * @Then /^element "([^"]*)" is not visible$/
     */
    public function elementIsNotVisible($arg) {

        $el = $this->getSession()->getPage()->find('css', $arg);
        if($el) {
            if($el->isVisible()){
                throw new Exception('Element is visible');
            }
        } else {
            throw new Exception('Element not found');
        }
    }


}
