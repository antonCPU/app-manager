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
				'visible' => (bool)$entity->asa('config') || (bool)$entity->asa('options'),
				'linkOptions' => array(
					'id' => 'tab-update',
				),
			),
			array(
				'label'   => AppManagerModule::t('View'),
				'url'	  => '#',
				'visible' => (bool)$entity->asa('class'),
				'linkOptions' => array(
					'id' => 'tab-view',
				),
			),
		),
	)); ?>
</div>
<div id="entity-update" class="tab">
    <?php $this->renderPartial('entity_update', array('entity' => $entity)); ?>
</div>
<?php if ($entity->asa('class')): ?>
	<div id="entity-view" class="tab" style="display:none;">
		<?php $this->renderPartial('entity_view', array('entity' => $entity)); ?>
	</div>
<?php endif; ?>
<script type="text/javascript">

$('.entity-tabs a').click(function() {
	var id = '#entity-' + $(this).attr('id').split('-')[1];
	$('.tab:not(' + id + ')').hide();
	$(id).show();
});

</script>
