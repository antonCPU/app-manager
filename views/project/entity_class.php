<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $entity,
	'attributes' => array(
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
            'type'  => 'ntext',
		),
		array(
			'label' => AppManagerModule::t('Version'),
			'name'  => 'version',
		),
		array(
			'label' => AppManagerModule::t('Link'),
			'type'  => 'raw',
			'value'  => CHtml::link($entity->link, $entity->link, array(
				'target' => '_blank',
			)),
		),
	),
)); ?>

<div class="entity-details">
	<?php echo $entity->summary; ?> 
	<br />
	<br />
	<?php echo $entity->description; ?>
</div>
