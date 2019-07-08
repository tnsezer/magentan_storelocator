<?php

namespace Magentan\StoreLocator\Api\Data;

/**
 * Interface StoreLocatorInterface
 */
interface StoreLocatorInterface
{

    /**
     * Get the store's id
     *
     * @return string
     */
    public function getId();

    /**
     * Get the store's name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the store's status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Get the store's address
     *
     StoreLocatorInterface* @return string
     */
    public function getAddress();

    /**
     * Get the store's phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Get the store's city
     *
     * @return string
     */
    public function getCity();

    /**
     * Get the store's country
     *
     * @return string
     */
    public function getCountry();

    /**
     * Get the store's email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get the store's website
     *
     * @return string
     */
    public function getWebsite();

    /**
     * Get the store's position's latitude
     *
     * @return string
     */
    public function getPosition();
}