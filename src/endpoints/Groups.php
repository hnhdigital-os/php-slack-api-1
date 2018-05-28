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
 * This is the Groups class.
 *
 * This file is automatically generated.
 *
 * @link 
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class Groups extends Base
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
     * Fetches history of messages and events from a private channel.
     *
     * @link https://api.slack.com/methods/groups.history
     *
     * @return array
     */
    public function history($optional = [])
    {
        return $this->apiCall('get', '/groups.history');
    }

    /**
     * Gets information about a private channel.
     *
     * @link https://api.slack.com/methods/groups.info
     *
     * @return array
     */
    public function info($optional = [])
    {
        return $this->apiCall('get', '/groups.info');
    }

    /**
     * Lists private channels that the calling user has access to.
     *
     * @link https://api.slack.com/methods/groups.list
     *
     * @return array
     */
    public function getList($optional = [])
    {
        return $this->apiCall('get', '/groups.list');
    }

    /**
     * Retrieve a thread of messages posted to a private channel
     *
     * @link https://api.slack.com/methods/groups.replies
     *
     * @return array
     */
    public function replies($optional = [])
    {
        return $this->apiCall('get', '/groups.replies');
    }

    /**
     * Archives a private channel.
     *
     * @param array $optional
     *                        - [channel=null] (string) Private channel to archive
     *
     * @link https://api.slack.com/methods/groups.archive
     *
     * @return mixed
     */
    public function archive($optional = [])
    {
        return $this->apiCall('post', '/groups.archive', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Creates a private channel.
     *
     * @param array $optional
     *                        - [validate=null] (boolean) Whether to return errors on invalid channel name instead
     *                        of modifying it to meet the specified criteria.
     *                        - [name=null] (string) Name of private channel to create
     *
     * @link https://api.slack.com/methods/groups.create
     *
     * @return mixed
     */
    public function create($optional = [])
    {
        return $this->apiCall('post', '/groups.create', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Clones and archives a private channel.
     *
     * @param array $optional
     *                        - [channel=null] (string) Private channel to clone and archive.
     *
     * @link https://api.slack.com/methods/groups.createChild
     *
     * @return mixed
     */
    public function createChild($optional = [])
    {
        return $this->apiCall('post', '/groups.createChild', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Invites a user to a private channel.
     *
     * @param array $optional
     *                        - [user=null] (string) User to invite.
     *                        - [channel=null] (string) Private channel to invite user to.
     *
     * @link https://api.slack.com/methods/groups.invite
     *
     * @return mixed
     */
    public function invite($optional = [])
    {
        return $this->apiCall('post', '/groups.invite', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Removes a user from a private channel.
     *
     * @param array $optional
     *                        - [user=null] (string) User to remove from private channel.
     *                        - [channel=null] (string) Private channel to remove user from.
     *
     * @link https://api.slack.com/methods/groups.kick
     *
     * @return mixed
     */
    public function kick($optional = [])
    {
        return $this->apiCall('post', '/groups.kick', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Leaves a private channel.
     *
     * @param array $optional
     *                        - [channel=null] (string) Private channel to leave
     *
     * @link https://api.slack.com/methods/groups.leave
     *
     * @return mixed
     */
    public function leave($optional = [])
    {
        return $this->apiCall('post', '/groups.leave', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Sets the read cursor in a private channel.
     *
     * @param array $optional
     *                        - [ts=null] (number) Timestamp of the most recently seen message.
     *                        - [channel=null] (string) Private channel to set reading cursor in.
     *
     * @link https://api.slack.com/methods/groups.mark
     *
     * @return mixed
     */
    public function mark($optional = [])
    {
        return $this->apiCall('post', '/groups.mark', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Opens a private channel.
     *
     * @param array $optional
     *                        - [channel=null] (string) Private channel to open.
     *
     * @link https://api.slack.com/methods/groups.open
     *
     * @return mixed
     */
    public function open($optional = [])
    {
        return $this->apiCall('post', '/groups.open', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Renames a private channel.
     *
     * @param array $optional
     *                        - [validate=null] (boolean) Whether to return errors on invalid channel name instead
     *                        of modifying it to meet the specified criteria.
     *                        - [name=null] (string) New name for private channel.
     *                        - [channel=null] (string) Private channel to rename
     *
     * @link https://api.slack.com/methods/groups.rename
     *
     * @return mixed
     */
    public function rename($optional = [])
    {
        return $this->apiCall('post', '/groups.rename', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Sets the purpose for a private channel.
     *
     * @param array $optional
     *                        - [purpose=null] (string) The new purpose
     *                        - [channel=null] (string) Private channel to set the purpose of
     *
     * @link https://api.slack.com/methods/groups.setPurpose
     *
     * @return mixed
     */
    public function setPurpose($optional = [])
    {
        return $this->apiCall('post', '/groups.setPurpose', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Sets the topic for a private channel.
     *
     * @param array $optional
     *                        - [topic=null] (string) The new topic
     *                        - [channel=null] (string) Private channel to set the topic of
     *
     * @link https://api.slack.com/methods/groups.setTopic
     *
     * @return mixed
     */
    public function setTopic($optional = [])
    {
        return $this->apiCall('post', '/groups.setTopic', ['json' => array_merge([
        ], $optional)]);
    }

    /**
     * Unarchives a private channel.
     *
     * @param array $optional
     *                        - [channel=null] (string) Private channel to unarchive
     *
     * @link https://api.slack.com/methods/groups.unarchive
     *
     * @return mixed
     */
    public function unarchive($optional = [])
    {
        return $this->apiCall('post', '/groups.unarchive', ['json' => array_merge([
        ], $optional)]);
    }
}