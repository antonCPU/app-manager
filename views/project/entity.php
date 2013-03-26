<div class="view-title">
	<h1><?php echo $entity->title; ?></h1>
	<?php $this->widget('zii.widgets.CMenu',array(
		'htmlOptions' => array(
			'class' => 'entity-tabs',
		),
		'items'=>array(
			array(
				'label'	  => AppManagerModule::t('Update'), 
				'url'	  => '#',
				'visible' => (bool)$entity->asa('config'),
				'linkOptions' => array(
					'id' => 'tab-config',
				),
			),
			array(
				'label'   => AppManagerModule::t('View'),
				'url'	  => '#',
				'visible' => (bool)$entity->asa('class'),
				'linkOptions' => array(
					'id' => 'tab-class',
				),
			),
		),
	)); ?>
</div>
<?php if ($entity->asa('config')): ?>
	<div id="entity-config" class="tab">
		<?php $this->renderPartial('entity_config', array('entity' => $entity)); ?>
	</div>
<?php endif; ?>
<?php if ($entity->asa('class')): ?>
	<div id="entity-class" class="tab" style="display:none;">
		<?php $this->renderPartial('entity_class', array('entity' => $entity)); ?>
	</div>
<?php endif; ?>
<script type="text/javascript">

$('.entity-tabs a').click(function() {
	var id = '#entity-' + $(this).attr('id').split('-')[1];
	$('.tab:not(' + id + ')').hide();
	$(id).show();
});

</script>
