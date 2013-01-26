<?php
/**
 * Generates a config file.
 */
class AmWriter extends CComponent
{
    /**
     * @var AmConfig 
     */
    protected $config;
    
    /**
     * @var string
     */
    public $indent = '    ';
    
    /**
     * @param AmConfig $config 
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Generates content for the config file.
     * @return string 
     */
    public function getContent()
    {
        ob_start();
        include $this->getTemplate('config');
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    /**
     * Returns generated content.
     * PHP magic method. 
     * @return string
     * @see AmWriter::getContent() 
     */
    public function __toString()
    {
        return (string)$this->getContent();
    }

    /**
     * @return AmConfig 
     */
    protected function getConfig()
    {
        return $this->config;
    }
     
    /**
     * Renders options list.
     * @param mixed $options
     * @param int   $level current level in the config tree. 
     */
    protected function renderOptions($options, $level = 0)
    { 
        if (!is_array($options) && !is_object($options)) {
            if (null === $options) {
                echo 'null';
            } else {
                echo $this->replaceConfigDir(var_export($options, true));
            }
        } else {
            $level++;
            include $this->getTemplate('options');
        }
    }
    
    /**
     * @param int $level 
     */
    protected function renderIndent($level)
    {
        echo str_repeat($this->indent, $level);
    }
    
    /**
     * Renders content for the header block.
     */
    protected function renderHeader()
    {
        include $this->getTemplate('header');
    }
    
    /**
     * Gets the path to the template file.
     * @param string $name
     * @return string 
     */
    protected function getTemplate($name)
    {
        return dirname(__FILE__) . '/templates/' . $name . '.php';
    }
    
    /**
     * Gets absolute path to the config directory.
     * @return string 
     */
    protected function getConfigDir()
    {
        return dirname($this->getConfig()->getLocation());
    }
    
    /**
     * Replaces absolute path to config by relative.
     * @param string $value
     * @return string
     */
    protected function replaceConfigDir($value)
    {
        $configDir = $this->getConfigDir();
        $index = strpos($value, $configDir);
        if (false !== $index) {
            $dir = 'dirname(__FILE__)';
            if (1 === $index) {
                $value = $dir . '.' . str_replace($configDir, '', $value);
            } else {
                $value = str_replace($configDir, '\'.' . $dir . '.\'', $value);
            }
        }
        return $value;
    }
}
