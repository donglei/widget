<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Domain extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'domain';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'search free domain.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$url = 'http://pandavip.www.net.cn/check/check_ac1.cgi?domain=%s&callback=jQuery1710840707394760102_1408345572710&_=1408345677437';
		foreach (range(1143, 9999) as $key => $value) {
			$domain= $value . '.com';
			//|210|
			$url = sprintf($url, $domain);
			$result = file_get_contents($url);
			Log::info('domain:' . $domain, ['domain' => $domain, 'request' => $result]);
			$status = strpos($result, '|210|');
			if ($status) {
				$this->error($domain);
				$this->error($result);
			}
			sleep(20);
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
