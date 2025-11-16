<?php
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use App\Models\Hotel; 

class HomeController extends Controller 
{
     public function index() 
    {
        $hotels = Hotel::all();
        return view('home', compact('hotels')); 
    }
} 