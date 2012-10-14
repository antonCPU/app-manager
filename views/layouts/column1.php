<?php $this->beginContent('/layouts/main'); ?>
    <div>
		<?php $this->widget('zii.widgets.CMenu', array('items'=>$this->getMenu())); ?>
	</div>
    <?php echo $content; ?>
<?php $this->endContent(); ?>
