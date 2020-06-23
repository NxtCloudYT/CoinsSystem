<?php


namespace Coins\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use Coins\CoinsAPI;
use Coins\Main;

class RemCoinsCommand extends Command
{
    private $plugin;
    private $API;

    public function __construct(Main $plugin)
    {
        parent::__construct("remcoins", "Remove Coins to an Player!", "/remcoins <name> <coins>");
        $this->plugin = $plugin;
        $this->API = new CoinsAPI($this->plugin);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = $this->plugin->getConfig();
        if ($this->API instanceof CoinsAPI) {
            $cfg = $this->API->getCoins();
            $folder = new Config($cfg . $sender->getName() . ".yml", Config::YAML);
            if ($sender instanceof Player) {
                if ($sender->hasPermission("system.remcoins")) {

                    if (!isset($args[0])) {

                        $sender->sendMessage($config->get("CoinsPrefix") . "/remcoins <player> <coins>");

                    } else {

                        $target = $sender->getServer()->getPlayer($args[0]);

                        if ($target->isOnline()) {

                            if (isset($args[1])) {

                                $targetfolder = new Config($cfg . $sender->getName() . ".yml", Config::YAML);

                                $get = $targetfolder->get("Coins") - $args[1];
                                $targetfolder->set("Coins", $get);
                                $targetfolder->save();

                                $sender->sendMessage($config->get("CoinsPrefix") . $config->get("RemMoneyMessage") . $args[1] . " Coins!");
                                $target->sendMessage($config->get("CoinsPrefix") . "§c- " . $args[1] . " Coins!");

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
}