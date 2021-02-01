<?php
namespace THN\SDK\HTTP;

interface UserAgentInterface
{
    public function setAgentField(string $name, string $value): void;

    public function getAgentString(): string;
}