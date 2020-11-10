<?php

namespace AntonioKadid\WordPressKit\Assets;

/**
 * Style.
 *
 * @package AntonioKadid\WordPressKit\Assets
 */
class Style extends Asset {
    /** @var string[] */
    private $dependencies;

    /** @var string */
    private $media;

    /** @var string */
    private $src;

    /** @var null|bool|string */
    private $version;

    /**
     * @param string           $src
     * @param string[]         $dependencies
     * @param null|bool|string $version
     * @param string           $media
     */
    public function __construct(string $src = '', array $dependencies = [], $version = false, $media = 'all') {
        parent::__construct(uniqid(sprintf('antoniokadid.wordpresskit.assets.style.%s', md5($src)), true));

        $this->src          = $src;
        $this->dependencies = $dependencies;
        $this->version      = $version;
        $this->media        = $media;
    }

    public function dequeue(): void {
        if ( ! $this->enqueued()) {
            return;
        }

        wp_dequeue_style($this->handle);
    }

    public function deregister(): void {
        if ( ! $this->registered()) {
            return;
        }

        wp_deregister_style($this->handle);
    }

    public function enqueue(): void {
        if ( ! $this->registered()) {
            wp_enqueue_style($this->handle, $this->src, $this->dependencies, $this->version, $this->media);
        } else {
            wp_enqueue_style($this->handle);
        }
    }

    public function enqueued(): bool {
        return wp_style_is($this->handle, 'enqueued');
    }

    public function register(): void {
        if ($this->registered()) {
            return;
        }

        wp_register_style($this->handle, $this->src, $this->dependencies, $this->version, $this->media);
    }

    public function registered(): bool {
        return wp_style_is($this->handle, 'registered');
    }
}
