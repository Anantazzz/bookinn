<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HotelCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

        public $namaHotel;
        public $gambar;
        public $kota;
        public $alamat;
        public $rating;
        public $bintang;

    public function __construct($namaHotel, $gambar = null, $kota, $alamat = null, $rating = 0, $bintang = 0)
    {
        $this->namaHotel = $namaHotel;
        $this->gambar = $gambar;
        $this->kota = $kota;
        $this->alamat = $alamat;
        $this->rating = $rating;
        $this->bintang = $bintang;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.hotel-card');
    }
}
