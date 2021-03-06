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
 * Admin_Edit_Content
 *
 * @package		CodeSharp
 * @subpackage	Application
 * @category	Controllers
 * @author		Andy Walpole
 * 
 */

class Admin_Edit_Content extends CI_Controller {

    function __constructor() {

        parent::__construct();

    }


    function _remap($method) {

        switch ($method) {

            case 'edit-node':
                $this->edit_node();
                break;

            case 'delete-content':
                $this->delete_content();
                break;

            case 'submit':
                $this->submit();
                break;

            default:
                $this->index();
                break;

        }

    }
    
    // --------------------------------------------------------------------

    /**
     * add_theme function
     * Adds category menu and template to all pages
     * Also adds list of all users for every page
     *
     * @access	private
     * @param	string
     * @return	string
     */


    // universal to all functions
    private function add_theme($array = "") {

        $data = $array;

        if ($this->uri->segment(2) != "index") { // <- this is necessary to prevent an odd clash with pagination

            $data['edit'] = $this->content_model->get_content_by_id($this->uri->segment(3));

        }

        $data['full_users'] = $this->author_model->get_users_title_mutli();

        $data['full_cats'] = $this->category_model->get_cat_title_mutli();

        $data['content'] = "admin/edit_content_view";

        $this->load->view("admin/includes/template.php", $data);

    }


    public function index() {

        $data = array();

        $config['base_url'] = site_url('admin-edit-content/index');

        // place below into its own model
        $config['total_rows'] = $this->content_model->find_content_rows($visible = false);

        $config['per_page'] = 5;

        $config['num_links'] = 3;

        $this->pagination->initialize($config);

        $data['cats'] = $this->user_model->find_all_users();

        $data['nodes_all'] = $this->content_model->get_all_content_edit($config['per_page'],
            $this->uri->segment(3));

        $this->add_theme($data);

    }


    public function edit_node() {

        $data = array();

        if (ctype_digit($this->uri->segment(3))) {

            $node = $this->uri->segment(3);

        } else {

            /**
             * IF NON DIGIT OR EMPTY A 404 MESSAGE IS THROW
             */

            get_404();

        }

        $this->add_theme($data);


    }
    
        // --------------------------------------------------------------------

    /**
     * isValidDateTime function
     * Checks to make sure all dates entered on the edit node form conform to MySQL datatime format
     *
     * @access	private
     * @param	string
     * @return	string
     */


    public function isValidDateTime($datetime) {

        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/",
            $datetime, $matches)) {

            if (checkdate($matches[2], $matches[3], $matches[1])) {

                return true;

            }

        } else {

            $this->form_validation->set_message('isValidDateTime',
                'The date format is incorrent. Please makes sure it is exactly the same format as: ' .
                date("Y-m-d H:i:s"));

            return false;

        }

    } // End form_validation_isValidDateTime() method
    
    // --------------------------------------------------------------------

    /**
     * submit function
     * Submission for the edit node page
     *
     * @access	public
     * @param	array
     * @return	string
     */

    // see routies.php -> $route['admin_edit_content/edit_node/:num/submit'] = "admin_edit_content/submit";
    public function submit() {
        
  

        $data = array();

        $file_result = null;

        if ($this->input->post("submit")) {
            
            /**
         * validation rule to be found in config -> form_validation.php
         */

            if ($this->input->post('metaDescription')) {

                $this->form_validation->set_rules('metaDescription', 'Meta Description',
                    'trim|max_length[255]');

            } else {
                
                $meta_description = null;
                
                
            }
            
            if ($this->input->post('metaKeywords')) {

                $this->form_validation->set_rules('metaKeywrods', 'Meta Keywords',
                    'trim|max_length[255]');

            } else {
                
                $meta_keywords = null;
                
            }

            $result = $this->form_validation->run("editnode");

            if ($_FILES["file_upload"]['name'] !== "") {

                $file_result = $this->image_model->upload_image();

            }

            if (!$result || $file_result !== null) {

                // form validation NOT okay
                $data['success_error'] = "<p>You have problems with your form:</p>";

                $data['file_error'] = $file_result;

            } else {


                // If file upload is okay then make sure that file is
                // 1. Resize and placed into thumbnail folder
                // 2. New image row is created
                // 3. Old image details are deleted from database and file system

                if ($_FILES["file_upload"]['name'] !== "") {

                    $image_result = $this->image_model->update_image($this->input->post("orig_image"));

                } else {

                    $image_result = $this->input->post("orig_name");

                }

                // form validation okay

                // create database update here
                
                if ($this->input->post('metaDescription')) {
                    
                    $meta_description = $this->input->post('metaDescription');
                    
                    }
                    
                    
                   if ($this->input->post('metaKeywords')) {
                    
                    $meta_keywords = $this->input->post('metaKeywords');
                    
                    }
                

                if ($this->content_model->update_content($this->input->post('title'), $this->
                    input->post('select'), $this->input->post('contentAuthor'), $image_result, $this->
                    input->post('date'), $this->input->post('body'), $this->input->post('publish'),
                    $meta_description, $meta_keywords, $this->uri->segment(3))) {

                    $data['success_error'] = '<p>You have successfully edited the content item</p>';

                }

            }

        }


        if ($this->input->post("detete")) {

            $data['delete_content'] = 1;

        }

        $this->add_theme($data);
    }
    
     // --------------------------------------------------------------------

    /**
     * delete_content
     * Deletes node
     *
     * @access	public
     * @param	string
     * @return	string
     */

    public function delete_content() {

        $data = array();

        if ($this->content_model->delete_content($this->input->post("delete_this"))) {
            
            ob_start();
            header('Location: ' . site_url() . 'admin-edit-content/');
            exit;
            ob_end_clean();

        }

        $this->add_theme($data);

    }

}
