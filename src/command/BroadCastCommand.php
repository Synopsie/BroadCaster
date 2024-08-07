<?php

/*
 *  ____   __   __  _   _    ___    ____    ____    ___   _____
 * / ___|  \ \ / / | \ | |  / _ \  |  _ \  / ___|  |_ _| | ____|
 * \___ \   \ V /  |  \| | | | | | | |_) | \___ \   | |  |  _|
 *  ___) |   | |   | |\  | | |_| | |  __/   ___) |  | |  | |___
 * |____/    |_|   |_| \_|  \___/  |_|     |____/  |___| |_____|
 *
 * Ce plugin fera en sorte d'écrire des messages automatiques au moment souhaitez et entièremenet configurable.
 *
 * @author Synopsie
 * @link https://github.com/Synopsie
 * @version 1.1.0
 *
 */

declare(strict_types=1);

namespace broadcaster\command;

use broadcaster\Main;
use iriss\CommandBase;
use iriss\parameters\TextParameter;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\Server;
use function str_replace;
use function strtolower;

class BroadCastCommand extends CommandBase {
	public function __construct(
		string $name,
		string|Translatable $description,
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
		$type   = strtolower(Main::getInstance()->getConfig()->getNested('broadcast.type'));
		$format = str_replace(['%message%', '%player%'], [$parameters['message'], $sender->getName()], Main::getInstance()->getConfig()->getNested('broadcast.format'));
		if ($type === 'popup') {
			Server::getInstance()->broadcastPopup($format);
		} elseif($type === 'tip') {
			Server::getInstance()->broadcastTip($format);
		} elseif($type === 'actionbar') {
			foreach (Server::getInstance()->getOnlinePlayers() as $player) {
				$player->sendActionBarMessage($format);
			}
		} else {
			Server::getInstance()->broadcastMessage($format);
		}
	}
}
