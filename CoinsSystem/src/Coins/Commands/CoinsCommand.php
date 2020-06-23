<?php


namespace Coins\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use Coins\CoinsAPI;
use Coins\Main;

class CoinsCommand extends Command
{
    private $plugin;
    private $API;

    public function __construct(Main $plugin)
    {
        parent::__construct("coins", "See your Coins!", "/coins <player>");
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
                if (isset($args[0])) {
                    $target = $sender->getServer()->getPlayer($args[0]);
                    if ($target->isOnline()) {
                        $tcoins = new Config($cfg . $target->getName() . ".yml", Config::YAML);
                        if (!$tcoins->get("CoinsToggled")) {
                            $sender->sendMessage($config->get("CoinsPrefix") . $target->getName() . " " . $config->get("HasCoinsMessage") . " " . $tcoins->get("Coins") . " Coins!");
                        } else {
                            $sender->sendMessage($config->get("CoinsPrefix") . $config->get("CoinsArePrivateMessage"));
                        }
                    } else {
                        $sender->sendMessage($config->get("CoinsPrefix") . $config->get("PlayerIsOffline"));
                    }
                } else {

                    $sender->sendMessage($config->get("CoinsPrefix") . $config->get("YourCoinsMessage") . " " . $folder->get("Coins") . " Coins!");

                }
            }
        }
    }
}