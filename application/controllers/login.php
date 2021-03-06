<?php

/**
 * CodeSharp
 *
 * A CMS based on CodeIgniter
 *
 * @package		CodeSharp
 * @author		Andy Walpole (unless stated to the contrary)
 * @copyright	Andy Walpole (unless stated to the contrary)
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		https://github.com/TCotton/CodeSharp
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Login
 *
 * @package		CodeSharp
 * @subpackage	Application
 * @category	Controllers
 * @author		Andy Walpole
 * 
 */

class Login extends CI_Controller {

    function __construct() {

        parent::__construct();

    }

    // --------------------------------------------------------------------

    /**
     * add_theme function
     * Adds user details and template to all pages
     *
     * @access	private
     * @param	string
     * @return	string
     */


    private function add_theme($array) {

        $data = $array;

        $data['content'] = "admin/login_page";

        $this->load->view("admin/includes/template.php", $data);

    }

    public function index() {

        $data = array();

        $this->add_theme($data);

    }

    // --------------------------------------------------------------------

    /**
     * set_logged_infunction
     * AIf user is logged in then set the user id to the cookie id and set logged_in parameter to true
     *
     * @access	public
     * @param	string
     * @return	string
     */


    public function set_logged_in() {

        if (isset($_COOKIE['codesharp'])) {

            $this->user_id = $_COOKIE['codesharp'];
            $this->logged_in = true;

        } else {

            unset($this->user_id);
            $this->logged_in = false;

        }

    } // end method check_logged_in


    // --------------------------------------------------------------------

    /**
     * letmein function
     * Login form for the administration area
     *
     * @access	public
     * @param	string
     * @return	string
     */


    public function letmein() {

        $data = array();

        /**
         * validation rule to be found in config -> form_validation.php
         */

        if ($this->form_validation->run("login") === false) {

            // if problems then log in here

            $data['success_error'] = "<p>Sorry there are problems with the form:</p>";

        } else {

            // if not problems then log in
            $data['success_error'] = $this->user_model->admin_login($this->input->post("username"),
                $this->input->post("password"));

            if (is_object($data['success_error'])) {

                /**
                 * If the login in is successful then the return value will always be an array
                 */

                $data = array('username' => $data['success_error']->email, 'is_logged_in' => true, );

                /**
                 * Now create cookie and redirect to the main admin page
                 */

                $this->session->set_userdata($data);

                redirect("admin");


            }


        }

        $this->add_theme($data);

    }


    public function forgotten_userdetails() {

        $data = array();

        $data['new_userdetails'] = true;

        $this->add_theme($data);

    }

    // --------------------------------------------------------------------

    /**
     * valdidate_forgotten_userdetails function
     * Allows the user to reset their password and user details
     *
     * @access	public
     * @param	string
     * @return	string
     */


    public function valdidate_forgotten_userdetails() {

        $email = "";
        $username = "";

        $data = array();

        $this->form_validation->set_rules('username', 'username',
            'trim|required|max_length[50]');

        if ($this->form_validation->run() === false) {

            // if problems then log in here

            $data['success_error'] = "<p>Sorry there are problems with the form:</p>";

        } else {

            // if everything okay then go here

            if (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {

                // It is a username
                $username = $_POST['username'];

            } else {

                $email = $_POST['username'];
                // If it is an email address

            }

            $data['success_error'] = $this->user_model->new_password($username, $email);


        }


        if (isset($_POST['username']) && !filter_var($_POST['username'],
            FILTER_VALIDATE_EMAIL)) {

            // It is not an email address
            $username = $_POST['username'];

        } else {

            $email = $_POST['username'];
            // If it is an email address

        }

        $data['new_userdetails'] = true;

        $this->add_theme($data);

    }

    // --------------------------------------------------------------------

    /**
     * letmeout function
     * Logout of the admin area and destroy the cookie
     *on/controllers/login.php
     * @access	public
     * @param	string
     * @return	string
     */


    public function letmeout() {

        $this->session->unset_userdata('is_logged_in');

        redirect("/");

    }


    //http://localhost/CodeSharp/new_password/login/561451/1
    // --------------------------------------------------------------------

    /**
     * new_password function
     * Sets new password after user clicks on link in email
     *
     * @access	public
     * @param	string
     * @return	string
     */


    public function new_password() {

        $data = array();

        if ($this->uri->segment(4) == 1) {

            $data['new_password'] = $this->user_model->set_new_password(urldecode($this->uri->segment(3)));

        } else {

            // display 404 message here

            $this->output->set_status_header('404');

            $data['error_404'] =
                "<p>There seems to be an issue with you user details. Please contact administration for help.</p>";

        }

        $this->add_theme($data);

    }


}
