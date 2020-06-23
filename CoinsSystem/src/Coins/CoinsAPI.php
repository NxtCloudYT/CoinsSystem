<?php


namespace Coins;

use pocketmine\utils\Config;

class CoinsAPI
{
    private $coins;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;

        if ($this->plugin->getConfig()->get("ConfigMode") === "Data") {

            $this->coins = $this->plugin->getDataFolder() . "/players/";

        } elseif ($this->plugin->getConfig()->get("ConfigMode") === "Cloud") {

            $this->coins = "/home/Datenbank/CoinsSystem/players/";

        }

    }

    public function getCoins()
    {
        return $this->coins;
    }
}