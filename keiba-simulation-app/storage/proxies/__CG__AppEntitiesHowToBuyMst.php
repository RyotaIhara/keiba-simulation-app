<?php

namespace DoctrineProxies\__CG__\App\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class HowToBuyMst extends \App\Entities\HowToBuyMst implements \Doctrine\ORM\Proxy\InternalProxy
{
    use \Symfony\Component\VarExporter\LazyGhostTrait {
        initializeLazyObject as private;
        setLazyObjectAsInitialized as public __setInitialized;
        isLazyObjectInitialized as private;
        createLazyGhost as private;
        resetLazyObject as private;
    }

    public function __load(): void
    {
        $this->initializeLazyObject();
    }
    

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'howToBuyCode' => [parent::class, 'howToBuyCode', null],
        "\0".parent::class."\0".'howToBuyName' => [parent::class, 'howToBuyName', null],
        "\0".parent::class."\0".'id' => [parent::class, 'id', null],
        "\0".parent::class."\0".'isOrdered' => [parent::class, 'isOrdered', null],
        'howToBuyCode' => [parent::class, 'howToBuyCode', null],
        'howToBuyName' => [parent::class, 'howToBuyName', null],
        'id' => [parent::class, 'id', null],
        'isOrdered' => [parent::class, 'isOrdered', null],
    ];

    public function __isInitialized(): bool
    {
        return isset($this->lazyObjectState) && $this->isLazyObjectInitialized();
    }

    public function __serialize(): array
    {
        $properties = (array) $this;
        unset($properties["\0" . self::class . "\0lazyObjectState"]);

        return $properties;
    }
}
