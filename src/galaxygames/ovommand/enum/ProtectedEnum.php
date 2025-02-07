<?php
declare(strict_types=1);

namespace galaxygames\ovommand\enum;

use galaxygames\ovommand\exception\EnumException;
use galaxygames\ovommand\utils\MessageParser;

// This was made so users would know what went wrong.
class ProtectedEnum extends \shared\galaxygames\ovommand\fetus\enum\ProtectedEnum{
	public function addAliases(array $aliases, bool $isHidden = false) : void{
		throw new EnumException(MessageParser::EXCEPTION_ENUM_ADD_PROTECTED_ALIAS->translate(['enumName' => $this->getName()]), EnumException::ENUM_EDIT_PROTECTED_ENUM); //TODO: change msg
	}

	public function removeAliases(array $aliases, bool $isHidden = false) : void{
		throw new EnumException(MessageParser::EXCEPTION_ENUM_REMOVE_PROTECTED_ALIAS->translate(['enumName' => $this->getName()]), EnumException::ENUM_EDIT_PROTECTED_ENUM); //TODO: change msg
	}

	public function removeValue(string $key) : void{
		$this->removeValues([]);
	}
	public function removeValuesBySpreading(string ...$keys) : void{
		$this->removeValues($keys);
	}

	/** @param string[] $context */
	public function removeValues(array $context) : void{
		throw new EnumException(MessageParser::EXCEPTION_ENUM_REMOVE_PROTECTED_VALUE->translate(['enumName' => $this->getName()]), EnumException::ENUM_EDIT_PROTECTED_ENUM); //TODO: change msg
	}

	/**
	 * @param string|string[] $showAliases
	 * @param string|string[] $hiddenAliases
	 */
	public function addValue(string $value, mixed $bindValue = null, array|string $showAliases = [], array|string $hiddenAliases = []) : void{
		$this->addValues([]);
	}

	/**
	 * @param array<string, mixed> $context
	 * @param array<string, string|string[]> $showAliases
	 * @param array<string, string|string[]> $hiddenAliases
	 */
	public function addValues(array $context, array $showAliases = [], array $hiddenAliases = []) : void{
		throw new EnumException(MessageParser::EXCEPTION_ENUM_ADD_PROTECTED_VALUE->translate(['enumName' => $this->getName()]), EnumException::ENUM_EDIT_PROTECTED_ENUM); //TODO: change msg
	}

	public function changeValue(string $key, mixed $value) : void{
		throw new EnumException(MessageParser::EXCEPTION_ENUM_REMOVE_PROTECTED_VALUE->translate(['key' => $key, 'enumName' => $this->getName()]), EnumException::ENUM_EDIT_PROTECTED_ENUM); //TODO: change msg
	}
}
