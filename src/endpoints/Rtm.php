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
 * This is the Rtm class.
 *
 * This file is automatically generated.
 *
 * @link 
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class Rtm extends Base
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
     * Starts a Real Time Messaging session.
     *
     * @link https://api.slack.com/methods/rtm.connect
     *
     * @return array
     */
    public function connect($optional = [])
    {
        return $this->apiCall('get', '/rtm.connect');
    }

    /**
     * Starts a Real Time Messaging session.
     *
     * @link https://api.slack.com/methods/rtm.start
     *
     * @return array
     */
    public function start($optional = [])
    {
        return $this->apiCall('get', '/rtm.start');
    }
}
