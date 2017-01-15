<?php

namespace Plivo;

use GuzzleHttp\Client;

/**
 * Class RestAPI
 * @package Plivo
 */
class RestAPI
{
    /**
     * @var string
     */
    private $api;
    /**
     * @var
     */
    private $auth_id;
    /**
     * @var
     */
    private $auth_token;

    /**
     * RestAPI constructor.
     * @param $auth_id
     * @param $auth_token
     * @param string $url
     * @param string $version
     * @throws PlivoError
     */
    public function __construct($auth_id, $auth_token, $url = "https://api.plivo.com", $version = "v1")
    {
        if (empty($auth_id)) {
            throw new PlivoError("no auth_id");
        }

        if (empty($auth_token)) {
            throw new PlivoError("no auth_token");
        }

        $this->version = $version;
        $this->api = $url . "/" . $this->version . "/Account/" . $auth_id;
        $this->auth_id = $auth_id;
        $this->auth_token = $auth_token;
    }

    /**
     * @param $uri
     * @param array $post_params
     * @param $signature
     * @param $auth_token
     * @return bool
     */
    public static function validate_signature($uri, $post_params = [], $signature, $auth_token)
    {
        ksort($post_params);

        foreach ($post_params as $key => $value) {
            $uri .= "$key$value";
        }

        $generated_signature = base64_encode(hash_hmac("sha1", $uri, $auth_token, true));

        return $generated_signature == $signature;
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_account($params = [])
    {
        return $this->request('GET', '', $params);
    }

    /**
     * @param $method
     * @param $path
     * @param array $params
     * @return array
     */
    private function request($method, $path, $params = [])
    {
        $url = $this->api . rtrim($path, '/') . '/';

        $client = new Client([
            'base_uri' => $url,
            'auth' => [$this->auth_id, $this->auth_token],
            'http_errors' => false,
        ]);

        if (!strcmp($method, "POST")) {
            $body = json_encode($params, JSON_FORCE_OBJECT);

            $response = $client->post(
                '',
                [
                    'headers' => ['Content-type' => 'application/json'],
                    'body' => $body,
                ]
            );
        } elseif (!strcmp($method, "GET")) {
            $response = $client->get(
                '',
                [
                    'query' => $params,
                ]
            );
        } elseif (!strcmp($method, "DELETE")) {
            $response = $client->delete(
                '',
                [
                    'query' => $params,
                ]
            );
        }

        $responseData = json_decode($response->getBody(), true);
        $status = $response->getStatusCode();

        return ["status" => $status, "response" => $responseData];
    }

    ## Accounts ##

    /**
     * @param array $params
     * @return array
     */
    public function modify_account($params = [])
    {
        return $this->request('POST', '', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_subaccounts($params = [])
    {
        return $this->request('GET', '/Subaccount/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function create_subaccount($params = [])
    {
        return $this->request('POST', '/Subaccount/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_subaccount($params = [])
    {
        $subauth_id = $this->pop($params, "subauth_id");

        return $this->request('GET', '/Subaccount/' . $subauth_id . '/', $params);
    }

    /**
     * @param $params
     * @param $key
     * @return mixed
     * @throws PlivoError
     */
    private function pop($params, $key)
    {
        $val = $params[$key];

        if (!$val) {
            throw new PlivoError($key . " parameter not found");
        }

        unset($params[$key]);

        return $val;
    }

    /**
     * @param array $params
     * @return array
     */
    public function modify_subaccount($params = [])
    {
        $subauth_id = $this->pop($params, "subauth_id");

        return $this->request('POST', '/Subaccount/' . $subauth_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete_subaccount($params = [])
    {
        $subauth_id = $this->pop($params, "subauth_id");

        return $this->request('DELETE', '/Subaccount/' . $subauth_id . '/', $params);
    }

    ## Applications ##
    /**
     * @param array $params
     * @return array
     */
    public function get_applications($params = [])
    {
        return $this->request('GET', '/Application/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function create_application($params = [])
    {
        return $this->request('POST', '/Application/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_application($params = [])
    {
        $app_id = $this->pop($params, "app_id");

        return $this->request('GET', '/Application/' . $app_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function modify_application($params = [])
    {
        $app_id = $this->pop($params, "app_id");

        return $this->request('POST', '/Application/' . $app_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete_application($params = [])
    {
        $app_id = $this->pop($params, "app_id");

        return $this->request('DELETE', '/Application/' . $app_id . '/', $params);
    }

    ## Numbers ##
    /**
     * @param array $params
     * @return array
     */
    public function get_numbers($params = [])
    {
        return $this->request('GET', '/Number/', $params);
    }

    ## This API is available only for US numbers with some limitations ##
    ## Please use get_number_group and rent_from_number_group instead ##
    /**
     * @param array $params
     * @return array
     */
    public function search_numbers($params = [])
    {
        return $this->request('GET', '/AvailableNumber/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_number($params = [])
    {
        $number = $this->pop($params, "number");

        return $this->request('GET', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function modify_number($params = [])
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/Number/' . $number . '/', $params);
    }

    ## This API is available only for US numbers with some limitations ##
    ## Please use get_number_group and rent_from_number_group instead ##
    /**
     * @param array $params
     * @return array
     */
    public function rent_number($params = [])
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/AvailableNumber/' . $number . '/');
    }

    /**
     * @param array $params
     * @return array
     */
    public function unrent_number($params = [])
    {
        $number = $this->pop($params, "number");

        return $this->request('DELETE', '/Number/' . $number . '/', $params);
    }

    ## Phone Numbers ##
    /**
     * @param array $params
     * @return array
     */
    public function search_phone_numbers($params = [])
    {
        return $this->request('GET', '/PhoneNumber/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function buy_phone_number($params = [])
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/PhoneNumber/' . $number . '/');
    }

    /**
     * @param array $params
     * @return array
     */
    public function link_application_number($params = [])
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function unlink_application_number($params = [])
    {
        $number = $this->pop($params, "number");
        $params = ["app_id" => ""];

        return $this->request('POST', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_number_group($params = [])
    {
        return $this->request('GET', '/AvailableNumberGroup/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_number_group_details($params = [])
    {
        $group_id = $this->pop($params, "group_id");

        return $this->request('GET', '/AvailableNumberGroup/' . $group_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function rent_from_number_group($params = [])
    {
        $group_id = $this->pop($params, "group_id");

        return $this->request('POST', '/AvailableNumberGroup/' . $group_id . '/', $params);
    }

    ## Calls ##
    /**
     * @param array $params
     * @return array
     */
    public function get_cdrs($params = [])
    {
        return $this->request('GET', '/Call/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_cdr($params = [])
    {
        $record_id = $this->pop($params, 'record_id');

        return $this->request('GET', '/Call/' . $record_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_live_calls($params = [])
    {
        $params["status"] = "live";

        return $this->request('GET', '/Call/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_live_call($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');
        $params["status"] = "live";

        return $this->request('GET', '/Call/' . $call_uuid . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function make_call($params = [])
    {
        return $this->request('POST', '/Call/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function hangup_all_calls($params = [])
    {
        return $this->request('DELETE', '/Call/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function transfer_call($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function hangup_call($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function record($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/Record/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function stop_record($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/Record/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function play($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/Play/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function stop_play($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/Play/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function speak($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/Speak/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function stop_speak($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/Speak/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function send_digits($params = [])
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/DTMF/', $params);
    }

    ## Calls requests ##
    /**
     * @param array $params
     * @return array
     */
    public function hangup_request($params = [])
    {
        $request_uuid = $this->pop($params, 'request_uuid');

        return $this->request('DELETE', '/Request/' . $request_uuid . '/', $params);
    }

    ## Conferences ##
    /**
     * @param array $params
     * @return array
     */
    public function get_live_conferences($params = [])
    {
        return $this->request('GET', '/Conference/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function hangup_all_conferences($params = [])
    {
        return $this->request('DELETE', '/Conference/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_live_conference($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('GET', '/Conference/' . $conference_name . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function hangup_conference($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('DELETE', '/Conference/' . $conference_name . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function hangup_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('DELETE', '/Conference/' . $conference_name . '/Member/' . $member_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function play_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Play/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function stop_play_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request(
            'DELETE',
            '/Conference/' . $conference_name . '/Member/' . $member_id . '/Play/',
            $params
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function speak_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Speak/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function deaf_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Deaf/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function undeaf_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request(
            'DELETE',
            '/Conference/' . $conference_name . '/Member/' . $member_id . '/Deaf/',
            $params
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function mute_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Mute/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function unmute_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request(
            'DELETE',
            '/Conference/' . $conference_name . '/Member/' . $member_id . '/Mute/',
            $params
        );
    }

    /**
     * @param array $params
     * @return array
     */
    public function kick_member($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Kick/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function record_conference($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('POST', '/Conference/' . $conference_name . '/Record/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function stop_record_conference($params = [])
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('DELETE', '/Conference/' . $conference_name . '/Record/', $params);
    }

    ## Recordings ##
    /**
     * @param array $params
     * @return array
     */
    public function get_recordings($params = [])
    {
        return $this->request('GET', '/Recording/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_recording($params = [])
    {
        $recording_id = $this->pop($params, 'recording_id');

        return $this->request('GET', '/Recording/' . $recording_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete_recording($params = [])
    {
        $recording_id = $this->pop($params, 'recording_id');

        return $this->request('DELETE', '/Recording/' . $recording_id . '/', $params);
    }

    ## Endpoints ##
    /**
     * @param array $params
     * @return array
     */
    public function get_endpoints($params = [])
    {
        return $this->request('GET', '/Endpoint/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function create_endpoint($params = [])
    {
        return $this->request('POST', '/Endpoint/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_endpoint($params = [])
    {
        $endpoint_id = $this->pop($params, 'endpoint_id');

        return $this->request('GET', '/Endpoint/' . $endpoint_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function modify_endpoint($params = [])
    {
        $endpoint_id = $this->pop($params, 'endpoint_id');

        return $this->request('POST', '/Endpoint/' . $endpoint_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete_endpoint($params = [])
    {
        $endpoint_id = $this->pop($params, 'endpoint_id');

        return $this->request('DELETE', '/Endpoint/' . $endpoint_id . '/', $params);
    }

    ## Incoming Carriers ##
    /**
     * @param array $params
     * @return array
     */
    public function get_incoming_carriers($params = [])
    {
        return $this->request('GET', '/IncomingCarrier/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function create_incoming_carrier($params = [])
    {
        return $this->request('POST', '/IncomingCarrier/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_incoming_carrier($params = [])
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('GET', '/IncomingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function modify_incoming_carrier($params = [])
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('POST', '/IncomingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete_incoming_carrier($params = [])
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('DELETE', '/IncomingCarrier/' . $carrier_id . '/', $params);
    }

    ## Outgoing Carriers ##
    /**
     * @param array $params
     * @return array
     */
    public function get_outgoing_carriers($params = [])
    {
        return $this->request('GET', '/OutgoingCarrier/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function create_outgoing_carrier($params = [])
    {
        return $this->request('POST', '/OutgoingCarrier/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_outgoing_carrier($params = [])
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('GET', '/OutgoingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function modify_outgoing_carrier($params = [])
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('POST', '/OutgoingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete_outgoing_carrier($params = [])
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('DELETE', '/OutgoingCarrier/' . $carrier_id . '/', $params);
    }

    ## Outgoing Carrier Routings ##
    /**
     * @param array $params
     * @return array
     */
    public function get_outgoing_carrier_routings($params = [])
    {
        return $this->request('GET', '/OutgoingCarrierRouting/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function create_outgoing_carrier_routing($params = [])
    {
        return $this->request('POST', '/OutgoingCarrierRouting/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_outgoing_carrier_routing($params = [])
    {
        $routing_id = $this->pop($params, 'routing_id');

        return $this->request('GET', '/OutgoingCarrierRouting/' . $routing_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function modify_outgoing_carrier_routing($params = [])
    {
        $routing_id = $this->pop($params, 'routing_id');

        return $this->request('POST', '/OutgoingCarrierRouting/' . $routing_id . '/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function delete_outgoing_carrier_routing($params = [])
    {
        $routing_id = $this->pop($params, 'routing_id');

        return $this->request('DELETE', '/OutgoingCarrierRouting/' . $routing_id . '/', $params);
    }

    ## Pricing ##
    /**
     * @param array $params
     * @return array
     */
    public function pricing($params = [])
    {
        return $this->request('GET', '/Pricing/', $params);
    }

    ## Outgoing Carriers ##

    ## To be added here ##

    ## Message ##
    /**
     * @param array $params
     * @return array
     */
    public function send_message($params = [])
    {
        return $this->request('POST', '/Message/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_messages($params = [])
    {
        return $this->request('GET', '/Message/', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function get_message($params = [])
    {
        $record_id = $this->pop($params, 'record_id');

        return $this->request('GET', '/Message/' . $record_id . '/', $params);
    }
}
