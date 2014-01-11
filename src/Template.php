<?php
/**
 * @author Thomas Peikert
 */
/**
 * Class Template
 * handles template changes
 */
class Template {

    /** @var string path to html file */
    private $template;

    private $extras = array();

    // login view
    const VIEW_LOGIN = 'login';

    // index view showing all of my presentations
    const VIEW_INDEX = 'index';

    // editor view, editing one presentation
    const VIEW_EDITOR = 'editor';

    // showing all active presentations currently in progress
    const VIEW_ACTIVE = 'active';

    // viewing one presentation
    const VIEW_SHOW = 'show';

    // viewing one presentation
    const VIEW_SPECTATE = 'spectate';

    public function __construct($view = self::VIEW_INDEX)
    {
        // if the user isn't logged in, only the loginView and the spectate view are permitted
        if(!SessionManager::instance()->isActive())
        {
            switch($view)
            {
                case self::VIEW_LOGIN:
                    $this->template = 'templates/loginTemplate.html';
                    break;
                case self::VIEW_SPECTATE:
                    $this->template = 'templates/spectateTemplate.php';
                    break;
                default:
                    $this->template = 'templates/loginTemplate.html';
                    break;
            }
            return;
        }

        switch($view)
        {
            case self::VIEW_LOGIN:
                $this->template = 'templates/loginTemplate.html';
                break;
            case self::VIEW_INDEX:
                $this->template = 'templates/indexTemplate.php';
                break;
            case self::VIEW_EDITOR:
                $this->template = 'templates/editorTemplate.php';
                break;
            case self::VIEW_ACTIVE:
                $this->template = 'templates/activeTemplate.php';
                break;
            case self::VIEW_SHOW:
                $this->template = 'templates/showTemplate.php';
                break;
            case self::VIEW_SPECTATE:
                $this->template = 'templates/spectateTemplate.php';
                break;
            default:
                break;
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        // to prevent direct output, use buffer
        ob_start();
        include $this->template;
        return ob_get_clean();

    }

    /**
     * post extra info for template
     * @param $key
     * @return mixed
     */
    public function getExtra($key)
    {
        return $this->extras[$key];
    }

    /**
     * give extra info to template
     * @param $key
     * @param $value
     */
    public function putExtra($key, $value)
    {
        $this->extras[$key] = $value;
    }

}