<?php
	class Team {
		const Teams = array(
			"global" => array("[", "]", "(",")"),
			"lol"=>array(
				"remove" => array(".LOL", "-LOL", " LOL", "TEAM ", "LOL", "GAMING"),
				"replace" => array(
					//EULCS
					"ROCCAT" => "ROC",
					"FNATIC" => "FNC",
					"ORIGEN" => "OG",
					"VTA" => "VIT",
					"EFOX" => "EFX",
					"GIANTS" => "GIA",
					"SPLYCE" => "SPY",
					//EUCS
					"M" => "MIL",
					//NALCS
					"IMMORTALS" => "IMT",
					//CBLOL
					"RED." => "REDC",
					"BIG" => "BG",
					//LCK
					"AFREECA F" => "AFC",
					"CJ ENTUS" => "CJ",
					"SAMSUNG G" => "SSG",
					"LONGZHU IM" => "LZ",
					"SKT T1" => "SKT",
					"KT ROLSTER" => "KT",
					"JINAIR GW" => "JAG",
					"ROX TIGERS" => "ROX",
					//LMS
					"J TEAM" => "JT",
					"MACHI" => "MAC",
					"YOEFW" => "FW",
					"XGAMERS" => "XG",
					//LPL
					"SNAKE" => "SNK",
					"SNT" => "SAT",
					"IMAY" => "IM",
					//LSPL
					"TBEAR" => "TBG",
					"ING." => "ING",
					"SHR" => "SHRC",
					"NEWBEE" => "NBY",
					"YGM" => "YM",
					"RVG []" => "RVGR",
					),
			),
			"hearthstone" => array(
				"remove" => array(),
				"replace" => array()
			)
		);
		public static function format($team, $config){
			$game = $config['game'];
			if($config['icons']){
				$team = strtoupper($team);
				$team = str_replace(self::Teams["global"], "", $team);
				$team = trim(str_replace(self::Teams[$game]["remove"], "", $team));
				echo "Replaced: $team => ";
				if(array_key_exists($team, self::Teams[$game]["replace"]))
					$team = self::Teams[$game]['replace'][$team];
				echo substr($team, 0, 5).PHP_EOL;
				return substr($team, 0, 5);
			}
			else {
				$team = str_replace(self::Teams["global"], "", $team);
				$team = trim(str_replace(self::Teams[$game]["remove"], "", $team));
				return $team;
			}
		}

	}
?>
