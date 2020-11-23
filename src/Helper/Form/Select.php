<?php

namespace Kuusamo\Vle\Helper\Form;

class Select
{
    private $options = [];
    private $defaultOption;

    /**
     * Add an option to the select.
     *
     * @param mixed $value Value.
     * @param mixed $label Optional label, will use value if not specified.
     * @return void
     */
    public function addOption($value, $label = null)
    {
        $this->options[$value] = $label;
    }

    /**
     * Set the option to be selected by default.
     *
     * @param mixed $value Value.
     * @return void
     */
    public function setDefaultOption($value)
    {
        $this->defaultOption = $value;
    }

    /**
     * Produce a template-friendly associative array.
     *
     * @return array
     */
    public function __invoke(): array
    {
        $data = [];

        foreach ($this->options as $key => $label) {
            $data[] = [
                'value' => $key,
                'label' => $label ?? $key,
                'selected' => $key == $this->defaultOption
            ];
        }
        
        return $data;
    }
}
