<?php 

/*  Custom function can be declare here  */


/**
 * Cron for Checking Bogo Discount Schedule Start Here 
 */

    // Schedule event on plugin/theme activation.
    function bogo_schedule_cron() {
        if ( ! wp_next_scheduled( 'bogo_update_status_cron' ) ) {
            wp_schedule_event( time(), 'five_minutes', 'bogo_update_status_cron' );
        }
    }
    add_action( 'wp', 'bogo_schedule_cron' );

    // Clear the scheduled event on deactivation.
    function bogo_clear_cron() {
        $timestamp = wp_next_scheduled( 'bogo_update_status_cron' );
        wp_unschedule_event( $timestamp, 'bogo_update_status_cron' );
    }
    register_deactivation_hook( __FILE__, 'bogo_clear_cron' );

    // Add custom interval if needed
    function bogo_cron_intervals( $schedules ) {
        $schedules['five_minutes'] = array(
            'interval' => 60,  // 300 seconds = 5 minutes
            'display'  => esc_html__( 'Every one Minutes' ),
        );
        return $schedules;
    }
    add_filter( 'cron_schedules', 'bogo_cron_intervals' );

    function bogo_update_status_callback() {
        // Query all posts of your custom post type where the discount is currently enabled.
        $args = array(
            'post_type'      => 'wc_bogo',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'   => '_bogo_deal_status',
                    'value' => 'yes',
                ),
            ),
        );

        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            $ist_timezone      = new DateTimeZone( 'Asia/Kolkata' );
            $current_time      = new DateTime( 'now', $ist_timezone );
            $current_timestamp = $current_time->getTimestamp();

            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id = get_the_ID();

                // Retrieve start and end dates.
                $start_date = get_post_meta( $post_id, '_bogo_start_date', true );
                $end_date   = get_post_meta( $post_id, '_bogo_end_date', true );

                // Convert the dates using the known format and timezone.
                $start_timestamp = ! empty( $start_date )
                    ? DateTime::createFromFormat('Y-m-d\TH:i', $start_date, $ist_timezone)->getTimestamp()
                    : 0;
                $end_timestamp   = ! empty( $end_date )
                    ? DateTime::createFromFormat('Y-m-d\TH:i', $end_date, $ist_timezone)->getTimestamp()
                    : 0;

                // Only disable the status when the end date has passed.
                if ( $end_timestamp && $current_timestamp >= $end_timestamp ) {
                    update_post_meta( $post_id, '_bogo_deal_status', 'no' );
                }
            }
            wp_reset_postdata();
        }
    }
    add_action( 'bogo_update_status_cron', 'bogo_update_status_callback' );