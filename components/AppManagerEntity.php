<?php
/**
 * Base class for all entities.
 */
abstract class AppManagerEntity extends AppManagerModel
{
    /**
     * @var string unique identifier.
     */
    protected $id;
    /**
     * @var string human-readable name.
     */
    protected $name;
    /**
     * @var string full description.
     */
    protected $desc;
    /**
     * @var string short description.
     */
    protected $shortDesc;
    /**
     * @var string author's details.
     */
    protected $author;
    /**
     * @var string entity web reference.
     */
    protected $link;
    /**
     * @var string the class name.
     */
    protected $className;
    /**
     * @var string the class name with a full alias path.
     */
    protected $fullClassName;
    /**
     * @var bool whether entity is activated.
     */
    protected $isActive;
    /**
     * @var AppManagerOptions list of options.
     */
    protected $options;
    
    /**
     * @var string full path to the entity file or directory.
     */
    private $_path;
    /**
     * @var string full path to the entity file.
     */
    private $_fileName;
    /**
     * @var AppManagerParser reflection handler.
     */
    private $_parser;
    /**
     * @var AppManagerConfig main settings. 
     */
    private $_config;
    
    /**
     * Initialization.
     * @param string $path full path or with aliases.
     */
    public function __construct($path = null)
    {
        $this->setPath($path);
    }
    
    /**
     * Searches available entities.
     * @return CArrayDataProvider 
     */
    public function search() 
    {
        $entities = $this->scanDirs();
        foreach ($entities as &$entity) {
            $entity = $this->createEntity($entity);
        }
        $entities = array_merge($entities, $this->getDefaultEntities());
        return $this->createDataProvider($entities);
    }
    
    /**
     * @return bool 
     */
    public function activate() 
    {
        if (!$this->canActivate()) {
            return false;
        }
        $this->loadConfigSection()->add($this->getId(), array(
            'class' => $this->getFullClassName(),
        ));
        return $this->saveConfig();
    }
    
    /**
     * @return bool 
     */
    public function deactivate() 
    {
        if (!$this->canDeactivate()) {
            return false;
        }
        $this->loadConfigSection()->remove($this->getId());
        return $this->saveConfig();
    }
    
    /**
     * Completely deletes the entity.
     * @return bool
     */
    public function delete() 
    {
        if (!$this->canDelete()) {
            return false;
        }
    }
    
    /**
     * Saves entity and all options.
     * @return bool 
     */
    public function save() 
    {
        if (!$this->canUpdate() || !$this->validate()) {
            return false;
        }
        $config = $this->getConfig();
        $config->add('class', $this->getFullClassName());
        if (!$this->getOptions()->updateConfig()) {
            return false;
        }
        return $this->saveConfig();
    }
    
    /**
     * Validation rules.
     * @return array 
     */
    public function rules()
    {
        return array(
          array('fullClassName', 'required'),
          array('fullClassName', 'validClass'),  
        );
    }
    
    /**
     * Checks if attribute is an existed class
     * @param string $attribute 
     */
    public function validClass($attribute)
    { 
        $path = Yii::getPathOfAlias($this->$attribute);
        if (!file_exists($path . '.php')) {
            $this->addError($attribute, 
                AppManagerModule::t('Class does not exist.'));
        }
    }
    
    /**
     * Restores options and the class name to defaults.
     * @return bool
     */
    public function restore()
    {
        if (!$this->canRestore()) {
            return false;
        } 
        $config = $this->getConfig();
        $config->clear();
        $config->add('class', $this->getFullClassName());
        return $this->saveConfig();
    }
    
    /**
     * @return string 
     */
    public function getId()
    {
        if (null === $this->id) {
            $this->id = $this->createId();
        }
        return $this->id;
    }
    
    /**
     * Forms unique identifier.
     * @return string
     */
    protected function createId()
    {
        $name = $this->getName();
        $name[0] = strtolower($name[0]);
        return (string)$name;
    }
    
    /**
     * Forms entity for id.
     * @param string $id
     * @return AppManagerEntity 
     */
    public function findById($id)
    {
        if ($path = $this->resolveId($id)) {
            $this->setPath($path);
        }
        return $this;
    }
    
    /**
     * @return string
     * @see CButtonColumn 
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }
    
    /**
     * @param string $name 
     */
    public function setName($name)
    {
        $this->name = ucfirst($name);
    }
    
    /**
     * @return string
     */
    public function getName() 
    {
        if (null === $this->name) {
            $this->setName($this->resolveName());
        }
        return $this->name;
    }
    
    /**
     * @param string $name 
     */
    public function setClassName($name)
    {
        $this->className = $name;
    }
    
    /**
     * @return string 
     */
    public function getClassName() 
    {
        if (null === $this->className) {
            $this->className = $this->resolveClassName();
        } 
        return $this->className;
    }
    
    /**
     * Gets class name for the original entity.
     * @return string
     */
    public function getDefaultClassName()
    {
        return $this->getParser()->getClassName();
    }
    
    /**
     * @return string 
     */
    public function getFullClassName()
    {
        if (null === $this->fullClassName) {
            $this->fullClassName = $this->normalizeClassName();
        }
        return $this->fullClassName;
    }
    
    /**
     * @param string $name 
     */
    public function setFullClassName($name)
    {
        $this->fullClassName = $name;
    }
    
    /**
     * @return string
     */
    public function getDesc() 
    {
        if (null === $this->desc) {
            $this->desc = $this->getParser()->getDesc();
        }
        return $this->desc;
    }
    
    /**
     * @return string 
     */
    public function getShortDesc() 
    {
        if (null === $this->shortDesc) {
            $this->shortDesc = $this->getParser()->getShortDesc();
        }
        return $this->shortDesc;
    }
    
    /**
     * @return string 
     */
    public function getAuthor() 
    {
        if (null === $this->author) {
            $this->author = $this->getParser()->getAuthor();
        }
        return $this->author;
    }
    
    /**
     * @return string 
     */
    public function getLink() 
    {
        if (null === $this->link) {
            $this->link = $this->getParser()->getLink();
        }
        return $this->link;
    }

    /**
     * @return bool 
     */
    public function getIsActive() 
    { 
        if (null === $this->isActive) {
            $this->isActive = (bool)$this->getConfig()->count();
        } 
        return $this->isActive;
    }
    
    /**
     * Checks if the entity belongs to Yii core.
     * @return bool 
     */
    public function getIsCore()
    {
        return (bool)$this->getCorePath($this->getId());
    }
    
    /**
     * Determines if the entity can BE activated.
     * @return bool
     */
    public function canActivate()
    {
        return !$this->getIsActive();
    }
    
    /**
     * @return bool 
     */
    public function canDeactivate()
    {
        return $this->getIsActive();
    }
    
    /**
     * @return bool 
     */
    public function canUpdate()
    {
        return $this->getIsActive();
    }
    
    /**
     * @return bool 
     */
    public function canDelete()
    {
        return true;
    }
    
    /**
     * @return bool 
     */
    public function canRestore()
    {
        return ($this->canUpdate() && ($this->getConfig()->count() > 1));
    }
    
    /**
     * Sets options from input data.
     * @param array $options 
     */
    public function setOptions($options)
    { 
        $this->getOptions()->attributes = $options;
    }
    
    /**
     * @return AppManagerOptions 
     */
    public function getOptions() 
    { 
        if (null === $this->options) {
            $options = new AppManagerOptions;
            $options->setParser($this->getParser());
            $options->setConfig($this->getConfig());
            $this->options = $options;
        }
        return $this->options;
    }
    
    /**
     * @return AppManagerOptions 
     */
    public function getOptionsProvider()
    {
        return $this->getOptions()->getProvider();
    }
    
    /**
     * @param string $path full path with directory separators or with aliases. 
     */
    public function setPath($path)
    {
        if (!empty($path)) {
            $this->_path = $this->resolveFullPath($path);
        }
    }
    
    /**
     * @return string 
     */
    public function getPath()
    {
        return $this->_path;
    }
    
    /**
     * @return string 
     */
    public function getFileName()
    {
        if (null === $this->_fileName) {
            $this->_fileName = $this->resolveFileName($this->getPath());
        }
        return $this->_fileName;
    }
    
    /**
     * @return AppManagerParser 
     */
    protected function getParser()
    { 
        if (null === $this->_parser) {
            $this->_parser = new AppManagerParser($this->getFileName());
        }
        return $this->_parser;
    }
    
    /**
     * @return AppManagerConfig 
     */
    protected function getConfig()
    {
        if (null === $this->_config) {
            $this->_config = $this->loadConfig();
        }
        return $this->_config;
    }
    
    /**
     * Scans all set directories.
     * @return array list of AppManagerEntity
     */
    protected function scanDirs()
    {
        $result = array();
        foreach ($this->getScanDirs() as $dir) {
            $result = array_merge($result, $this->scanDir($dir));
        }
        return $result;
    }
    
    /**
     * Finds entities only in one directory.
     * @param string $dir
     * @return array list of AppManagerEntity 
     */
    protected function scanDir($dir)
    {
        $entities = scandir($dir);
        unset($entities[0], $entities[1]);
        foreach ($entities as &$entity) {
            $entity = $dir . DIRECTORY_SEPARATOR . $entity;
        }
        return (array)$entities;
    }
    
    /**
     * Gets list of directories that need to find in.
     * @return array empty for abstraction
     */
    protected function getScanDirs()
    {
        return array();
    }
    
    /**
     * Gets list of entities that need to be added without scan.
     * @return array list of AppManagerEntity
     */
    protected function getDefaultEntities()
    {
        $entities = array();
        foreach ($this->getCoreList() as $alias => $path) {
            $entities[] = $this->createEntity()->findById($alias);
        }
        return $entities;
    }
    
    /**
     * Gets full path to a core entity.
     * @param string $id
     * @return string
     */
    protected function getCorePath($id)
    {
        $core = $this->getCoreList();
        return isset($core[$id]) ? $this->formCorePath($core[$id]) : null;
    }
    
    /**
     * Gets list of core entities.
     * @return array
     */
    protected function getCoreList()
    {
        return array();
    }
    
    /**
     * Creates path to core entity.
     * @param string $path
     * @return string 
     */
    protected function formCorePath($path)
    { 
        return 'system.' . $path;
    }
    
    /**
     * Finds full path using supplied.
     * @param string $path could be with Yii aliases
     * @return string 
     */
    protected function resolveFullPath($path)
    {            
        if ($fullPath = Yii::getPathOfAlias($path)) {
            if (is_file($fullPath . '.php')) {
                $fullPath.= '.php';
            }
        } else {
            $fullPath = $path;
        }
        return $fullPath;
    }
    
    /**
     * Finds full path to entity file.
     * @param string $path
     * @return string 
     * @throws CException in case if file does not exist
     */
    protected function resolveFileName($path)
    {  
        if (false === strpos($path, '.')) {
            $path .= DIRECTORY_SEPARATOR . $this->formFileName(basename($path));
        }
        if (!file_exists($path)) {
            throw new CException(AppManagerModule::t('{path} does not exist.', 
                                 array('{path}' => $path)));
        }
        return $path;
    }
    
    /**
     * @param string $id
     * @return AppManagerEntity
     * @throws CException in case if an entity does not exist 
     */
    protected function resolveId($id)
    {
        if (empty($id)) {
            return false;
        }
        if ($path = $this->getCorePath($id)) {
            $this->setName($id);
            return $path;
        }
        
        foreach ($this->scanDirs() as $entity) { 
            if (strtolower(basename($entity, '.php')) === strtolower($id)) {
                return $entity;
            }
        }
        throw new CException(AppManagerModule::t('{id} does not exist.',
                             array('{id}' => $id)));
    }
    
    /**
     * Forms name.
     * @return string 
     */
    protected function resolveName()
    {
        return basename($this->getFileName(), '.php');
    }
    
    /**
     * Forms class name.
     * @return string 
     */
    protected function resolveClassName()
    {
        $name = $this->resolveName();
        $tmp = explode('.', $name);
        return array_pop($tmp);
    }
    
    /**
     * Forms file name.
     * @param string $name
     * @return string 
     */
    protected function formFileName($name)
    {
        return $name . '.php';
    }
    
    /**
     * Creates full class name.
     * @return string 
     */
    protected function normalizeClassName()
    {
        if ($this->getIsCore()) {
            return $this->getCorePath($this->getId());
        } elseif (false === strpos($this->getClassName(), '.')) {
            return $this->formFullClassName();
        }
        return $this->getClassName();
    }
    
    /**
     * Forms full class name
     * @return string 
     */
    protected function formFullClassName()
    {
        return $this->getClassName();
    }
    
    /**
     * Creates entity instance.
     * @param string $path
     * @return AppManagerEntity 
     */
    protected function createEntity($path = null)
    {
        $class = get_class($this);
        return new $class($path);
    }
    
    /**
     * Loads main config.
     * Creates an empty if does not exist.
     * @return AppManagerConfig
     */
    protected function loadConfig()
    {
        $config  = $this->loadConfigSection();
        $name    = $this->getId();
        $current = $config->itemAt($name);
        if (null === $current) {
            $key = $config->search($name);
            if (false !== $key) { //normalize config
                $config->remove($key);
                $config->add($name, array(
                    'class' => $this->getFullClassName(),
                ));
            } else {
                $config->add($name, array());
            }
            $current = $config->itemAt($name);
        } 
        return $current;
    }
    
    /**
     * @return bool 
     */
    protected function saveConfig()
    {
        return AppManagerModule::config()->save();
    }
    
    /**
     * Gets value from config.
     * @param string $name
     * @return mixed 
     */
    protected function getConfigValue($name)
    {
        if ($config = $this->getConfig()) {
            return $config->itemAt($name);
        }
        return null;
    }
    
    /**
     * Loads config only for current settings section.
     * @return AppManagerConfig 
     */
    protected function loadConfigSection()
    {
        return AppManagerModule::config($this->getConfigSection());
    }
    
    /**
     * Gets name of the config section.
     * @return string 
     */
    protected function getConfigSection()
    {
        return '';
    }
    
    /**
     * @param array $data
     * @param array $options
     * @return CArrayDataProvider 
     */
    protected function createDataProvider($data, $options = array())
    {
        return new CArrayDataProvider($data, $options);
    }
}
?>
