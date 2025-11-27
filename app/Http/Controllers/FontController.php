<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FontController extends Controller
{
    public function index()
    {
        $fonts = [
            'Jost' => [
                'weights' => ['Thin' => 100, 'ExtraLight' => 200, 'Light' => 300, 'Regular' => 400, 'Medium' => 500, 'SemiBold' => 600, 'ExtraBold' => 700, 'Black' => 800],
                'styles' => ['normal', 'italic'],
                'sample' => 'The quick brown fox jumps over the lazy dog. Pack my box with five dozen liquor jugs.'
            ],
            'Raleway' => [
                'weights' => ['Thin' => 100, 'ExtraLight' => 200, 'Light' => 300, 'Regular' => 400, 'Medium' => 500, 'SemiBold' => 600, 'Bold' => 700, 'ExtraBold' => 800, 'Black' => 900],
                'styles' => ['normal', 'italic'],
                'sample' => 'Sphinx of black quartz, judge my vow. How vexingly quick daft zebras jump!'
            ],
            'Stack Sans Notch' => [
                'weights' => ['ExtraLight' => 200, 'Light' => 300, 'Regular' => 400, 'Medium' => 500, 'SemiBold' => 600, 'Bold' => 700],
                'styles' => ['normal'],
                'sample' => 'Typography is the craft of endowing human language with a durable visual form.'
            ],
            'Ubuntu' => [
                'weights' => ['Light' => 300, 'Regular' => 400, 'Medium' => 500, 'Bold' => 700],
                'styles' => ['normal', 'italic'],
                'sample' => 'Design is not just what it looks like and feels like. Design is how it works.'
            ],
            'Comfortaa' => [
                'weights' => ['300', '400', '500', '600', '700'],
                'styles' => ['normal'],
                'sample' => 'Good typography is invisible, great typography is beautiful.'
            ],
            'Elms Sans' => [
                'weights' => ['100', '200', '300', '400', '500', '600', '700', '800', '900'],
                'styles' => ['normal', 'italic'],
                'sample' => 'The only way to do great work is to love what you do.'
            ]
        ];

        return view('fonts.index', compact('fonts'));
    }
}
