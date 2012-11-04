<?php $this->beginContent('/layouts/main'); ?>
    <div class="menu-sections">
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('AmWidgets.AmEntityBreadcrumbs', array(
                'homeLink' => CHtml::link(AppManagerModule::t('Home'), array('/appManager')),
            )); ?><!-- breadcrumbs -->
        <?php endif?>
		<?php $this->widget('zii.widgets.CMenu', array('items'=>$this->getMenu())); ?>
	</div>	
    
    <div id="content">
        <?php $this->widget('AmWidgets.AmFlash'); ?>
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>