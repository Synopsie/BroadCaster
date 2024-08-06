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
 * @version 1.0.0
 *
 */

declare(strict_types=1);

namespace broadcaster;

use broadcaster\command\BroadCastCommand;
use broadcaster\manager\BroadCastManager;
use broadcaster\utils\BroadCastMessage;
use Exception;
use iriss\IrissCommand;
use olymp\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use sofia\Updater;

class Main extends PluginBase {
	use SingletonTrait;

	private BroadCastManager $broadcastManager;

	protected function onLoad() : void {
		self::setInstance($this);
		$this->saveResource('config.yml');

		$this->broadcastManager = new BroadCastManager($this);
	}

	/**
	 * @throws Exception
	 */
	protected function onEnable() : void {

		require $this->getFile() . 'vendor/autoload.php';

		$config = $this->getConfig();

		Updater::checkUpdate('BroadCaster', $this->getDescription()->getVersion(), 'Synopsie', 'BroadCaster');
		IrissCommand::register($this);

		foreach ($config->get('broadcasts') as $broadcast) {
			$this->getBroadCastManager()->registerMessage(new BroadCastMessage(
				$broadcast['message'],
				false,
				$broadcast['interval'],
			));
		}

		$permissionManager = new PermissionManager();
		$permissionManager->registerPermission(
			$config->getNested('command.permission.name'),
			'BroadCast',
			$permissionManager->getType($config->getNested('command.permission.default', 'operator'))
		);

		if($config->get('unregister.say.command')) {
			$this->getServer()->getCommandMap()->unregister(
				$this->getServer()->getCommandMap()->getCommand('say')
			);
		}

		$command = $config->get('command');
		$this->getServer()->getCommandMap()->register('Synopsie', new BroadCastCommand(
			$command['name'],
			$command['description'],
			$command['usage'],
			[],
			$command['aliases']
		));

		$this->getBroadCastManager()->setup();
	}

	public function getBroadCastManager() : BroadCastManager {
		return $this->broadcastManager;
	}

}
