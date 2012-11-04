<?php
Yii::import('zii.widgets.CBreadcrumbs');

class AmEntityBreadcrumbs extends CBreadcrumbs
{
    public function init()
    {
        $this->links = $this->generate();
    }
    
    protected function generate()
    {
        $controller = $this->controller;
        $entity = $controller->getEntity();
        if ('update' === $controller->action->id) {
            $breadcrumbs[] = AppManagerModule::t('Update');
            $breadcrumbs[$entity->getTitle()] = array('view', 'id' => $entity->getId());
        } else {
            $breadcrumbs[] = $entity->getTitle();
        }
        $model = $controller->getModel();
        while ($entity = $entity->getParent()) {
            if ($entity->getId() === $model->getId()) {
                $url = array($controller->defaultAction);
            } else {
                $action = $entity->canList() ? 'list' : 'view';
                $url = array($action, 'id' => $entity->getId());
            }
            $breadcrumbs[$entity->getTitle()] = $url;
        }
        return array_reverse($breadcrumbs);
    }
}
