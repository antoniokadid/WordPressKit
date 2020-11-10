<?php

namespace AntonioKadid\WordPressKit\Assets;

/**
 * Script.
 *
 * @package AntonioKadid\WordPressKit\Assets
 */
class Script extends Asset {
    /** @var string[] */
    private $dependencies;

    /** @var bool */
    private $inFooter;

    /** @var string */
    private $src;

    /** @var null|bool|string */
    private $version;

    /**
     * @param string           $src
     * @param string[]         $dependencies
     * @param null|bool|string $version
     * @param bool             $inFooter
     */
    public function __construct(string $src = '', array $dependencies = [], $version = false, $inFooter = false) {
        parent::__construct(uniqid(sprintf('antoniokadid.wordpresskit.assets.script.%s', md5($src)), true));

        $this->src          = $src;
        $this->dependencies = $dependencies;
        $this->version      = $version;
        $this->inFooter     = $inFooter;
    }

    public function dequeue(): void {
        if ( ! $this->enqueued()) {
            return;
        }

        wp_dequeue_script($this->handle);
    }

    public function deregister(): void {
        if ( ! $this->registered()) {
            return;
        }

        wp_deregister_script($this->handle);
    }

    public function enqueue(): void {
        if ( ! $this->registered()) {
            wp_enqueue_script($this->handle, $this->src, $this->dependencies, $this->version, $this->inFooter);
        } else {
            wp_enqueue_script($this->handle);
        }
    }

    public function enqueued(): bool {
        return wp_script_is($this->handle, 'enqueued');
    }

    public function register(): void {
        if ($this->registered()) {
            return;
        }

        wp_register_script($this->handle, $this->src, $this->dependencies, $this->version, $this->inFooter);
    }

    public function registered(): bool {
        return wp_script_is($this->handle, 'registered');
    }
}
