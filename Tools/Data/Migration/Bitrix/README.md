Пример использования миграции типа почтового события:

```php
<?
use Quetzal\Exception\Data\Migration\MigrationException;
use Quetzal\Tools\Data\Migration\Bitrix\AbstractEventTypeMigration;
use Quetzal\Tools\Logger\EchoLogger;

class ExampleEventTypeMigration extends AbstractEventTypeMigration
{
	/**
	 * Применяет миграцию
	 */
	public function up()
	{
		$logger = new EchoLogger;

		$logger->log(sprintf('Info: start'));

		try {
			$this->createEventType('TEST_EVENT_TYPE', 'Тестовое почтовое событие');

			$logger->log(sprintf('Success: event type with code "TEST_EVENT_TYPE" added'));
		} catch (MigrationException $exception) {
			$logger->log(sprintf('Error: %s', $exception->getMessage()));
		}

		$logger->log(sprintf('Info: finish'));
	}

	/**
	 * Отменяет миграцию
	 */
	public function down()
	{
	}
}

$migrationType = new ExampleEventTypeMigration();
$migrationType->up();

```

Пример использования миграции типа почтового события:

```php
<?
use Quetzal\Exception\Data\Migration\MigrationException;
use Quetzal\Tools\Data\Migration\Bitrix\AbstractEventMessageMigration;
use Quetzal\Tools\Logger\EchoLogger;

class ExampleEventMessageMigration extends AbstractEventMessageMigration
{
	/**
	 * Применяет миграцию
	 */
	public function up()
	{
		$logger = new EchoLogger;

		$logger->log(sprintf('Info: start'));

		try {
			$message = '
Сообщение начинаем с новой строки, без табов, чтобы не было сдвигов в теле письма.
Почта: #EMAIL_TO#
			';

			$this->createTextEventMessage(
				'TEST_EVENT_TYPE',
				'Тестовое сообщение. Тема письма.',
				$message,
				array(
					'EMAIL_TO' => 'info@ratio.bz'
				)
			);

			$logger->log(sprintf('Success: event message with id "%s" added', $this->eventMessageId));
		} catch (MigrationException $exception) {
			$logger->log(sprintf('Error: %s', $exception->getMessage()));
		}

		$logger->log(sprintf('Info: finish'));
	}

	/**
	 * Отменяет миграцию
	 */
	public function down()
	{
	}
}

$migrationMessage = new ExampleEventMessageMigration();
$migrationMessage->up();

```
