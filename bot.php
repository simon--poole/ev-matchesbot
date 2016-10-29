<?php
	opcache_reset();
	require('lib/GosuGamers.php');
	require('lib/httpful.phar');
	require('teams.php');
	require('spoiler.php');
	use GosuGamers\Matchticker\Game;
	class Bot {

		//Config
		const MAX_GAMES = 8;
		const USERAGENT = "Script: Eventvods Matchticker Bot (by /u/satansf4te)";

		//Config manager static
		private static $configs;
		public static function addConfig($config){
			self::$configs[] = parse_ini_file($config);
		}
		public static function run(){
			foreach (self::$configs as $game) {
				$instance = new Bot($game);
				$instance->exec();
			}
		}

		//Instance settings
		private $config, $api, $token;
		public function __construct($config){
			$this->config = $config;
			$this->api = new \GosuGamers\Matchticker\Api($config['api_key']);
		}
		public function exec(){
			$matches = $this->getMatches();
			$matches = $this->sortMatches($matches);
			$table = $this->parseMatches($matches);
			var_dump($table);
			$this->reddit($table);
		}
		private function shortenURL($url){
			$response = \Httpful\Request::post("http://ev.wtf/process.php")
				->sendsType(\Httpful\Mime::FORM)
				->body(array(
					"url"=>$url
				))
				->send();
			return $response->body;
		}
		private function getMatches(){
			$matches = (array) $this->api->getMatches($this->config['game'], self::MAX_GAMES);
			return array_map(function($i){
				$tournament = $i->tournament->name;
				$time = ($i->isLive) ? null : new DateTime($i->datetime);
				$team1 = Team::format($i->firstOpponent->shortName, $this->config);
				$team2 = Team::format($i->secondOpponent->shortName, $this->config);
				$tournamentURL = $this->shortenURL($i->tournament->pageUrl);
				$matchURL = $this->shortenURL($i->pageUrl);
				$stream = (count($i->streams) > 0 && $i->streams[0]->isLive) ? $this->shortenURL($i->streams[0]->pageUrl) : null;
				$spoiler  = Spoiler::Check($tournament, $i->description, $this->config, $team1, $team2);
				return array($tournament, $team1, $team2, $time, $tournamentURL, $matchURL, $stream, $spoiler);
			}, $matches);
			var_dump($matches);
		}
		private function sortMatches($matches){
			usort($matches, function($key1,$key2) {
				if(is_null($key1[3]) && is_null($key2[3]))
					return 0;
				else if (is_null($key1[3]) && !is_null($key2[3]))
					return -1;
				else if (!is_null($key1[3]) && is_null($key2[3]))
					return 1;
				else
					return ($key1[3]<$key2[3])?-1:1;
			});
			return $matches;
		}
		private function parseMatches($matches){
			$result = "[](#matches_start)".PHP_EOL.PHP_EOL."* **Upcoming Matches**";
			$previous_label = "";
			if(count($matches) === 0)
				$result .= PHP_EOL."* No upcoming matches!";
			$now = new DateTime();
			foreach($matches as $match){
				$spoiler = $match[7];
				$icons = $this->config['icons'];
				if(is_null($match[3]))
					$time = (is_null($match[6])) ? "**[LIVE](#countdown)**" : "**[LIVE]($match[6]#stream)**";
				else {
					if($match[3]->getTimestamp() < time())
						continue 1;
					$interval = $now->diff($match[3]);
					$time = $interval->format("[Starting in %dd, %hh, %im](#countdown)");
				}
				$time = str_replace(array(" 0d,", " 0h,"), "", $time);
				if($previous_label != $match[0]){
					$result .= PHP_EOL;
					$result .= "* [".$match[0]."](".$match[4]."#title)";
					$result .= PHP_EOL;
					$result .= "* $time";
				}
				$previous_label = $match[0];
				if($icons && $spoiler)
					$result .= PHP_EOL."* [$match[2]](#spoiler) [](#default) [vs]($match[5]) [](#default) [$match[1]](#spoiler)";
				else if ($icons)
				{
					$icon1 = strtolower($match[1]);
					$icon2 = strtolower($match[2]);
					$result .= PHP_EOL."* [$match[2]](#team) [](#$icon2) [vs]($match[5]) [](#$icon1) [$match[1]](#team)";
				}
				else if($spoiler){
					$result .= PHP_EOL."* [$match[2]](#spoiler) [vs]($match[5]) [$match[1]](#spoiler)";
				}
				else {
					$result .= PHP_EOL."* [$match[2]](#team) [vs]($match[5]) [$match[1]](#team)";
				}
			}
			$result .= PHP_EOL."* **Powered by [GosuGamers](".$this->config['credits'].")**";
			$result .= PHP_EOL.PHP_EOL."[](#matches_end)";
			return $result;
		}
		private function login() {
			$array = array(
				"grant_type" => "password",
				"username" => $this->config['user'],
				"password" => $this->config['password']
			);
			$response = \Httpful\Request::post("https://www.reddit.com/api/v1/access_token")
				->sendsType(\Httpful\Mime::FORM)
				->expectsJson()
				->body($array)
				->authenticateWith($this->config['client_id'], $this->config['client_secret'])
				->userAgent(Bot::USERAGENT)
				->send();
			$this->token = $response->body->access_token;
	}
		private function reddit($table){
			$this->login();
			$response = \Httpful\Request::get("https://oauth.reddit.com/r/".$this->config['subreddit']."/about/edit/.json")
				->expectsJson()
				->addHeader('Authorization', "bearer $this->token")
				->userAgent(Bot::USERAGENT)
				->send();
			$sidebar = preg_replace("/\[\]\(#matches_start\)[\s\S]*\[\]\(#matches_end\)/", $table, $response->body->data->description, 1);
			$settings = (array) $response->body->data;
			$settings['description'] = htmlspecialchars_decode($sidebar);
			$settings['sr'] = $settings['subreddit_id'];
			$settings['link_type'] = $settings['content_options'];
			$settings['type'] = $settings['subreddit_type'];
			$settings['over_18'] = "false";
			unset($settings['hide_ads']);
			$response = \Httpful\Request::post("https://oauth.reddit.com/api/site_admin?api_type=json")
				->sendsType(\Httpful\Mime::FORM)
				->expectsJson()
				->body($settings)
				->addHeader('Authorization', "bearer $this->token")
				->userAgent(Bot::USERAGENT)
				->send();
			var_dump($response->body);
		}
	}
	Bot::addConfig('configs/lol.conf');
	Bot::addConfig('configs/hs.conf');
	Bot::addConfig('configs/ow.conf');
	Bot::addConfig('configs/dota.conf');
	Bot::run();
?>
