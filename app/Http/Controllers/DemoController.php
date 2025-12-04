<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DemoController extends Controller
{
    public function collectionExample()
    {
        $arrays = [1, 2, 3, 5, 8, 40];
        $random = collect($arrays)->random(2);
        return $random->all();
        // return $random->toJson();
        // Storage::put('test.txt', 'Hello world');

    }

    public function testStorage()
    {
        $path = Storage::disk('public')->put('test.txt', 'Hello world by mariya to mariya');
        return $path ? 'DONE' : 'FAILED';
    }

    public function getContent()
    {
        $content = Storage::disk('public')->get('test.txt');
        return $content;
    }
}
