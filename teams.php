<?php
	class Team {
		const Teams = array(
			"lol"=>array(
				"remove" => array(".LOL", "-LOL", " LOL", "TEAM ", "LOL", "GAMING"),
				"replace" => array(
					//EULCS
					"ROCCAT" => "ROC",
					"FNATIC" => "FNC",
					"ORIGEN" => "OG",
					"VTA" => "VIT",
					"EFOX" => "EFX",
					//NALCS
					"IMMORTALS" => "IMT",
					//CBLOL
					"RED." => "REDC",
					"BIG" => "BG",
					//LCK
					"AFREECA F" => "AFC",
					//LMS
					"J TEAM" => "JT",
					"MACHI" => "M17",
					"YOEFW" => "FW",
					"XGAMERS" => "XG",
					//LPL
					"SNAKE" => "SNK",
					//LSPL
					"TBEAR" => "TBG",
					"ING." => "ING",
					"SHR" => "SHRC",
					"NEWBEE" => "NBY",
					),
			)
		);
		public static function format($team, $game){
			$team = strtoupper($team);
			$team = trim(str_replace(self::Teams[$game]["remove"], "", $team));
			echo "Replaced: $team => ";
			if(array_key_exists($team, self::Teams[$game]["replace"]))
				$team = self::Teams[$game]['replace'][$team];
			echo substr($team, 0, 5).PHP_EOL;
			return substr($team, 0, 5);
		}
		
	}
?>