<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $entity->childrenProvider,
    'template' => "<h3>{$entity->title}</h3>{summary}\n{items}\n{pager}",
    'selectableRows' => 0,
    'columns' => array(
        array(
            'name'   => 'title',
            'header' => AppManagerModule::t('Title'),
        ),
        array(
            'name'   => 'name',
            'header' => AppManagerModule::t('Name'),
        ),
        array(
            'name'   => 'summary',
            'header' => AppManagerModule::t('Description'),
        ),
        array(            
            'class'    =>'CButtonColumn',
            'template' => '{activate} {deactivate} {view} {update}',
            'buttons' => array(
                'activate' => array(
                    'label'   => AppManagerModule::t('activate'),
                    'url'     => 'array("activate", "id" => $data->id)',
                    'visible' => '$data->canActivate()',
                    'options' => array('class' => 'confirm'),
                ),
                'deactivate' => array(
                    'label'   => AppManagerModule::t('deactivate'),
                    'url'     => 'array("deactivate", "id" => $data->id)',
                    'visible' => '$data->canDeactivate()',
                    'options' => array('class' => 'confirm'),
                ),
                'update' => array(
                    'visible' => '$data->canUpdate()',
                ),
            ),
       ),
    ),
)); ?>