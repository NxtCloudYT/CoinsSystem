<?php


namespace Coins\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use Coins\CoinsAPI;
use Coins\Main;

class SetCoinsCommand extends Command
{
    private $plugin;
    private $API;

    public function __construct(Main $plugin)
    {
        parent::__construct("setcoins", "Set Coins from Player!", "/setcoins <name> <coins>");
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
                if ($sender->hasPermission("system.setcoins")) {

                    if (!isset($args[0])) {

                        $sender->sendMessage($config->get("CoinsPrefix") . "/setmoney <player> <coins>");

                    } else {

                        $target = $sender->getServer()->getPlayer($args[0]);

                        if ($target->isOnline()) {

                            if (isset($args[1])) {

                                $targetfolder = new Config($cfg . $sender->getName() . ".yml", Config::YAML);

                                $targetfolder->set("Coins", $args[1]);
                                $targetfolder->save();

                                $sender->sendMessage($config->get("CoinsPrefix") . $config->get("SetCoinsMessage") . $args[1]);
                                $target->sendMessage($config->get("CoinsPrefix") . $config->get("YourMoneySetMessage") . $args[1] . " Coins!");

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