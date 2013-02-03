<?php
echo "array(\n"; 
foreach ($options as $name => $option)
{
    $this->renderIndent($level);
    if (!is_numeric($name))
        echo "'$name' => ";
 
    $this->renderOptions($option, $level);
    echo ",\n";
}
$this->renderIndent($level - 1);
echo ")";