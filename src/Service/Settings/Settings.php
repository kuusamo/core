<?php

namespace Kuusamo\Vle\Service\Settings;

use Kuusamo\Vle\Entity\Setting;
use Doctrine\ORM\EntityManager;

class Settings
{
    private $settings;
    private $db;

    /**
     * Load the settings.
     *
     * @param EntityManager $db Database.
     */
    public function __construct(EntityManager $db)
    {
        $this->db = $db;
        $settings = $this->db->getRepository('Kuusamo\Vle\Entity\Setting')->findBy([]);

        foreach ($settings as $setting) {
            $this->settings[$setting->getKey()] = $setting;
        }
    }

    /**
     * Get a setting.
     *
     * @param string $key Key.
     * @return mixed
     */
    public function get(string $key)
    {
        if (isset($this->settings[$key])) {
            return $this->settings[$key]->getValue();
        }

        return null;
    }

    /**
     * Update a setting.
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     * @return void
     */
    public function update(string $key, $value)
    {
        if (isset($this->settings[$key])) {
            $this->settings[$key]->setValue($value);
        } else {
            $setting = new Setting;
            $setting->setKey($key);
            $setting->setValue($value);
            $this->db->persist($setting);
            $this->settings[$key] = $setting;
        }
    }

    /**
     * Persist settings.
     *
     * !return void
     */
    public function save()
    {
        $this->db->flush();
    }
}
