<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RoomCard extends Component
{
     public $gambar, $tipeKamar, $harga, $status, $kapasitas, $jumlahBed, $internet;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($gambar, $tipeKamar, $harga, $status, $kapasitas, $jumlahBed, $internet)
    {
        $this->gambar = $gambar;
        $this->tipeKamar = $tipeKamar;
        $this->harga = $harga;
        $this->status = $status;
        $this->kapasitas = $kapasitas;
        $this->jumlahBed = $jumlahBed;
        $this->internet = $internet;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.room-card');
    }
}
