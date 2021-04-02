<?php 


class User {
    private $ID;
    private $progression; 

    public function __contruct() {}

    public function setID(int $ID) : self 
    {
        $this->ID = $ID;

        return $this;
    }

    public function getID() : int 
    {
        return $this->ID;
    }

    public function setCurrentProgression(int $points) : self 
    {
        $this->progression = $points;

        return $this;
    }

    public function getCurrentProgression() : int 
    {
        return $this->progression;
    }
}