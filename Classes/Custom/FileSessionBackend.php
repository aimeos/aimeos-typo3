<?php


namespace \Aimeos\Aimeos\Custom;


class FileSessionBackend extends \TYPO3\CMS\Core\Session\Backend\SessionBackendInterface
{
	public function initialize(string $identifier, array $configuration)
	{
	}


	public function validateConfiguration()
	{
		return true;
	}


	public function getAll(): array
	{
		return $_SESSION;
	}


	public function get(string $sessionId): array
	{
		return (array) (isset( $_SESSION[$sessionId] ) ? $_SESSION[$sessionId] : []);
	}


	public function remove(string $sessionId): bool
	{
		unset($_SESSION[$sessionId]);
		return true;
	}


	public function set(string $sessionId, array $sessionData): array
	{
		$_SESSION[$sessionId] = $sessionData;
		return $sessionData;
	}


	public function update(string $sessionId, array $sessionData): array
	{
		$_SESSION[$sessionId] = $sessionData;
		return $sessionData;
	}


	public function collectGarbage(int $maximumLifetime, int $maximumAnonymousLifetime = 0)
	{
	}
}
