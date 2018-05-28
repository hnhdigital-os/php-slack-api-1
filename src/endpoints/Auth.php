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
 * This is the Auth class.
 *
 * This file is automatically generated.
 *
 * @link 
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class Auth extends Base
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
     * Revokes a token.
     *
     * @link https://api.slack.com/methods/auth.revoke
     *
     * @return array
     */
    public function revoke($optional = [])
    {
        return $this->apiCall('get', '/auth.revoke');
    }

    /**
     * Checks authentication & identity.
     *
     * @link https://api.slack.com/methods/auth.test
     *
     * @return array
     */
    public function test()
    {
        return $this->apiCall('get', '/auth.test');
    }
}