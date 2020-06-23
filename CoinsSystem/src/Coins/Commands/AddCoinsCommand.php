<?php


namespace Coins\Commands;

use Coins\CoinsAPI;
use Coins\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class AddCoinsCommand extends Command
{
    private $plugin;
    private $API;

    public function __construct(Main $plugin)
    {
        parent::__construct("addcoins", "Add Coins to an Player!", "/addcoins <name> <coins>");
        $this->plugin = $plugin;
        $this->API = new CoinsAPI($this->plugin);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = $this->plugin->getConfig();
        if ($this->API instanceof CoinsAPI) {
            $cfg = $this->API->getCoins();
            $folder = new Config($cfg . $sender->getName() . ".yml", Config::YAML);
            if ($sender->hasPermission("system.addcoins")) {

                if (!isset($args[0])) {

                    $sender->sendMessage($config->get("CoinsPrefix") . "/addcoins <player> <coins>");

                } else {

                    $target = $sender->getServer()->getPlayer($args[0]);

                    if ($target->isOnline()) {

                        if (isset($args[1])) {

                            $targetfolder = new Config($cfg . $sender->getName() . ".yml", Config::YAML);

                            $get = $targetfolder->get("Coins") + $args[1];
                            $targetfolder->set("Coins", $get);
                            $targetfolder->save();

                            $sender->sendMessage($config->get("CoinsPrefix") . $config->get("AddMoneyMessage") . $args[1] . " Coins!");
                            $target->sendMessage($config->get("CoinsPrefix") . "§a+ " . $args[1] . " Coins!");

                        }
                    } else {
                        $sender->sendMessage($config->get("CoinsPrefix") . $config->get("PlayerIsOffline"));
                    }
                }
            } else {
                $sender->sendMessage($config->get("CoinsPrefix") . $config->get("NoPerms"));
            }
        }
    }
}