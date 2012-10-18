<?php
/**
 * Displays flash messages.
 * Most of functionality comes from "yii-flash". 
 * @link http://www.yiiframework.com/extension/yii-flash
 */
class AmFlash extends CWidget
{
    /**
     * @var string message types.
     */
    public $keys = array('success', 'notice', 'error');
    /**
     * @var string module/section name. 
     */
    public $section = 'AppManager';
    /**
     * @var string
     */
    public $template = '<div class="flash-{key}">{message}</div>';
    /**
     * @var string 
     */
    public $js = "jQuery('#{id}').animate({opacity: 1.0}, 3000).fadeOut('slow');";
    /**
     * @var array 
     */
    public $htmlOptions;
    
    /**
     * @var string generated content. 
     */
    protected $content;
    
    /**
     * Executes the widget.
     */
    public function run()
    {
        $this->formOptions();
        $this->formContent();
        $this->displayContent();
    }
    
    /**
     * Forms html options.
     */
    protected function formOptions()
    {
        $id = $this->getId();

        if (isset($this->htmlOptions['id'])) {
            $id = $this->htmlOptions['id'];
        } else {
            $this->htmlOptions['id'] = $id;
        }
    }
    
    /**
     * Forms content for displaying.
     */
    protected function formContent()
    {
        foreach ($this->keys as $key) { 
            $name = $this->formName($key);
            if(Yii::app()->user->hasFlash($name)) {
                $this->content.= strtr($this->template, array(
                    '{key}'    => $key,
                    '{message}'=> Yii::app()->user->getFlash($name),
                ));
            }
        }
    }
    
    /**
     * Shows rendered content. 
     */
    protected function displayContent()
    {
        if (empty($this->content)) {
            return;
        }
        echo CHtml::openTag('div', $this->htmlOptions);
        echo $this->content;
        echo CHtml::closeTag('div');

        $this->registerScript();
    }
    
    /**
     * Registers all necessary js scripts.
     */
    protected function registerScript()
    {
        $id = $this->getId();
        Yii::app()->clientScript->registerScript(__CLASS__. '#' . $id,
                strtr($this->js,array('{id}' => $id)), CClientScript::POS_READY);
    }
    
    /**
     * Generates name related to the section.
     * @param string $key
     * @return string 
     */
    protected function formName($key)
    {
        return $this->section . '.' . $key;
    }
}
