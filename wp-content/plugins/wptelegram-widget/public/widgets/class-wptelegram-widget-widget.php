<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
* Adds WP Telegram Widget widget
*/
class WPTelegram_Widget_Widget extends WP_Widget {

    /**
    * Register widget with WordPress
    */
    public function __construct() {
        parent::__construct(
            'wptelegram_widget_widget', // Base ID
            esc_html__( 'WP Telegram Widget', 'wptelegram-widget' ), // Name
            array( 'description' => esc_html__( 'Display the Telegram Public Channel or Group Feed in a widget', 'wptelegram-widget' ),
            ) // Args
        );
    }

    /**
     * Outputs the content for the widget.
     *
     * @since 1.0.0
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Pages widget instance.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';

        /**
         * Filters the widget title.
         *
         * @since 1.0.0
         *
         * @param string $title    The widget title. Default 'Pages'.
         * @param array  $instance Array of settings for the current widget.
         * @param mixed  $id_base  The widget ID.
         */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $widget_with = ( empty( $instance['widget_with'] ) || ! absint( $instance['widget_with'] ) ) ? 100 : (int) $instance['widget_with'];

        $num_messages = ( empty( $instance['num_messages'] ) || ! absint( $instance['num_messages'] ) ) ? 5 : (int) $instance['num_messages'];

        $author_photo = ( empty( $instance['author_photo'] ) || ! in_array( $instance['author_photo'], array( 'auto', 'always_show', 'always_hide' ) ) ) ? 'auto' : $instance['author_photo'];

        $content = do_shortcode( '[wptelegram-widget num_messages="' . $num_messages . '" widget_width="' . $widget_with . '" author_photo="' . $author_photo . '"]' );

        if ( ! empty( $content ) ) {
            echo $args['before_widget'];
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
        ?>
        <div>
            <?php echo $content; ?>
        </div>
        <?php
            echo $args['after_widget'];
        }
    }

    /**
     * Handles updating settings for the widget instance.
     *
     * @since 1.0.0
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update( $new_instance, $old_instance ) {
        
        $instance = $old_instance;

        $instance['title'] = sanitize_text_field( $new_instance['title'] );

        if ( in_array( $new_instance['author_photo'], array( 'auto', 'always_show', 'always_hide' ) ) ) {
            $instance['author_photo'] = $new_instance['author_photo'];
        } else {
            $instance['author_photo'] = 'auto';
        }

        $instance['widget_with'] = (int) sanitize_text_field( $new_instance['widget_with'] );

        $instance['num_messages'] = (int) sanitize_text_field( $new_instance['num_messages'] );

        return $instance;
    }

    /**
     * Outputs the settings form for the widget.
     *
     * @since 1.0.0
     *
     * @param array $instance Current settings.
     */
    public function form( $instance ) {
        //Defaults
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'num_messages'  => 5,
                'widget_with'   => 100,
                'author_photo'  => 'auto',
                'title'         => '',
            )
        );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title' ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'author_photo' ) ); ?>"><?php _e( 'Author Photo', 'wptelegram-widget' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'author_photo' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'author_photo' ) ); ?>" class="widefat">
                <option value="auto"<?php selected( $instance['author_photo'], 'auto' ); ?>><?php _e( 'Auto', 'wptelegram-widget' ); ?></option>
                <option value="always_show"<?php selected( $instance['author_photo'], 'always_show' ); ?>><?php _e( 'Always show', 'wptelegram-widget' ); ?></option>
                <option value="always_hide"<?php selected( $instance['author_photo'], 'always_hide' ); ?>><?php _e( 'Always hide', 'wptelegram-widget' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'widget_with' ) ); ?>"><?php _e( 'Widget Width', 'wptelegram-widget' ); ?></label>
            <input type="text" value="<?php echo esc_attr( $instance['widget_with'] ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_with' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'widget_with' ) ); ?>" class="widefat" placeholder="100" />
            <br />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'num_messages' ) ); ?>"><?php _e( 'Number of Messages', 'wptelegram-widget' ); ?></label>
            <input type="number" value="<?php echo esc_attr( $instance['num_messages'] ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_messages' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'num_messages' ) ); ?>" class="widefat" placeholder="100" />
            <br />
            <span class="description"><?php _e( 'Number of messages to display in the widget', 'wptelegram-widget' ); ?></span>
        </p>
        <?php
    }
}