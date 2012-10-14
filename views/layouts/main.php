<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <?php $module = $this->module; ?>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo $module->getCssUrl('screen'); ?>" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo $module->getCssUrl('print'); ?>" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo $module->getCssUrl('ie'); ?>" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo $module->getCssUrl('main'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo $module->getCssUrl('form'); ?>" />

    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo $this->module->name; ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array(
                    'label'  => AppManagerModule::t('App'), 
                    'url'    => array('/appManager/app'), 
                    'active' => $this->isId('app'),
                ),
				array(
                    'label'  => AppManagerModule::t('Core'), 
                    'url'    => array('/appManager/core'), 
                    'active' => $this->isId('core'),
                ),
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'homeLink' => CHtml::link(AppManagerModule::t('Home'), array('/appManager')),
			'links'    => $this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

    <?php echo $content; ?>
      
	<div class="clear"></div>
</div><!-- page -->

</body>
</html>
