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
 * @version 1.2.1
 *
 */

declare(strict_types=1);

namespace broadcaster\command\parameter;

use InvalidArgumentException;
use iriss\parameters\EnumParameter;
use pocketmine\command\CommandSender;

class BroadCastTypeParameter extends EnumParameter {
	public function __construct(string $name, string $enumName, bool $isOptional = false) {
		parent::__construct($name, $enumName, $isOptional);
		$this->addValue('popup', 'popup');
		$this->addValue('chat', 'chat');
		$this->addValue('toast', 'toast');
		$this->addValue('tip', 'tip');
		$this->addValue('actionbar', 'actionbar');
	}

	public function parse(string $argument, CommandSender $sender) : mixed {
		$value = $this->getValue($argument);
		if ($value === null) {
			throw new InvalidArgumentException();
		}
		return $value;
	}

}
