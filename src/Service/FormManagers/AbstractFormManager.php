<?php

namespace App\Service\FormManagers;

abstract class AbstractFormManager implements FormHandlerInterface
{
    protected int $id;

    protected ?object $record = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRecord()
    {
        return $this->record;
    }

    public function setRecord($record): void
    {
        $this->record = $record;
    }


}