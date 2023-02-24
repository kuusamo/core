<?php

namespace Kuusamo\Vle\Proxy\__CG__\Kuusamo\Vle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Image extends \Kuusamo\Vle\Entity\Image implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'id', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'filename', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'originalFilename', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'mediaType', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'description', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'keywords', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'width', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'height'];
        }

        return ['__isInitialized__', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'id', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'filename', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'originalFilename', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'mediaType', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'description', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'keywords', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'width', '' . "\0" . 'Kuusamo\\Vle\\Entity\\Image' . "\0" . 'height'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Image $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId(): int
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setId(int $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', [$value]);

        return parent::setId($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getFilename(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFilename', []);

        return parent::getFilename();
    }

    /**
     * {@inheritDoc}
     */
    public function setFilename(string $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFilename', [$value]);

        return parent::setFilename($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginalFilename(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOriginalFilename', []);

        return parent::getOriginalFilename();
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaType(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMediaType', []);

        return parent::getMediaType();
    }

    /**
     * {@inheritDoc}
     */
    public function setMediaType(string $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMediaType', [$value]);

        return parent::setMediaType($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', []);

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(?string $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDescription', [$value]);

        return parent::setDescription($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getKeywords(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKeywords', []);

        return parent::getKeywords();
    }

    /**
     * {@inheritDoc}
     */
    public function setKeywords(?string $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKeywords', [$value]);

        return parent::setKeywords($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getWidth(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWidth', []);

        return parent::getWidth();
    }

    /**
     * {@inheritDoc}
     */
    public function setWidth(int $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWidth', [$value]);

        return parent::setWidth($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeight(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHeight', []);

        return parent::getHeight();
    }

    /**
     * {@inheritDoc}
     */
    public function setHeight(int $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHeight', [$value]);

        return parent::setHeight($value);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'jsonSerialize', []);

        return parent::jsonSerialize();
    }

}