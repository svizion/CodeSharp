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
 * Admin_Menu
 *
 * @package		CodeSharp
 * @subpackage	Application
 * @category	Controllers
 * @author		Andy Walpole
 * 
 */

class Admin_Menu extends CI_Controller {


    function __constructor() {

        parent::__construct();

    }

    function _remap($method) {

        switch ($method) {

            case 'duplicate-menu-name':
                $this->duplicate_menu_name();
                break;

            case 'update-menu':
                $this->update_menu();
                break;

            case 'delete-menu':
                $this->delete_menu();
                break;

            case 'change-menu-order':
                $this->change_menu_order();
                break;

            case 'add-categories-to-menu':
                $this->add_categories_to_menu();
                break;

            case 'menu-add':
                $this->menu_add();
                break;

            default:
                $this->index();
                break;

        }

    }

    // --------------------------------------------------------------------

    /**
     * add_theme function
     * Adds menu details  and template to all pages
     *
     * @access	private
     * @param	string
     * @return	string
     */

    // universal to all functions
    private function add_theme($array) {

        $data = $array;

        $data['content'] = "admin/admin_menu_view";

        $data['display_menu'] = $this->menu_model->display_menu_admin();

        $data['admin_menu_order'] = $this->menu_model->menu_order();

        $data['cat_menu_result'] = $this->menu_model->fetch_cat_menu();

        $this->load->view("admin/includes/template.php", $data);

    }


    public function index() {

        $data = array();

        $this->add_theme($data);

    }

    // --------------------------------------------------------------------

    /**
     * duplicate_menu_name function
     * When a new menu item is created make sure that the menu name is unique
     *
     * @access	public
     * @param	string
     * @return	string
     */

    public function duplicate_menu_name($name) {

        $query = $this->menu_model->display_menu();

        foreach ($query as $row) {

            if ($row->name == $name) {

                $this->form_validation->set_message('duplicate_menu_name',
                    "The %s field already exists in the database. Please chose a new name");

                return false;

            }

        }

    }

    // --------------------------------------------------------------------

    /**
     * update_menu function
     * Form submission for the edit menu forms
     *
     * @access	public
     * @param	array
     * @return	string
     */

    public function update_menu() {

        $data = array();

        // if form is posted then process validation
        if (!is_null($_POST)) {

            // the rewrite keys in the correct order
            $newkeys = array('one', 'two', 'three', 'four', 'five', 'six');

            // change the associative array of the form results - VALUES
            $new_form = array_key_change($_POST, $newkeys);

            // change the associative array forms results - KEYS
            $array_keys = array_key_change(array_keys($_POST), $newkeys);

            $this->form_validation->set_rules($array_keys['one'], 'menu name',
                'trim|required|max_length[40]');

            $this->form_validation->set_rules($array_keys['two'], 'menu url',
                'trim|max_length[40]');

            $this->form_validation->set_rules($array_keys['three'], 'publish',
                'trim|required');

            if ($this->form_validation->run() !== false) {

                if ($this->menu_model->update_menu($new_form['one'], $new_form['two'], $new_form['three'],
                    $new_form['five'])) {

                    $data['success_error'] = "<p>You have successfully submitted the form</p>";

                }

            } else {

                $data['success_error'] = "<p>There have been problems with the form:</p>";

            }

        } // end !is_null($_POST)

        $this->add_theme($data);
    }

    // --------------------------------------------------------------------

    /**
     * uchange_menu_order( function
     * Changes the menu order on the public pages
     *
     * @access	public
     * @param	array
     * @return	string
     */


    public function change_menu_order() {

        $data = array();

        $error = null;

        // Make sure validation only happens after from has been submitted
        if (!empty($_POST)) {

            // user array_map to create multidimensional arrays for the form result
            $array = array_map(null, $_POST['hidden_menu_id'], $_POST['hidden_menu_name'], $_POST['menu']);

            foreach ($array as $row) {

                // This should be made into a PHP regex
                if ($row[2] === "" || !ctype_digit($row[2]) || strlen($row[2]) > 2) {

                    $error = 1;
                    break;

                } // end foreach loop

            } // end isset


            if ($error === null) {

                // Form has no validation problems

                if ($this->menu_model->update_menu_order($array) > 0) {

                    $data['success_fail'] = '<p>You have updated the form</p>';

                }

            } else {

                // Form has validation problems

                $data['success_fail'] = "<p>There have been some problems with the form</p>";
                $data['success_fail'] .=
                    '<p>Please make sure the value is a one or two digit number</p>';

            }


        } // end if post

        $this->add_theme($data);

    }

    // --------------------------------------------------------------------

    /**
     * add_categories_to_menu function
     * Adds categories to the public menu
     *
     * @access	public
     * @param	string
     * @return	string
     */


    public function add_categories_to_menu() {

        $data = array();

        if ($this->menu_model->add_categories_to_menu()) {

            $data['success_failure'] =
                "<p>You have successfully added the categories to the menu items</p>";

        }

        $this->add_theme($data);

    }

    /**
     * menu_add function
     * Creates a menu, validation, database processing
     *
     * @access	public
     * @param	string
     * @return	string
     */

    public function menu_add() {

        $data = array();

        /**
         * validation rule to be found in config -> form_validation.php
         */

        if ($this->form_validation->run("addmenu")) {

            // successful

            if ($this->menu_model->insert_menu($this->input->post('nameAdd'), $this->input->
                post('urlAdd'), $this->input->post('publishAdd'))) {

                $data['success'] = "<p>You have successfully create a new menu item</p>";

            }

        } else {

            // error

            $data['data'] = "<p>There have been some problems with your form submission: </p>";

        }

        $this->add_theme($data);

    }

    /**
     * delete_menu function
     * Deletes a menu item
     *
     * @access	public
     * @param	string
     * @return	string
     */


    public function delete_menu() {

        $data = array();

        if ($this->menu_model->delete_menu($this->input->post("delete_this"))) {

            $data['success'] = "<p>You have successfully deleted the menu item</p>";

        }

        $this->add_theme($data);

    }

}
