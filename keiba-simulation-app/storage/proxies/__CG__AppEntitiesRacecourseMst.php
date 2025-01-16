<?php

namespace DoctrineProxies\__CG__\App\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class RacecourseMst extends \App\Entities\RacecourseMst implements \Doctrine\ORM\Proxy\InternalProxy
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
        "\0".parent::class."\0".'id' => [parent::class, 'id', null],
        "\0".parent::class."\0".'jyoCd' => [parent::class, 'jyoCd', null],
        "\0".parent::class."\0".'raceInfo' => [parent::class, 'raceInfo', null],
        "\0".parent::class."\0".'racecourseName' => [parent::class, 'racecourseName', null],
        'id' => [parent::class, 'id', null],
        'jyoCd' => [parent::class, 'jyoCd', null],
        'raceInfo' => [parent::class, 'raceInfo', null],
        'racecourseName' => [parent::class, 'racecourseName', null],
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
