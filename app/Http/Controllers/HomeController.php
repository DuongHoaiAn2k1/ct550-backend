<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $data = [];
    public function index()
    {
        $this->data['name'] = 'hoaian';
        $this->data['number'] = 0;
        $this->data['users'] = [
            // 'Duong Hoai An',
            // 'Pham Viet Thanh',
            // 'Nguyen Van Hao'
        ];
        return view('home', $this->data);
    }
}
