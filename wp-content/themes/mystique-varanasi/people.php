<?php
/**
 * Template Name: People
 *
 * Selectable from a dropdown menu on the edit page screen.
 */
?>
<?php
 get_header();
?>

  <!-- main content: primary + sidebar(s) -->
  <div id="main">
   <div id="main-inside" class="clear-block">
    <!-- primary content -->
    <div id="primary-content">
     <div class="blocks">
       <?php do_action('mystique_before_primary'); ?>
       
       <table width=100%>
       <tbody>
       <tr>
       <td><h1 class="title">Graduate Students:</h1>
       <?php echo do_shortcode('[people-lists list=postdoctoral-researchers]'); ?>
       </td>
       <td><h1 class="title">Undergraduate Students:</h1>
       <?php echo do_shortcode('[people-lists list=postdoctoral-researchers]'); ?>
       </td>
       </tr>
       </tbody>
       </table>
       
       <?php do_action('mystique_after_primary'); ?>
     </div>
    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>

