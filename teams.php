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
				"remove" => array(".HS", " HS", "-HS"),
				"replace" => array()
			),
			"overwatch" => array(
				"remove" => array(".OW", " OW", "-OW"),
				"replace" => array()
			),
			"dota2" => array(
				"remove" => array(".DOTA", " DOTA", "-DOTA", ".Dota2", ".DotA2", "Dota2"),
				"replace" => array(
					"Elements." => "Elements",
					"G5." => "G5",
					".Escape" => "Escape",
					"Vega." => "Vega",
					"Fnatic." => "Fnatic",
					"FRIENDS." => "FRIENDS"
				)
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
				echo "Replaced $team => ";
				$team = str_replace(self::Teams["global"], "", $team);
				$team = trim(str_replace(self::Teams[$game]["remove"], "", $team));
				if(array_key_exists($team, self::Teams[$game]["replace"]))
					$team = self::Teams[$game]['replace'][$team];
				echo $team.PHP_EOL;
				return $team;
			}
		}

	}
?>
