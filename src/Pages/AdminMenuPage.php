<?php

namespace AntonioKadid\WordPressKit\Pages;

use AntonioKadid\WordPressKit\Assets\Asset;
use Closure;

/**
 * AdminMenuPage.
 *
 * @package AntonioKadid\WordPressKit\Pages
 */
abstract class AdminMenuPage {
    /** @var string */
    private $adminHook;

    /** @var Asset[] */
    private $assets = [];

    /** @var AdminMenuPage */
    private $parentPage = null;

    /** @var AdminMenuPage[] */
    private $submenuPages = [];

    /** @var string */
    public $capability;

    /** @var string */
    public $icon;

    /** @var string */
    public $menuTitle;

    /** @var string */
    public $slug;

    /** @var string */
    public $title;

    /**
     * @param Asset $asset
     *
     * @return AdminMenuPage
     */
    public function addAsset(Asset $asset): AdminMenuPage {
        array_push($this->assets, $asset);

        return $this;
    }

    /**
     * @param AdminMenuPage $page
     *
     * @return AdminMenuPage
     */
    public function addSubmenuPage(AdminMenuPage $page): AdminMenuPage {
        $page->parentPage = $this;
        array_push($this->submenuPages, $page);

        return $this;
    }

    /**
     * Register page and any available subpages.
     */
    public function register(): void {
        if ( ! is_admin()) {
            return;
        }

        add_action(
            'admin_enqueue_scripts',
            Closure::bind(
                function($admin_hook) {
                    $this->enqueueAssets($admin_hook);
                },
                $this
            )
        );

        if ($this->parentPage instanceof AdminMenuPage) {
            $this->adminHook = add_submenu_page(
                $this->parentPage->slug,
                $this->title,
                $this->menuTitle,
                $this->capability,
                $this->slug,
                Closure::bind(
                    function() {
                        $this->generateContent();
                    },
                    $this
                )
            );

            return;
        }

        $this->adminHook = add_menu_page(
            $this->title,
            $this->menuTitle,
            $this->capability,
            $this->slug,
            Closure::bind(
                function() {
                    $this->generateContent();
                },
                $this
            ),
            $this->icon
        );

        foreach ($this->submenuPages as $submenuPage) {
            $submenuPage->register();
        }
    }

    abstract protected function generateContent(): void;

    private function enqueueAssets(string $adminHook): void {
        if ($this->adminHook !== $adminHook || empty($this->assets)) {
            return;
        }

        foreach ($this->assets as $asset) {
            $asset->enqueue();
        }
    }
}
