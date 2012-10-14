<?php
$this->breadcrumbs=array(
    $this->getTitle(),
);
?>
<?php 
$confirm = 'function(){if(!confirm(' 
         . CJavaScript::encode(AppManagerModule::t('Are you sure?'))
         . ')) return false;}'; 
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $list,
    'template' => "<h3>{$this->getTitle()}</h3>{summary}\n{items}\n{pager}",
    'selectableRows' => 0,
    'columns' => array(
        array(
            'name'   => 'title',
            'header' => AppManagerModule::t('Title'),
        ),
        array(
            'name'   => 'summary',
            'header' => AppManagerModule::t('Description'),
        ),
        array(            
            'class'    =>'CButtonColumn',
            'template' => '{activate} {deactivate} {view} {update} {delete}',
            'buttons' => array(
                'activate' => array(
                    'label'   => AppManagerModule::t('activate'),
                    'url'     => 'array("activate", "id" => $data->id)',
                    'visible' => '$data->canActivate()',
                    'click'   => $confirm,
                ),
                'deactivate' => array(
                    'label'   => AppManagerModule::t('deactivate'),
                    'url'     => 'array("deactivate", "id" => $data->id)',
                    'visible' => '$data->canDeactivate()',
                    'click'   => $confirm,
                ),
                'update' => array(
                    'visible' => '$data->canUpdate()',
                ),
                'delete' => array(
                    'visible' => '$data->canDelete()',
                ),
            ),
       ),
    ),
)); ?>