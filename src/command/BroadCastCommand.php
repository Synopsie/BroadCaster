<?php
declare(strict_types=1);

namespace broadcaster\command;

use broadcaster\Main;
use iriss\CommandBase;
use iriss\parameters\TextParameter;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\Server;

class BroadCastCommand extends CommandBase {

    public function __construct(
        string $name,
        Translatable|string $description,
        string $usageMessage,
        array $subCommands = [],
        array $aliases = []
    ) {
        parent::__construct($name, $description, $usageMessage, $subCommands, $aliases);
        $this->setPermission(Main::getInstance()->getConfig()->getNested('command.permission.name'));
    }

    public function getCommandParameters() : array {
        return [
            new TextParameter('message', false)
        ];
    }

    protected function onRun(CommandSender $sender, array $parameters) : void {
        Server::getInstance()->broadcastMessage(str_replace(['%message%', '%player%'], [$parameters['message'], $sender->getName()], Main::getInstance()->getConfig()->get('broadcast.format')));
    }
}