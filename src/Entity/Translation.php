<?php

namespace App\Entity;

interface Translation
{
    public function setEntity($object);

    public function setCountry(?Country $country);

    public function getCountry();
}