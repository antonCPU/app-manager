<?php
/**
 * File description.
 *
 * @author Author Name <author@email.com>
 * @link http://link-to-page
 * @copyright Copyright &copy; 2008-2011 Organization
 * @license http://www.yiiframework.com/license/
 */

/**
 * Short Description
 *
 * Long Description.
 * With line breaks.
 *
 * @author Author Name <author@email.com>
 * @version 0.7
 */
class AmClassInfoSource extends CComponent
{
    public $property;
    /**
     * @var string 
     */
    public $propertyString = 'string default';
    public $propertyInt    = 10;
    /**
     * @var string|bool|int 
     */
    public $propertyMulti;
    /**
     * Short description
     * @var int
     */
    public $propertyShortDescription;
    /**
     * Short description
     * 
     * Long description.
     * @var int
     */
    public $propertyLongDescription;
    /**
     * @var int var description.
     */
    public $propertyVarDescription;
    /**
     * @property int property description.
     */
    public $propertyPropertyDescription;
    /**
     * Short description
     * 
     * Long description.
     * @var int|string var description.
     * @since 1.0
     * @see AmClassInfoSource::setMethod()
     */
    public $propertyFullDescription;
    public $propertyEmptyDescription;
    
    public function setMethod($value) {}
    
    /**
     * @param string $value
     */
    public function setMethodString($value) {}
    
    /**
     * @param string|bool|int $value
     */
    public function setMethodMulti($value) {}
    
    /**
     * Short description
     * @param string $value
     */
    public function setMethodShortDescription($value) {}
    
    /**
     * Short description
     * 
     * Full description.
     * @param string $value
     */
    public function setMethodLongDescription($value) {}
    
    /**
     * @param string $value param description.
     */
    public function setMethodParamDescription($value) {}
    
    /**
     * Short description.
     * 
     * Full description.
     * @param string $value param description.
     * @since 1.0
     * @see AmClassInfoSource::setMethod()
     */
    public function setMethodFullDescription($value) {}
    
    public function setMethodEmptyDescription($value) {}
}
