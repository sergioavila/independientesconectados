<?php
/* Template Name: Visita */ 


get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

?>

<div id="main">
    <div class="container">
        <h1>Agenda tu visita</h1>
        <p>Coordina una visita de nuestros representantes en tu farmacia</p>
        <!-- Calendly inline widget begin -->
    <div class="calendly-inline-widget" data-url="https://calendly.com/sergio-teva?hide_landing_page_details=1&hide_gdpr_banner=1&primary_color=0907d4" style="min-width:320px;height:700px;"></div>
    <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
    <!-- Calendly inline widget end -->
    </div>
</div>
<?php

get_footer();
