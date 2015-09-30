<?php

namespace Quetzal\Tools\Mail;

/**
 * Простая обертка для отправки писем через функцию mail
 *
 * Class SimpleMailer
 *
 * @package Quetzal\Tools\Mail
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 */
class SimpleMailer
{
	/**
	 * @var array
	 */
	protected $headers;

	/**
	 * @param array $headers
	 */
	public function __construct(array $headers = array())
	{
		$this->headers = $headers;
	}

	/**
	 * Оправляет письмо с указанными данными
	 *
	 * @param array $recipients
	 * @param string $subject
	 * @param string $message
	 * @param array $additionalHeaders
	 *
	 * @return bool
	 */
	public function sendEmail(array $recipients, $subject, $message, $additionalHeaders = array())
	{
		return mail(
			implode(',', $recipients),
			$subject,
			$message,
			implode("\r\n", array_unique(array_merge($this->headers, $additionalHeaders)))
		);
	}

	/**
	 * Размечает строку с темой как utf-8
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function markSubjectAsUtf8($string)
	{
		return sprintf('=?UTF-8?B?%s?=', base64_encode($string));
	}
}
