<?php

/**
 * @author Andy Walpole
 * @date 26/9/2011
 * 
 */

?><div id="wrapper">

<header class="clearfix">

<h1><?php

echo SITENAME;

?></h1>

</header><!-- End header -->

<div id="content" class="clearfix">

<section id="column-one">


<?php

/**
 * BELOW IS FOR DISPLAYING THE MENU
 */

?>

<menu>
<ul>
<?php
echo main_menu($menu);
?>

</ul>
</menu>

</section>
<!-- End column one -->

<section id="column-two">





<?php

// Only display content list if "content" is in URI
// This is for the entire node list
if (isset($records)):

?>

<?php

    foreach ($records as $rows):

?>

<article class="summary">

<h2><?php

        echo html_special($rows->title);

?></h2>

<time class="summary-date" datetime="<?php

        // Find a CodeIgnitor-friendly way of displaying date as below
        echo strftime("%Y-%m-%d", strtotime($rows->date));

?>"><?php

        // Find a CodeIgnitor friendly way of display date as below
        echo strftime("%B %d, %Y", strtotime($rows->date));

?></time>

<div class="summary-category">Category: <a href="

<?php

        echo site_url() . 'category/';

?>
<?php

        foreach ($query_result as $result) {

            if ($result->id === $rows->category_id) {

                $cat_title = html_special($result->name);
                echo url_title(strtolower($cat_title));
                break;

            }

        }

?>

">
<?php

        echo $cat_title;

?>
</a></div>

<div class="summary-teaser"><?php

        // Uses text helper for nice clean break in string near to 320 characters
        // Also uses typograhpy class to add HTML to the database text
        // This produces nicely formatted blocks to text

        echo $this->typography->auto_typography(character_limiter(utf8_special($rows->
            body), 320));

?></div>

<div class="read-more"><a href="<?php

        echo site_url('article/' . url_title(strtolower($rows->title)));

?>">Read more</a></div>

</article>

<?php

    endforeach;

?>

<?php

    echo '<div id="pagination">';
    echo $this->pagination->create_links();
    echo '</div>';

endif;

?>


<?php

/**
 * THIS IS FOR FULL NODE
 */

if (!empty($full_node)):

?>

<article id="node">

<h1><?php

    echo html_special($full_node[0]->title);

?></h1>

<time id="node-date" datetime="<?php

    echo strftime("%Y-%m-%d", strtotime($full_node[0]->date));

?>"><?php

    echo strftime("%B %d, %Y", strtotime($full_node[0]->date));

?></time>

<div id="node-author">Author: <?php

    echo html_special($author_name[0]->username);

?></div>

<div id="node-category">Category: <a href="<?php

    echo site_url() . 'category/';

    foreach ($query_result as $cat) {

        if ($cat->id == $full_node[0]->category_id) {

            echo url_title(strtolower($cat->name));

        }

    }

?> "><?php

    echo html_special($cat_name[0]->name);

?></a></div>

<div id="node-image">

<img src="<?php

    echo base_url();

?>images/thumbnail/<?php

    echo $full_node[0]->image_id;

?>" alt="<?php

    echo $full_node[0]->image_id;

?>" />

</div>

<div id="node-body"><?php

    echo $this->typography->auto_typography(utf8_special($this->textile->
        TextileThis($full_node[0]->body)));

?></div></article>

<?php

endif;

?>
<?php

?>

<?php

/**
 * THIS IS FOR THE CATEGORY PAGE
 */


if (!empty($category_records)):

?>

<h1><?php

    echo html_special($category_records[0]->name);

?></h1>

<?php

    foreach ($category_details as $cat):

?>

<article class="category-summary">

<h2><?php

        echo html_special($cat->title);

?></h2>

<div class="category-summary-date"><?php

        echo strftime("%B %d, %Y", strtotime($cat->date));

?></div>

<div class="category-summary-teaser">
<?php

        echo $this->typography->auto_typography(character_limiter(utf8_special($cat->
            body), 320));

?>
</div>

<div class="read-more"><a href="<?php

        echo site_url("article/" . url_title(strtolower($cat->title)))

?>">Read more</a></div>

</article>

<?php

    endforeach;

?>

<?php

    echo '<div id="pagination">';
    echo $this->pagination->create_links();
    echo '</div>';

endif;

?>


</section><!-- End column two -->

</div><!-- End content -->