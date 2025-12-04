<?php

namespace PostTypeStudio\Domain\Model;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FieldGroup {
    public string $group_id;
    public string $title;
    public array $locations;
    public array $fields;

    public function __construct( array $data ) {
        $this->group_id = $data['group_id'];
        $this->title    = $data['title'];
        $this->locations = $data['locations'] ?? [];
        $this->fields    = $data['fields'] ?? [];
    }

    public function to_array(): array {
        return [
            'group_id'  => $this->group_id,
            'title'     => $this->title,
            'locations' => $this->locations,
            'fields'    => $this->fields,
        ];
    }
}
