<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Faker\Factory as Faker;

class FakeDataController extends Controller
{
    public function user(){
        $magic = Faker::create();
        return response()->json([
           "meta" => [
               "code" => 200
           ],
            "data" => [
                "attribution" => null,
                "tags" => [
                    $magic->domainWord,
                    $magic->domainWord
                ],
                "type" => "image",
                "location" => $magic->country,
                "filter" => "normal",
                "created_time" => time(),
                "link" => "https://instagram.com/p/" . str_random(10),
                "images" => [
                    "low_resolution" => [
                        "url" => "http://lorempixel.com/320/320/fashion/",
                        "width" => 320,
                        "height" => 320
                    ],
                    "thumbnail" => [
                        "url" => "http://lorempixel.com/150/150/fashion/",
                        "width" => 150,
                        "height" => 150
                    ],
                    "standard_resolution" => [
                        "url" => "http://lorempixel.com/640/640/fashion/",
                        "width" => 640,
                        "height" => 640
                    ]
                ],
                "users_in_photo" => [
                    $magic->userName,
                    $magic->userName,
                    $magic->userName,
                    $magic->userName
                ],
                "caption" => [
                    "created_time" => time(),
                    "text" => $magic->text,
                    "from" => [
                        "username" => $magic->userName,
                        "profile_picture" => "http://lorempixel.com/320/320/people/",
                        "id" => $magic->numberBetween(1000000000,2000000000),
                    ],
                ],
                "user_has_liked" => $magic->boolean,
                "id" => "1059322354827483655_1547627" . rand(100,999),
                "user" => [
                    "username" => $magic->userName,
                    "profile_picture" => "http://lorempixel.com/320/320/people/",
                    "id" => $magic->numberBetween(1000000000,2000000000),
                    "full_name" => $magic->firstName . " " . $magic->lastName
                ]
            ]
        ]);
    }

    public function image(){
        $magic = Faker::create();
        return response()->json([
            "user" => [
                "biography" => $magic->text,
                "blocked_by_viewer" => $magic->boolean,
                "country_block" => $magic->boolean,
                "external_url" => $magic->url,
                "external_url_linkshimmed" => $magic->url,
                "followed_by" => [
                    "count" => rand(100,2500000)
                ],
                "followed_by_viewer" => $magic->boolean,
                "follows" => [
                    "count" => rand(100,15000)
                ],
                "follows_viewer" => $magic->boolean,
                "full_name" => $magic->firstName . " " . $magic->lastName,
                "has_blocked_viewer" => $magic->boolean,
                "has_requested_viewer" => $magic->boolean,
                "id" => rand(10000000,99999999),
                "is_private" => $magic->boolean,
                "is_verified" => $magic->boolean,
                "mutual_followers" => [
                    "additional_count" => rand(10,100),
                    "usernames" => [
                        $magic->userName,
                        $magic->userName,
                        $magic->userName,
                        $magic->userName
                    ]
                ],
                "profile_picture_url" => "http://lorempixel.com/320/320/people/",
                "profile_picture_url_hd" => "http://lorempixel.com/320/320/people/",
                "requested_by_viewer" => $magic->boolean,
                "username" => $magic->userName,
                "connected_fb_page" => null,
                "media" => [
                    "nodes" => [
                        "__typename" => "GraphImage",
                        "id" => "172198531929570" . rand(1000,9999),
                        "comments_disabled" => $magic->boolean,
                        "dimensions" => [
                            "height" => rand(300,1500),
                            "width" => rand(300,1500)
                        ],
                        "edge_media_preview_like" => [
                            "count" => rand(1000,30000)
                        ],
                        "gating_info" => null,
                        "media_preview" => str_random(64),
                        "owner" => [
                            "id" => rand(10000000,99999999)
                        ],
                        "thumbnail_src" => "http://lorempixel.com/150/150/fashion/",
                        "thumbnail_resources" => [[
                                "src" => "http://lorempixel.com/150/150/fashion/",
                                "config_width" => 150,
                                "config_height" => 150
                            ], [
                                "src" => "http://lorempixel.com/150/150/fashion/",
                                "config_width" => 150,
                                "config_height" => 150
                        ]],
                        "is_video" => false,
                        "code" => str_random(11),
                        "date" => time(),
                        "display_src" => "http://lorempixel.com/150/150/fashion",
                        "caption" => $magic->text,
                        "comments" => [
                            "count" => rand(100,10000)
                        ],
                        "likes" => [
                            "count" => rand(1000,100000)
                        ]
                    ],
                    "count" => rand(100,5000),
                    "page_info" => [
                        "has_next_page" => $magic->boolean,
                        "end_cursor" => str_random(30)
                    ]
                ],
                "saved_media" => [
                    "nodes" => [
                    ],
                    "count" => 0,
                    "page_info" => [
                        "has_next_page" => false,
                        "end_cursor" => null
                    ]
                ],
                "media_collections" => [
                    "count" => 0,
                    "page_info" => [
                        "has_next_page" => false,
                        "end_cursor" => null
                    ],
                    "edges" => [
                    ]
                ]

            ],
            "logging_page_id" => "profilePage_" . rand(10000000,99999999),
            "show_suggested_profiles" => $magic->boolean,
            "graphql" => [
            ]
        ]);
    }

}
