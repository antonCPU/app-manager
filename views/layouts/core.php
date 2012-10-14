<?php $this->beginContent('/layouts/main'); ?>
    <div>
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
                array(
                    'label'  => AppManagerModule::t('Components'), 
                    'url'    => array('components'), 
                ),
				array(
                    'label'  => AppManagerModule::t('Modules'), 
                    'url'    => array('modules'), 
                ),
			),
		)); ?>
	</div>

    <?php echo $content; ?>
<?php $this->endContent(); ?>