<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\handler\PacketHandler;
use pocketmine\network\mcpe\protocol\types\DimensionIds;

class SpawnParticleEffectPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SPAWN_PARTICLE_EFFECT_PACKET;

	/** @var int */
	public $dimensionId = DimensionIds::OVERWORLD; //wtf mojang
	/** @var int */
	public $entityUniqueId = -1; //default none
	/** @var Vector3 */
	public $position;
	/** @var string */
	public $particleName;

	protected function decodePayload() : void{
		$this->dimensionId = $this->buf->getByte();
		$this->entityUniqueId = $this->buf->getEntityUniqueId();
		$this->position = $this->buf->getVector3();
		$this->particleName = $this->buf->getString();
	}

	protected function encodePayload() : void{
		$this->buf->putByte($this->dimensionId);
		$this->buf->putEntityUniqueId($this->entityUniqueId);
		$this->buf->putVector3($this->position);
		$this->buf->putString($this->particleName);
	}

	public function handle(PacketHandler $handler) : bool{
		return $handler->handleSpawnParticleEffect($this);
	}
}