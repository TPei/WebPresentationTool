<?php
/**
 * @author Thomas Peikert
 */

namespace handler;
use SessionManager;
use Template;

class ViewHandler extends AjaxHandler
{

    /**
     * change the view
     */
    public function changeViewAction()
    {
        // change view to e.g. edit
        $view = $this->ajaxData('view');
        $template = new Template($view);

        if($view == Template::VIEW_EDITOR)
        {
            $id = $this->ajaxData('id');

            $presentation = SessionManager::instance()->getUser()->getPresentation($id);
            $template->putExtra('presentation', $presentation);

            $slides = $presentation->getSlides();
            $slide = reset($slides);
            $slideId = $slide->getId();
            SessionManager::instance()->setActiveSlideId($slideId);

            echo json_encode(array(
                'html' => $template->getContent(),
                'title' => $presentation->getTitle()
            ));
        }
        else if($view == Template::VIEW_SHOW)
        {
            $id = SessionManager::instance()->getActivePresentationId();
            $presentation = SessionManager::instance()->getUser()->getPresentation($id);
            $template->putExtra('presentation', $presentation);

            echo json_encode(array(
                'html' => $template->getContent(),
                'title' => $presentation->getTitle()
            ));
        }
        else
        {
            echo json_encode(array(
                'html' => $template->getContent()
            ));
        }
    }

} 