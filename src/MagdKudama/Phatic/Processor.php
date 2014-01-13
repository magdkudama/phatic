<?php

namespace MagdKudama\Phatic;

interface Processor
{
    function getCollection();

    /**
     * @return string
     */
    function getName();

    /**
     * @return void
     */
    function dump($element);
}