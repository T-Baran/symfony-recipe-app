<?php

namespace App\Service\FormManagers;

interface FormHandlerInterface
{
    public const FORM_TYPE = '';

    public function createDTO($id);

    public function saveRecord($DTO);

    public function flushRecord($object);

    public function getLocation();
}