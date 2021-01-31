<?php
namespace THN\SDK\HTTP;

interface UserAgentInterface
{
    public function addAgentField(string $name, string $value): void;

    public function getAgentString(): string;
}