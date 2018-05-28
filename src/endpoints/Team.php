<?php

namespace HnhDigital\SlackApi;

/*
 * This file is part of the PHP Slack API.
 *
 * (c) H&H|Digital <hello@hnh.digital>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use HnhDigital\SlackApi\Foundation\Base;

/**
 * This is the Team class.
 *
 * This file is automatically generated.
 *
 * @link 
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class Team extends Base
{
    /**
     * Endpoint.
     *
     * @var string
     */
    protected $endpoint = '';

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the access logs for the current team.
     *
     * @link https://api.slack.com/methods/team.accessLogs
     *
     * @return array
     */
    public function accessLogs($optional = [])
    {
        return $this->apiCall('get', '/team.accessLogs');
    }

    /**
     * Gets billable users information for the current team.
     *
     * @link https://api.slack.com/methods/team.billableInfo
     *
     * @return array
     */
    public function billableInfo($optional = [])
    {
        return $this->apiCall('get', '/team.billableInfo');
    }

    /**
     * Gets information about the current team.
     *
     * @link https://api.slack.com/methods/team.info
     *
     * @return array
     */
    public function info()
    {
        return $this->apiCall('get', '/team.info');
    }

    /**
     * Gets the integration logs for the current team.
     *
     * @link https://api.slack.com/methods/team.integrationLogs
     *
     * @return array
     */
    public function integrationLogs($optional = [])
    {
        return $this->apiCall('get', '/team.integrationLogs');
    }
}