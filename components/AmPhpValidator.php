<?php
/**
 * Checks if an attribute contains a valid php expression.
 */
class AmPhpValidator extends CValidator
{
    /**
     * @var array a valid php callback that is used for set_error_handler().
     */
    private $_systemHandler;
    /**
     * @var object whose attribute is to be validated. 
     */
    private $_object;
    /**
     * @var string 
     */
    private $_attribute;
    
    /**
     * Validates a single attribute.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute)
    { 
        $this->_object    = $object;
        $this->_attribute = $attribute;
        
        $this->initErrorHandler();
        ob_start();
        $this->evaluateExpression($object->$attribute);
        if (ob_get_contents()) {
            $this->addErrorMessage();
        }
        ob_end_clean();
        $this->restoreErrorHandler();
    }
    
    /**
     * Adds a message for error case.
     */
    protected function addErrorMessage()
    {
        $this->addError($this->_object, $this->_attribute, 
            AppManagerModule::t('Invalid expression'));
    }
    
    /**
     * Replaces the system handler on internal.
     */
    protected function initErrorHandler()
    {
        $this->_systemHandler = set_error_handler(array($this, 'handleError'));
    }
    
    /**
     * Restores the system error handler.
     * @see CApplication::initSystemHandlers
     */
    protected function restoreErrorHandler()
    {
        if($this->_systemHandler && YII_ENABLE_ERROR_HANDLER) {
            set_error_handler($this->_systemHandler, error_reporting());
        }
    }
    
    /**
     * Catches errors that are triggered by php.  
     * @param int    $code
     * @param string $message 
     * @see CApplication::handleError()
     */
    public function handleError($code, $message) 
    { 
        $this->addErrorMessage();
    }
}

