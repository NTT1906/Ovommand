<?php
declare(strict_types=1);

namespace shared\galaxygames\ovommand;

use shared\galaxygames\ovommand\exception\OvommandEnumPoolException;
use shared\galaxygames\ovommand\fetus\enum\IDynamicEnum;
use shared\galaxygames\ovommand\fetus\enum\IStaticEnum;
use shared\galaxygames\ovommand\fetus\enum\ProtectedEnum;
use shared\galaxygames\ovommand\fetus\IHookable;

final class GlobalEnumPool{
	/** @var array<string, IStaticEnum> */
	private static array $hardEnums = [];
	/** @var array<string, IHookable> */
	private static array $hardEnumHooker = [];
	/** @var array<string, IDynamicEnum> */
	private static array $softEnums = [];
	/** @var array<string, IHookable> */
	private static array $softEnumHooker = [];

	public static function addEnums(IHookable $hookable, IDynamicEnum|IStaticEnum ...$enums) : void{
		if (!GlobalHookPool::isHookRegistered($hookable)) {
			throw new OvommandEnumPoolException("Hook is not registered!", OvommandEnumPoolException::ENUM_UNREGISTERED_HOOK);
		}
		foreach ($enums as $enum) {
			$eName = $enum->getName();
			if ($enum->isSoft()) {
				$enumPool = &self::$softEnums;
				$enumHooker = &self::$softEnumHooker;
			} else {
				$enumPool = &self::$hardEnums;
				$enumHooker = &self::$hardEnumHooker;
			}
			if (isset($enumPool[$eName])) {
				if ($enum->isDefault()) {
					continue;
				}
				throw new OvommandEnumPoolException("Enum with the same name is already existed!", OvommandEnumPoolException::ENUM_ALREADY_EXISTED);
			}
			$enumPool[$eName] = $enum;
			$enumHooker[$eName] = $hookable;
		}
	}

	/**
	 * Retrieves a hard enum based on the enum name. If the enum is protected and the hook is not the owner, it returned
	 * a protected/copy version of the enum.
	 *
	 * @param string     $key      The hard enum name.
	 * @param ?IHookable $hookable The optional hookable instance for permission verification, default=null
	 *
	 * @return ?IStaticEnum|ProtectedEnum Returns the hard enum as an `IStaticEnum` or `ProtectedEnum`, or `null` if no
	 * 									  enum with that name is found.
	 */
	public static function getHardEnum(string $key, ?IHookable $hookable = null) : IStaticEnum | ProtectedEnum | null{
		$eHook = self::$hardEnumHooker[$key] ?? null;
		if ($eHook !== null && ($eHook === $hookable || !$eHook::isPrivate())) {
			$enum = self::$hardEnums[$key];
			return $enum->isProtected() && $eHook !== $hookable ? $enum->asProtected() : $enum;
		}
		return null;
	}

	/**
	 * Retrieves a hard enum based on the enum name. If the enum is protected and the hook is not the owner, it returned
	 * a protected/copy version of the enum.
	 *
	 * @param string 	 $key      The soft enum name.
	 * @param ?IHookable $hookable The optional hookable instance for permission verification, default=null
	 *
	 * @return ?IDynamicEnum|ProtectedEnum Returns the soft enum as an `IDynamicEnum` or `ProtectedEnum`, or `null` if
	 * 									   no enum with that name is found.
	 */
	public static function getSoftEnum(string $key, ?IHookable $hookable = null) : IDynamicEnum | ProtectedEnum | null{
		$eHook = self::$softEnumHooker[$key] ?? null;
		if ($eHook !== null && ($eHook === $hookable || !$eHook::isPrivate())) {
			$enum = self::$softEnums[$key];
			return $enum->isProtected() && $eHook !== $hookable ? $enum->asProtected() : $enum;
		}
		return null;
	}

	public static function isHardEnumRegistered(string $key) : bool{
		return isset(self::$hardEnums[$key]);
	}

	public static function isSoftEnumRegistered(string $key) : bool{
		return isset(self::$softEnums[$key]);
	}

	/** @return array<string,IDynamicEnum> */
	public static function getHookerRegisteredSoftEnums(IHookable $hookable) : array{
		if ($hookable::isPrivate()) {
			return [];
		}
		$results = [];
		foreach (self::$softEnumHooker as $eName => $hook) {
			if ($hook === $hookable) {
				$results[$eName] = self::$softEnums[$eName];
			}
		}
		return $results;
	}

	/** @return array<string,IStaticEnum> */
	public static function getHookerRegisteredHardEnums(IHookable $hookable) : array{
		if ($hookable::isPrivate()) {
			return [];
		}
		$results = [];
		foreach (self::$hardEnumHooker as $eName => $hook) {
			if ($hook === $hookable) {
				$results[$eName] = self::$hardEnums[$eName];
			}
		}
		return $results;
	}
}
