<?php
namespace Timeline\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Timeline\Model\Timeline;          
use Timeline\Form\TimelineForm;

class TimelineController extends AbstractActionController
{
    protected $timelineTable;
    
    public function indexAction()
    {
        return new ViewModel(array(
            'timelines' => $this->getTimelineTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new TimelineForm();
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $timeline = new Timeline();
            $form->setInputFilter($timeline->getInputFilter());
            $form->setData($request->getPost());
        
            if ($form->isValid()) {
                $timeline->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($timeline);
        
                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }
        
        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $timeline = $this->getAlbumTable()->getAlbum($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'index'
            ));
        }
        
        $form  = new TimelineForm();
        $form->bind($timeline);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($timeline->getInputFilter());
            $form->setData($request->getPost());
        
            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($timeline);
        
                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
        
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }
        
            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }
        
        return array(
            'id'    => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }
    
    // module/Album/src/Album/Controller/AlbumController.php:
    public function getTimelineTable()
    {
        if (!$this->timelineTable) {
            $sm = $this->getServiceLocator();
            $this->timelineTable = $sm->get('Timeline\Model\TimelineTable');
        }
        return $this->timelineTable;
    }
}