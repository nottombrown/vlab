<?php 

add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'sponsor',
       array(
         'labels' => array(
           'name' => __( 'Sponsors' ),
           'singular_name' => __( 'Sponsor' )
         ),
         'public' => true
       )
     );

 
   register_post_type( 'publication',
       array(
         'labels' => array(
           'name' => __( 'Publications' ),
           'singular_name' => __( 'Publication' )
         ),
         'public' => true
       )
     );

   register_post_type( 'patent',
       array(
         'labels' => array(
           'name' => __( 'Patents' ),
           'singular_name' => __( 'Patent' )
         ),
         'public' => true
       )
     );
  
}

?>