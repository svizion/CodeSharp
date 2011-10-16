<?php

/**
 * @author Andy Walpole
 * @date 9/10/2011
 * 
 */

?>

<div id="wrapper" class="admin">

<header class="clearfix">

<h1>Pinhead CMS - CodeIgnitor Verson</h1>

</header><!-- End header -->

<div id="content" class="clearfix">

<section  id="column-one">

<h1>Admin section</h1>

<div id="admin-add-content-result">

</div><!-- end admin-add-content-result -->

<div id="admin-add-menu">


<?php

if (isset($_POST['submit'])) {

    if (isset($error)) {

        echo $error;

    }

    if (isset($success)) {

        echo $success;

    }

    echo validation_errors();

}


$attributes = array('id' => "addMenu", 'name' => "addMenu");

$form = form_open('admin_menu/menu_add', $attributes);

$form .= form_fieldset('Add a new menu item');

$form .= form_label('Name:', 'name-add');

$form .= form_input(array('name' => 'nameAdd', 'id' => 'name-add', 'maxlength' =>
    '40', 'type' => 'text', 'value' => set_value('nameAdd')));

$form .= form_label('URL:', 'url-add');

$form .= form_input(array('name' => 'urlAdd', 'id' => 'url-add', 'maxlength' =>
    '100', 'type' => 'text', 'value' => set_value('urlAdd')));

$form .= form_label('Publish', 'publish');

$form .= form_radio(array('name' => 'publishAdd', 'id' => 'publish', 'value' =>
    'YES', 'checked' => (isset($_POST["publishAdd"]) && $_POST["publishAdd"] ==
    "YES") ? 'checked' : ''));

$form .= form_label('Not publish', 'not-publish');

$form .= form_radio(array('name' => 'publishAdd', 'id' => 'not-publish', 'value' =>
    'NO', 'checked' => (isset($_POST["publishAdd"]) && $_POST["publishAdd"] == "NO") ?
    'checked' : ''));

$form .= form_submit("submit", "submit");

$form .= form_fieldset_close();

$form .= form_close();

echo $form;

?>

</div>

<h1>Add a new menu item, edit an existing one or reorder the menu as it appears to the public. <br>
Also decide whether you would like to include categories in the main public menu</h1>

<?php

if (isset($_POST['submitCat'])) {

    if (isset($success_failure)) {

        echo $success_failure;

    }


    echo validation_errors();

}


$attributes = array('id' => "addCategories", 'name' => "addCategories");

$form = form_open('admin_menu/add_categories_to_menu', $attributes);

$form .= form_fieldset('Add categories to the menus?');

$form .= form_label('Yes', 'yes-categories');

$form .= form_radio(array('name' => 'categoriesAdd', 'id' => 'yes-categories',
    'value' => 'YES', 'checked' => $cat_menu_result->okay == 1 || (isset($_POST['categoriesAdd']) &&
    $_POST['categoriesAdd'] == "YES") ? 'checked' : ''));

$form .= form_label('No', 'no-publish');

$form .= form_radio(array('name' => 'categoriesAdd', 'id' => 'no-categories',
    'value' => 'NO', 'checked' => $cat_menu_result->okay == 0 || (isset($_POST['categoriesAdd']) &&
    $_POST['categoriesAdd'] == "NO") ? 'checked' : ''));

$form .= form_submit("submitCat", "submit");

$form .= form_fieldset_close();

$form .= form_close();

echo $form;

?>

<h2>Rearrange the order of the main menu</h2>

<div id="menu-order-result">

<?php

if (isset($_POST['submitMenu'])) {

    if (isset($success_fail)) {

        echo $success_fail;

    }

}

?>

</div><!-- end menu-order-result -->

<form id="menu-order" name="menuOrder" method="post" action="<?php

echo base_url();

?>index.php/admin_menu/change_menu_order#menu-order-result">
<fieldset>
<legend><span>Change the number from 99 to 0. <br>The higher the number the higher it appears in the menu order</span></legend>

<?php

/**
 *  VALUE FOR ARRAY IN INPUT FORM NOT STICKING AFTER ERROR AND FORM RELOAD
 */

$a = 1;
$b = 1;

foreach ($admin_menu_order as $menuItem) {

    $menuInput = 'menu[' . $a++ . ']';

    $menuFormControl = 'menu' . $b++;

    $result = '<label for="' . $menuFormControl . '">' . $menuItem['name'] .
        '</label>';

    $result .= '<input type="text" id="' . $menuFormControl . '" name="' . 'menu[' .
        $a++ . ']' . '" value="';

    $result .= isset($_POST['menu[' . $a++ . ']']) ? $_POST['menu[' . $a++ . ']'] :
        $menuItem['number'];

    $result .= '" maxlength="2" />';

    $result .= '<input type="hidden" name="hidden_menu_id[' . $a++ . ']" value="' .
        $menuItem['menu_id'] . '">';

    $result .= '<input type="hidden" name="hidden_menu_name[' . $a++ . ']" value="' .
        $menuItem['name'] . '">';

    echo $result;

}

?>

<input name="submitMenu" value="submit" type="submit">
</fieldset>
</form>
<?php

foreach ($display_menu as $menu_page) {

    $id = $menu_page->id;

    // Set variables
    $submit = 'submit' . $id;
    $menu_name = 'menuName' . $id;
    $url = 'menuUrl' . $id;
    $publish = 'menuPublish' . $id;
    $orig_name = 'hidden' . '00' . $id;
    $delete = 'delete' . $id;
    $finalDelete = 'finalDelete' . $id;
    $id_item = 'hidden' . $id;
    $menu_number = 'menuOrder' . $id;

    // Create this block for the form action value
    echo '<div id="menu-block-result-' . $id . '">';

    if (isset($_POST["submit$id"])) {

        if (isset($success_error)) {

            echo $success_error;
            
            echo validation_errors();

        }// 

    }

    echo '</div>';

    $form = '<div id="menu-block-full-' . $id . '" class="full-block">';

    $form .= '<div id="menu-block-result' . $id . '">';

    $form .= '<div class="menuname">';

    $form .= $menu_page->name;

    $form .= '</div>';

    $form .= '<form id="admin-add-menu-';

    $form .= $id;

    $form .= '" ';

    $form .= 'name="adminAddMenu';

    $form .= $id;

    $form .= '" ';

    $form .= 'method="post" action="';

    $form .= base_url() . 'index.php/' . 'admin_menu/update_menu#menu-block-result-' .
        $id;

    $form .= '">';

    $form .= '<fieldset class="fieldset-hidden">';

    $form .= '<legend id="legend' . $id . '">Edit:' . $menu_page->name . '</legend>';

    // Username form field

    $form .= '<label for="' . $menu_name . '">Edit ' . 'Menu name:' . '</label>';

    $form .= '<input type="text" maxlength="40" name="' . $menu_name . '" id="' . $menu_name .
        '" value="';

    $form .= isset($_POST[$menu_name]) ? $_POST[$menu_name] : $menu_page->name;

    $form .= '" />';

    // URL

    $form .= '<label for="' . $url . '">Edit ' . 'URL:' . '</label>';

    $form .= '<input type="text" maxlength="40" name="' . $url . '" id="' . $url .
        '" value="';

    $form .= isset($_POST[$url]) ? $_POST[$url] : $menu_page->url;

    $form .= '" />';

    // Publication settings settings

    $form .= '<input type="radio" name="' . $publish . '" id="publishYes' . $id .
        '" value="YES" class="admin-top"';

    $form .= $menu_page->visible == 1 || (isset($_POST[$publish]) && $_POST[$publish] ==
        "YES") ? ' checked="checked" ' : null;

    $form .= '/>';

    $form .= '<label for="publishYes' . $id . '">Make publish</label>';

    $form .= '<input type="radio" name="' . $publish . '" id="publishNo' . $id .
        '" value="NO" ';

    $form .= $menu_page->visible == 0 || (isset($_POST[$publish]) && $_POST[$publish] ==
        "NO") ? ' checked="checked" ' : null;

    $form .= '/>';

    $form .= '<label for="publishNo' . $id . '">Don\'t make public</label>';

    // Delete button

    $form .= '<input type="submit" name="delete';

    $form .= $id . '" value="delete" />';

    // Submit button

    $form .= '<input type="submit" name="submit';

    $form .= $id . '" value="submit" />';

    // Hidden buttons

    $form .= '<input type="hidden" name="hidden';

    $form .= $id . '" value="';

    $form .= $id;

    $form .= '" />';

    $form .= '<input type="hidden" name="';

    $form .= $orig_name . '" value="';

    $form .= $menu_page->name;

    $form .= '" />';

    $form .= '</fieldset>';

    $form .= '</form>';

    $form .= '</div>';

    $form .= '</div>';

    echo $form;


}

?>

</section>
<!-- End column one -->

<section  id="column-two">

</section><!-- End column two -->

<section id="column-three">

</section><!-- End column three -->

</div><!-- End content -->