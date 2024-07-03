<?php

namespace App\DTO;

class ProxyResult
{
    protected string $proxy;

    protected int $proxyType;

    protected string $location;

    protected int $timeout;

    protected string $realIp;

    public function getProxy(): string
    {
        return $this->proxy;
    }

    public function setProxy(string $proxy): static
    {
        $this->proxy = $proxy;
        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function getProxyType(): int
    {
        return $this->proxyType;
    }

    public function setProxyType(int $proxyType): static
    {
        $this->proxyType = $proxyType;
        return $this;
    }

    public function getRealIp(): string
    {
        return $this->realIp;
    }

    public function setRealIp(string $realIp): static
    {
        $this->realIp = $realIp;
        return $this;
    }
}
