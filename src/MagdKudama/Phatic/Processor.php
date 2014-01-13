<?php

namespace MagdKudama\Phatic;

interface Processor
{
    function getCollection();

    function getName();

    function dump($element);
}