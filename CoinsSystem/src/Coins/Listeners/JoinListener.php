<?php


namespace Coins\Listeners;


use Coins\CoinsAPI;
use Coins\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use pocketmine\utils\Config;

class JoinListener implements Listener
{
    private $plugin, $API;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->API = new CoinsAPI($this->plugin);
    }

    public function onJoin(PlayerJoinEvent $event) {

        $player = $event->getPlayer();

        $config = $this->plugin->getConfig();

        $pconf = new Config($this->API->getCoins() . $player->getName() . ".yml", Config::YAML);

        if (!$pconf->exists("Coins")) {
            $cplay = new Config($this->API->getCoins() . $player->getName() . ".yml", Config::YAML);
            $cplay->set('Coins', $config->get("StartCoins"));
            $cplay->set("CoinsToggled", false);
            $cplay->save();
        }

    }

}