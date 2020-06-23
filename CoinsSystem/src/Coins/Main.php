<?php


namespace Coins;


use Coins\Commands\AddCoinsCommand;
use Coins\Commands\CoinsCommand;
use Coins\Commands\PayCoinsCommand;
use Coins\Commands\RemCoinsCommand;
use Coins\Commands\SetCoinsCommand;
use Coins\Commands\ToggleCoinsCommand;
use Coins\Listeners\JoinListener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase
{
    private $API;

    const PREFIX = "§aCoinsSystem §8> §7";

    public function onEnable()
    {
        $this->saveDefaultConfig();

        $this->getLogger()->info(self::PREFIX . "§aActivated!");

        Server::getInstance()->getPluginManager()->registerEvents(new JoinListener($this), $this);
        Server::getInstance()->getCommandMap()->register("coins", new CoinsCommand($this));
        Server::getInstance()->getCommandMap()->register("addcoins", new AddCoinsCommand($this));
        Server::getInstance()->getCommandMap()->register("paycoins", new PayCoinsCommand($this));
        Server::getInstance()->getCommandMap()->register("remcoins", new RemCoinsCommand($this));
        Server::getInstance()->getCommandMap()->register("setcoins", new SetCoinsCommand($this));
        Server::getInstance()->getCommandMap()->register("togglecoins", new ToggleCoinsCommand($this));

        $this->API = new CoinsAPI($this);

        if ($this->getConfig()->get("ConfigMode") === "Data") {

            @mkdir($this->getDataFolder() . "/players/");

        } elseif ($this->getConfig()->get("ConfigMode") === "Cloud") {

            @mkdir("/home/Datenbank/");
            @mkdir("/home/Datenbank/CoinsSystem/");
            @mkdir("/home/Datenbank/CoinsSystem" . "/players/");

        }
    }

    public function onDisable()
    {
        $this->getLogger()->info(self::PREFIX . "§cDeactivated!");
    }

}