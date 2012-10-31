<?php
/**
 * Common controller.
 */
class AmController extends CController
{
    /**
     * @var string
     */
    public $layout = null;
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    private $_breadcrumbs=array();
    
    /**
     * @var string section title. 
     */
    protected $title;
        
    /**
     * @param array $list
     */
    public function setBreadcrumbs($list)
    {
        $this->_breadcrumbs = $list;
    }
    
    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->_breadcrumbs;
    }
    
    /**
     * Forms page title.
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->module->name . ' - ' . $this->getTitle();
    }
    
    /**
     * Gets localized section title.
     * @return string 
     */
    public function getTitle()
    { 
        return AppManagerModule::t($this->title);
    }
    
    /**
     * Checks if controller id equals to needed. 
     * Mostly used for view.
     * @param string $id controller id.
     * @return bool 
     */
    public function isId($id)
    {
        return ($id === $this->getId());
    }
 
    /**
     * Gets data from POST request.
     * @param string $name
     * @param mixed  $default
     * @return mixed 
     */
    protected function getPost($name, $default = null)
    {
        return Yii::app()->getRequest()->getPost($name, $default);
    }
    
    /**
     * Gets data from GET request.
     * @param string $name
     * @param mixed  $default
     * @return mixed 
     */
    protected function getQuery($name, $default = null)
    {
        return Yii::app()->getRequest()->getQuery($name, $default);
    }
    
    /**
     * Wrapper to Yii user->setFlash() functionality.
     * All messages will be localized for AppManager module.
     * @param string $type could be: 'success', 'notice', 'error'.
     * @param string $message with placeholders.
     * @param array  $params placeholders values. 
     */
    protected function setFlash($type, $message, $params = null)
    {
        Yii::app()->user->setFlash('AppManager.' . $type, 
                                   AppManagerModule::t($message, $params));
    }
}