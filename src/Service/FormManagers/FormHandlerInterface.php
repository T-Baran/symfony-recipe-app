<?php

namespace App\Service\FormManagers;

interface FormHandlerInterface
{
    public const FORM_TYPE = '';

    public function createDTO();

    public function saveRecord($DTO, $id);

    public function flushRecord($object);

    public function getLocation();
}