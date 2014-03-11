<?php

class Bedrijfsinfo {

    private static $idMap = array();
    private $id;
    private $naam;
    private $adres;
    private $postcode_id;
    private $email;
    private $telefoon;
    private $gsm;
    private $lev_vw;
    private $openinguren;
    private $logo;
    private $alg_info;
    private $promotie;
    private $faq_info;
    private $postcode;

    private function __construct($id, $naam, $adres, $email, $telefoon, $gsm, $lev_vw, $openinguren, $postcode_id, 
                                 $logo, $alg_info, $promotie, $faq_info, $postcode) {
        $this->id = $id;
        $this->naam = $naam;
        $this->adres = $adres;
        $this->email = $email;
        $this->telefoon = $telefoon;
        $this->gsm = $gsm;
        $this->lev_vw = $lev_vw;
        $this->openinguren = $openinguren;
        $this->postcode_id = $postcode_id;
        $this->logo = $logo;
        $this->alg_info = $alg_info;
        $this->promotie = $promotie;
        $this->faq_info = $faq_info;
        $this->postcode = $postcode;
    }

    public static function create($id, $naam, $adres, $email, $telefoon, $gsm, $lev_vw, $openinguren, 
                                  $postcode_id, $logo, $alg_info, $promotie, $faq_info, $postcode) 
    {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Bedrijfsinfo($id, $naam, $adres, $email, $telefoon, $gsm, $lev_vw, $openinguren, 
                                  $postcode_id, $logo, $alg_info, $promotie, $faq_info, $postcode);
        }
        return self::$idMap[$id];
    }

    public function getId() {
        return $this->id;
    }

    public function getNaam() {
        return $this->naam;
    }

    public function getAdres() {
        return $this->adres;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefoon() {
        return $this->telefoon;
    }

    public function getGsm() {
        return $this->gsm;
    }

    public function getLev_vw() {
        return $this->lev_vw;
    }

    public function getOpeninguren() {
        return $this->openinguren;
    }

    public function getPostcode_id() {
        return $this->postcode_id;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getAlg_info() {
        return $this->alg_info;
    }

    public function getPromotie() {
        return $this->promotie;
    }
    public function getFaq_info() {
        return $this->faq_info;
    }

    public function getPostcode() {
        return $this->postcode;
    }

    public function setNaam($naam) {
        $this->naam = $naam;
    }

    public function setAdres($adres) {
        $this->adres = $adres;
    }

    public function setTelefoon($telefoon) {
        $this->telefoon = $telefoon;
    }
    public function setGsm($gsm) {
        $this->gsm = $gsm;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setLev_vw($lev_vw) {
        $this->lev_vw = $lev_vw;
    }

    public function setOpeninguren($openinguren) {
        $this->openinguren = $openinguren;
    }

    public function setPostcode_id($postcode_id) {
        $this->postcode_id = $postcode_id;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }

    public function setAlg_info($alg_info) {
        $this->alg_info = $alg_info;
    }

    public function setPromotie($promotie) {
        $this->promotie = $promotie;
    }
    public function setFaq_info($faq_info) {
        $this->faq_info = $faq_info;
    }
    
}

