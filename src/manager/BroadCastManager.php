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

namespace broadcaster\manager;

use broadcaster\Main;
use broadcaster\utils\BroadCastMessage;
use pocketmine\scheduler\ClosureTask;

use function count;
use function is_callable;

class BroadCastManager {
	/** @var BroadCastMessage[] */
	private array $messages     = [];
	private int $currentMessage = 0;
	private Main $main;
	private ClosureTask $closureTask;

	public function __construct(
		Main $main
	) {
		$this->main = $main;
	}

	public function registerMessage(BroadCastMessage ...$message) : void {
		foreach ($message as $msg) {
			$this->messages[] = $msg;
		}
	}

	private function getNextMessage() : BroadCastMessage {
		$message = $this->messages[$this->currentMessage];
		$this->currentMessage++;
		if ($this->currentMessage >= count($this->messages)) {
			$this->currentMessage = 0;
		}
		return $message;
	}

	/**
	 * @return BroadCastMessage[]
	 */
	public function getMessages() : array {
		return $this->messages;
	}

	public function setup() : void {
		$scheduler = $this->main->getScheduler();
		$interval  = $this->main->getConfig()->get('broadcastMessage.Interval');
		foreach ($this->getMessages() as $message) {
			if (!$message->isExclusive()) {
				$scheduler->scheduleRepeatingTask(
					$this->getClosureTask(),
					$interval * 60 * 20
				);
			} else {
				$scheduler->scheduleRepeatingTask(
					$this->getClosureTask(),
					$message->getTime() * 60 * 20
				);
			}
		}
	}

	private function getClosureTask() : ClosureTask {
		if (!isset($this->closureTask)) {
			return $this->closureTask = new ClosureTask(
				function () : void {
					$message = $this->getNextMessage();
					$msg     = $message->getMessage();
					if (is_callable($msg)) {
						$msg = $msg();
					}
					$this->main->getServer()->broadcastMessage($msg);
				}
			);
		} else {
			return $this->closureTask;
		}
	}
}
