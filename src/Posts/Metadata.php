<?php

namespace AntonioKadid\WordPressKit\Assets;

use ArrayAccess;
use WP_Post;

/**
 * Metadata.
 *
 * @package AntonioKadid\WordPressKit\Assets
 */
class Metadata implements ArrayAccess {
    /** @var WP_Post */
    private $post;

    private function __construct(WP_Post $post) {
        $this->post = $post;
    }

    public static function ofPost(WP_Post $post): Metadata {
        return new Metadata($post);
    }

    public static function ofPostFromId(int $id): ?Metadata {
        $post = get_post($id);
        if (null === $post) {
            return null;
        }

        return self::ofPost($post);
    }

    public function offsetExists($offset) {
        return metadata_exists('post', $this->post->ID, $offset);
    }

    public function offsetGet($offset) {
        $result = get_post_meta($this->post->ID, $offset);
        if (1 === count($result)) {
            return array_shift($result);
        }

        return $result;
    }

    public function offsetSet($offset, $value) {
        delete_post_meta($this->post->ID, $offset);

        if (is_array($value)) {
            foreach ($value as $item) {
                add_post_meta($this->post->ID, $offset, $item, false);
            }

            return $this;
        }

        add_post_meta($this->post->ID, $offset, $value, true);
    }

    public function offsetUnset($offset) {
        delete_post_meta($this->post->ID, $offset);
    }
}
