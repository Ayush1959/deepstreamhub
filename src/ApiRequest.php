<?php

namespace Deepstreamcentral;

/**
 * A class representing a single API request
 *
 * @author deepstreamCentral GmbH <info@deepstreamcentral.com>
 * @copyright (c) 2017, deepstreamCentral GmbH
 */
class ApiRequest
{
    private $requestData;
    private $url;

    /**
     * Creates the request
     *
     * @param string $url
     * @param mixed $authData
     */
    public function __construct($url, $authData)
    {
        $this->url = $url;
        $this->requestData = $authData;
        $this->requestData['body'] = array();
    }

    /**
     * Adds an aditional step to the request
     *
     * @param array $request
     *
     * @private
     * @returns void
     */
    public function add($request)
    {
        array_push($this->requestData['body'], $request);
    }

    /**
     * Executes the HTTP request and parses the result
     *
     * @private
     * @return mixed result data
     */
    public function execute()
    {
        // $options = array(
        //     'http' => array(
        //         'header'  => "Content-type: application/json\r\n",
        //         'method'  => 'POST',
        //         'content' => json_encode( $this->requestData, JSON_UNESCAPED_SLASHES )
        //     )
        // );

        // $context  = stream_context_create($options);
        //$result = file_get_contents($this->url, false, $context);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->requestData, JSON_UNESCAPED_SLASHES));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($ch);

        curl_close($ch);

        if ($result === false) {
            return false;
        } else {
            return json_decode($result);
        }
    }
}
