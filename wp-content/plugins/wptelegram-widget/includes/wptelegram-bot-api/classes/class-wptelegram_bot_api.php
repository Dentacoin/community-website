<?php

/**
 * Class WPTelegram_Bot_API.
 *
 * 
 */
if ( ! class_exists( 'WPTelegram_Bot_API' ) ) :
class WPTelegram_Bot_API {

    /**
     * @var string Telegram Bot API Access Token.
     */
    private $bot_token;

    /**
     * @var WPTelegram_Bot_API_Client The Telegram client
     */
    protected $client;

    /**
     * @since  1.0.0
     *
     * @var WPTelegram_Bot_API_Request The original request
     */
    protected $request;

    /**
     * @var WPTelegram_Bot_API_Response|null Stores the last request made to Telegram Bot API.
     */
    protected $last_response;

    /**
     * Instantiates a new WPTelegram_Bot_API object.
     *
     *
     * @param string    $bot_token   The Telegram Bot API Access Token.
     *
     */
    public function __construct( $bot_token = null ) {
        $this->bot_token = $bot_token;

        $this->client = new WPTelegram_Bot_API_Client();
    }
    /**
     * Magic Method to handle all API calls.
     *
     * @param $method
     * @param $args
     *
     * @return mixed|string
     */
    public function __call( $method, $args ) {
        if ( ! empty( $args ) ) {
            $args = $args[0];
        }
        return $this->sendRequest( $method, $args );
    }

    /**
     * Set the bot token for this request.
     *
     * @since  1.0.0
     *
     * @param string    $bot_token  The Telegram Bot API Access Token.
     *
     */
    public function set_bot_token( $bot_token ) {
        $this->bot_token = $bot_token;
    }

    /**
     * Returns Telegram Bot API Access Token.
     *
     * @return string
     */
    public function get_bot_token() {
        return $this->bot_token;
    }

    /**
     *
     * @return WPTelegram_Bot_API_Client
     */
    public function get_client() {
        return $this->client;
    }

    /**
     * Return the original request 
     *
     * @since   1.0.0
     *
     * @return WPTelegram_Bot_API_Request
     */
    public function get_request() {
        return $this->request;
    }

    /**
     * Returns the last response returned from API request.
     *
     * @return WPTelegram_Bot_API_Response
     */
    public function get_last_response() {
        return $this->last_response;
    }

    /**
     * Send Message
     *
     * @since  1.0.0
     */
    public function sendMessage( $params ){
        
        // break text after every 4096th character and preserve words
        preg_match_all( '/.{1,4095}(?:\s|$)/su', $params['text'], $matches );
        foreach ( $matches[0] as $text ) {
            $params['text'] = $text;
            $res = $this->sendRequest( __FUNCTION__, $params );
            $params['reply_to_message_id'] = null;
        }
        return $res;
    }

    /**
     * sendRequest
     *
     * @since  1.0.0
     */
    private function sendRequest( $endpoint, $params ){
        
        if ( null == $this->get_bot_token() ) {
            return new WP_Error( 'invalid_bot_token', __( 'Bot Token is required to make a request', 'wptelegram' ) );
        }

        $this->request = $this->request( $endpoint, $params );

        $this->last_response = $this->get_client()->sendRequest( $this->get_request() );
        
        if ( (bool) apply_filters( 'wptelegram_bot_api_enable_log', false ) ) {
            $this->api_log();
        }

        do_action( 'wptelegram_bot_api_debug', $this->last_response, $this );

        return $this->last_response;
    }

    /**
     * Instantiates a new WPTelegram_Bot_API_Request
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @return WPTelegram_Bot_API_Request
     */
    private function request( $endpoint, array $params = array() ) {
        return new WPTelegram_Bot_API_Request(
            $this->get_bot_token(),
            $endpoint,
            $params
        );
    }

    /**
     * Create a log of the API requests
     *
     * @since 1.0.0
     *
     */
    private function api_log() {
        $res = $this->get_last_response();
        // add the method and request params
        $text = 'Method: ' . $this->get_request()->get_endpoint() . PHP_EOL . 'Params: ' . json_encode( $this->get_request()->get_params() ) . PHP_EOL . '--------------------------------' . PHP_EOL;

        // add the response
        if ( is_wp_error( $res ) ) {
            $text .= 'WP_Error: ' . $res->get_error_code() . ' ' . $res->get_error_message();
        } else{
            $text .= 'Response: ' . $res->get_body();
        }

        $filename = WP_CONTENT_DIR . '/wptelegram-bot-api.log';
        $filename = apply_filters( 'wptelegram_bot_api_log_filename', $filename );

        $data = PHP_EOL . '[' . current_time( 'mysql' ) . ']' . PHP_EOL . $text . PHP_EOL . PHP_EOL;

        file_put_contents( $filename, $data, FILE_APPEND );
    }
}
endif;