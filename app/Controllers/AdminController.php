<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class AdminController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['aio','form'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;
    protected $template;

    protected $settings;

	protected $user;

	protected $session;

    protected $uri;
    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        $this->settings = new \Config\Setting();
		$this->template = service('template');
        $this->user = service('user');
		$this->session = service('session');
		$this->uri = service('uri');

        $this->template->set_theme('admin');
		$this->template->set('header',true);
        $this->template->set('site_name',isset($this->settings->config_site_title)?$this->settings->config_site_title:'Login');

        if (isset($this->settings->config_site_logo) && is_file(DIR_UPLOAD . $this->settings->config_site_logo)) {
            $logo = base_url('uploads') . '/' . $this->settings->config_site_logo;
        } else {
            $logo = '';
        }
        $this->template->set('logo',$logo);

        $ckfinderdata = [
			'root'  	=> WRITEPATH,
			'baseUrl'   => base_url('writable/uploads')
		];
		$_SESSION['ckfinder']=$ckfinderdata;

    }
}
