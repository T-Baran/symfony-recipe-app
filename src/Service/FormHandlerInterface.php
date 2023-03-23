<?php

namespace App\Service;

interface FormHandlerInterface
{
    public const FormType = '';

    public function createDTO();

    public function saveRecord($DTO, $id);

    public function flushRecord($object);

    public function getLocation();
}