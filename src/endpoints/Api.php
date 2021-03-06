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
 * This is the Api class.
 *
 * This file is automatically generated.
 *
 * @link 
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class Api extends Base
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
     * Checks API calling code.
     *
     * @link https://api.slack.com/methods/api.test
     *
     * @return array
     */
    public function test($optional = [])
    {
        return $this->apiCall('get', '/api.test');
    }
}
