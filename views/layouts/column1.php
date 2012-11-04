<?php $this->beginContent('/layouts/main'); ?>
    <div class="menu-sections">
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'homeLink' => CHtml::link(AppManagerModule::t('Home'), array('/appManager')),
                'links' => $this->breadcrumbs,
            )); ?><!-- breadcrumbs -->
        <?php endif?>
		<?php $this->widget('zii.widgets.CMenu', array('items'=>$this->getMenu())); ?>
	</div>	
    
    <div id="content">
        <?php $this->widget('AmWidgets.AmFlash'); ?>
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>