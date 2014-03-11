<?php

class Bestelling_hdr {

    private static $idMap = array();
    private $id;
    private $gebr_id;
    private $dt_best;
    private $thuis_lev;
    private $uur_lev;
    private $status;
    private $extra_kost_lev;
    private $gebr;
    

    private function __construct($id, $gebr_id, $dt_best, 
                                 $thuis_lev, $uur_lev, $status, 
                                 $extra_kost_lev, $gebr) {
        $this->id               = $id;
        $this->gebr_id          = $gebr_id;
        $this->dt_best          = $dt_best;
        $this->thuis_lev        = $thuis_lev;
        $this->uur_lev          = $uur_lev;
        $this->status           = $status;
        $this->extra_kost_lev   = $extra_kost_lev;
        $this->gebr   = $gebr;
    }

    public static function create($id, $gebr_id, $dt_best, 
                                  $thuis_lev, $uur_lev, $status, 
                                  $extra_kost_lev, $gebr ) 
    {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Bestelling_hdr($id, $gebr_id, $dt_best, 
                                                   $thuis_lev, $uur_lev, $status, 
                                                   $extra_kost_lev, $gebr);
        }
        return self::$idMap[$id];
    }

    public function getId() {
        return $this->id;
    }

    public function getGebr_id() {
        return $this->gebr_id;
    }

    public function getDt_best() {
        return $this->dt_best;
    }

    public function getThuis_lev() {
        return $this->thuis_lev;
    }

    public function getUur_lev() {
        return $this->uur_lev;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getExtra_kost_lev() {
        return $this->extra_kost_lev;
    }

    public function setGebr_id($gebr_id) {
        $this->gebr_id = $gebr_id;
    }

    public function getGebr() {
        return $this->gebr;
    }
    
    public function setDt_best($dt_best) {
        $this->dt_best = $dt_best;
    }

    public function setThuis_lev($thuis_lev) {
        $this->thuis_lev = $thuis_lev;
    }

    public function setUur_lev($uur_lev) {
        $this->uur_lev = $uur_lev;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setExtra_kost_lev($extra_kost_lev) {
        $this->extra_kost_lev = $extra_kost_lev;
    }

    public function setGebr($gebr) {
        $this->gebr = $gebr;
    }
    
    
}

