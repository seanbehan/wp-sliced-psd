<?php

get_header();

if(have_posts()): while(have_posts()): the_post(); print_the_content( get_the_content() ); endwhile; endif; 

get_footer(); 

?>