<?php


namespace Coins\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use Coins\CoinsAPI;
use Coins\Main;
class PayCoinsCommand extends Command
{
    private $plugin;
    private $API;

    public function __construct(Main $plugin)
    {
        parent::__construct("pay", "Pay Coins to Another player!", "/pay <name> <coins>");
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

                if (!isset($args[0])) {

                    $sender->sendMessage($config->get("CoinsPrefix") . "/pay <player> <coins>");

                } else if ($target = $sender->getServer()->getPlayer($args[0])) {

                    $targetfolder = new Config($cfg . $target->getName() . ".yml", Config::YAML);

                    if ($args[1] >= $folder->get("Coins") or $folder->get("Coins") <= 1) {

                        $sender->sendMessage($config->get("CoinsPrefix") . $config->get("NotEnoughtCoins"));

                    } elseif ($args[1] <= 0) {
                        $sender->sendMessage($config->get("CoinsPrefix") . $config->get("ErrorMessage"));
                    } else {
                        $send = $folder->get("Coins") - $args[1];
                        $get = $targetfolder->get("Coins") + $args[1];

                        $targetfolder->set("Coins", $get);
                        $targetfolder->save();

                        $folder->set("Coins", $send);
                        $folder->save();

                        $target->sendMessage($config->get("CoinsPrefix") . $config->get("GetCoinsMessage") . $args[1] . " Coins! " . $config->get("From") . $sender->getName());
                        $sender->sendMessage($config->get("CoinsPrefix") . $config->get("SuccesPayedMessage") . $target->getName() . " " . $args[1] . " Coins!");

                    }

                } else {
                    $sender->sendMessage($config->get("CoinsPrefix") . $config->get("PlayerIsOffline"));
                }
            }
        }
    }
}