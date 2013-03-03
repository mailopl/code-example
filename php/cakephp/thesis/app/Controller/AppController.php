<?php
App::uses('Controller', 'Controller');
App::uses('Memcached', 'Vendor');
/**
 * Application Controller
 */
class AppController extends Controller
{
    public $theme = 'TwitterBootstrap';

    public $components = array(
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email')
                )
            )
        ),
        'Session',
        'Email'
    );

    public function beforeRender()
    {
        if ($this->name == 'CakeError') {
            if ($this->params->params['controller'] == 'rest') {
                $this->layout = 'ajax';
            } else {
                $this->layout = 'default';
            }
        }
    }

    /**
     * Memcache initialization
     */
    public function beforeFilter()
    {
        $this->set('currentUser', $this->Auth->user());

        $this->Email->from = '';
        $this->Email->delivery = 'smtp';
        $this->Email->smtpOptions = array(
            'port' => '',
            'username' => '',
            'password' => '',
            'host' => '',
            'timeout' => '',
        );

        $lang = 'eng';

        /*if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            if (function_exists('locale_accept_from_http')) {
                $lang = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            }
        }

        if (isset($this->params->query['lang'])) {
            $lang = $this->params->query['lang'];
        }*/

        $lang = $lang == 'en' ? 'eng' : $lang;
        //$lang = $lang == 'pl' ? 'pol' : $lang;


        Configure::write('Config.language', $lang);
        CakeSession::write('Config.language', $lang);

        $path = '..' . DS . 'View' . DS . $lang . DS . $this->viewPath;
        if ($lang && file_exists($path)) {
            $this->viewPath = $path;
        }
    }

    /**
     * Allows to separate REST api options, from any other passed parameters
     * ex. ?limit=5&unknownparameter=5 will result in limit => 5
     *
     * @return array
     */
    protected function _separateOptions()
    {
        return array_intersect_key($this->request->query, $this->acceptedOptions);

    }
}
