<?php

namespace AntonioKadid\WordPressKit\Assets;

/**
 * Asset.
 *
 * @package AntonioKadid\WordPressKit\Assets
 */
abstract class Asset {
    /** @var string */
    protected $handle;

    protected function __construct(string $handle) {
        $this->handle = $handle;
    }

    abstract public function dequeue(): void;

    abstract public function deregister(): void;

    abstract public function enqueue(): void;

    /**
     * @return bool
     */
    abstract public function enqueued(): bool;

    /**
     * @return string
     */
    public function getHandle(): string {
        return $this->handle;
    }

    abstract public function register(): void;

    /**
     * @return bool
     */
    abstract public function registered(): bool;
}
