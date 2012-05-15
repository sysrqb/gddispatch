<?php

class Patron
{
  private $name;
  private $pickup;
  private $dropoff;
  private $riders;
  private $notes;
  private $phonenumber;
  private $clothes;
  private $status;
  private $created;
  private $modified;

  public function __construct(
      $name, 
      $pickup, 
      $dropoff, 
      $riders, 
      $phonenumber,
      $clothes,
      $notes,
      $status)
  {
    $this->$name = $name;
    $this->$pickup = $pickup;
    $this->$dropoff = $dropoff;
    $this->$riders = $riders;
    $this->$phonenumber = $phonenumber;
    $this->$clothes = $clothes;
    $this->$notes = $notes;
    $this->$status = $status;
    $this->$created = gmdate('Y-m-d H:i:s');
    $this->$modified = $this->$created;
  }

  public function getName(){ return $this->$name; }
  public function getPickup(){ return $this->$pickup; }
  public function getDropoff(){ return $this->$dropoff; }
  public function getRiders(){ return $this->$riders; }
  public function getPhoneNumber(){ return $this->$phonenumber; }
  public function getClothes(){ return $this->$clothes; }
  public function getNotes(){ return $this->$notes; }
  public function getStatus(){ return $this->$status; }

  public function setName($name)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$name = $name; 
  }
  public function setPickup($pickup)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$pickup = $pickup;
  }
  public function setDropoff($dropoff)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$dropoff = $dropoff;
  }
  public function setRiders($riders)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$riders = $riders;
  }
  public function setPhoneNumber($number)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$phonenumber = $number;
  }
  public function setClothes($clothes)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$clothes = $clothes;
  }
  public function setNotes($notes)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$notes = $notes;
  }
  public function setStatus(status)
  {
    $this->$modified = gmdate('Y-m-d H:i:s');
    $this->$status = $status;
  }
