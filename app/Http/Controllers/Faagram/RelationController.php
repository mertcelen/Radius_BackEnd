<?php

namespace App\Http\Controllers\Faagram;

use App\FaagramRelation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RelationController extends Controller
{
    /**
     * @api {post} /api/faagram/relation/add Add Faagram Relation
     * @apiName AddFaagramRelation
     * @apiGroup Faagram
     *
     * @apiParam {String} follower Follower user id.
     * @apiParam {String} following Following user id.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function add(){
        $relation = new FaagramRelation();
        $relation->follower = request('follower');
        $relation->following = request('following');
        $relation->save();
        return [
            'success' => [
                "message" => 'Relation added',
                "code" => 5
            ]
        ];
    }
    /**
     * @api {post} /api/faagram/relation/getFollowers Get Faagram User' Followers
     * @apiName GetFaagramFollowers
     * @apiGroup Faagram
     *
     * @apiParam {String} userID User id.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function getFollowers(){
        $followers = FaagramRelation::where('follower',request('userId'))->get();
        return [
            'success' => [
                "message" => 'Followers retrieved',
                "code" => 5
            ],
            'followers' => $followers
        ];
    }
    /**
     * @api {post} /api/faagram/relation/getFollowing Get Faagram User' Following
     * @apiName GetFaagramFollowing
     * @apiGroup Faagram
     *
     * @apiParam {String} userId User id.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function getFollowing(){
        $following = FaagramRelation::where('following',request('userId'))->get();
        return [
            'success' => [
                "message" => 'Following retrieved',
                "code" => 5
            ],
            'followers' => $following
        ];
    }
    /**
     * @api {post} /api/faagram/relation/remove Remove Faagram Relation
     * @apiName RemoveFaagramRelation
     * @apiGroup Faagram
     *
     * @apiParam {String} id Relation id.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function remove(){
        FaagramRelation::where('_id',request('id'))->delete();
        return [
            'success' => [
                "message" => 'Relation removed',
                "code" => 5
            ]
        ];
    }
}
