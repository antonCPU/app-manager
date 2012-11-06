<div class="view-title">
    <h1><?php echo $entity->title; ?></h1>
    <?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array(
                    'label'  => AppManagerModule::t('Update'), 
                    'url'    => array('update', 'id' => $entity->id), 
                    'visible' => $entity->canUpdate(),
                ),
                array(
                    'label'  => AppManagerModule::t('Activate'), 
                    'url'    => array('activate', 'id' => $entity->id), 
                    'visible' => $entity->canActivate(),
                ),
                array(
                    'label'  => AppManagerModule::t('Deactivate'), 
                    'url'    => array('deactivate', 'id' => $entity->id), 
                    'visible' => $entity->canDeactivate(),
                    'itemOptions' => array('class' => 'confirm'),
                ),
            )
    )); ?>
</div>
<?php $this->widget('zii.widgets.jui.CJuiTabs', array(
    'htmlOptions' => array('class' => 'entity-details'),
    'tabs'=>array(
        AppManagerModule::t('Details') => $this->widget('zii.widgets.CDetailView', array(
            'data'=>$entity,
            'attributes'=>array(
                array(
                    'label' => AppManagerModule::t('Name'),
                    'name'  => 'name',
                ),
                array(
                    'label' => AppManagerModule::t('Class'),
                    'name'  => 'className',
                ),
                array(
                    'label' => AppManagerModule::t('Location'),
                    'name'  => 'fileName',
                ),
                array(
                    'label' => AppManagerModule::t('Author'),
                    'name'  => 'author',
                ),
                array(
                    'label' => AppManagerModule::t('Version'),
                    'name'  => 'version',
                ),
                array(
                    'label' => AppManagerModule::t('Link'),
                    'type'  => 'url',
                    'name'  => 'link',
                ),
            ),
        ), true),
        AppManagerModule::t('Description') => $entity->summary . '<br /><br />' . $entity->description,
      ),
));?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $entity->options->provider,
        'template' => "<h3>". AppManagerModule::t('Options') . "</h3>{summary}\n{items}\n{pager}",
        'selectableRows' => 0,
        'columns' => array(
            array(
                'name'   => 'name',
                'header' => AppManagerModule::t('Name'),
            ),
            array(
                'name'   => 'textValue',
                'header' => AppManagerModule::t('Value'),
                'htmlOptions' => array('class' => 'code'),
            ),
            array(
                'name'   => 'desc',
                'header' => AppManagerModule::t('Description'),
            ),
            array(
                'name'   => 'type',
                'header' => AppManagerModule::t('Type'),
            ),
        ),
)); ?>

