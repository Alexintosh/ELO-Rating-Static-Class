<?php 
/**
 * This class calculates ratings based on the Elo system http://en.wikipedia.org/wiki/Elo_rating.
 *
 * @author Alessio Delmonti <alessio.d@gmail.com>
 * @license Mit License http://opensource.org/licenses/MIT
 */
class Rank {

	const KFACTOR = 40;

	static function GetExpected($rA, $rB) //rate
	{
		return (1/(1+pow(10,(($rB-$rA)/400)))); // ELO formula	
	}

	static function calculateNewRate($winner, $looser)
	{
		if( !isset($winner) || !isset($looser) ) return NULL;

		$winner['expected'] = Rank::GetExpected($winner['rank'], $looser['rank']);
		$looser['expected'] = Rank::GetExpected($looser['rank'], $winner['rank']);

		$winner['rank'] = Rank::_getNewRate($winner['rank'], $winner['expected'], 1);
		$looser['rank'] = Rank::_getNewRate($looser['rank'], $looser['expected'], 0);

		return array(
			$winner,
			$looser
		);
	}

	static function _getNewRate($rate, $expected, $score)
	{
		return $rate + ( self::KFACTOR * ( $score - $expected ) );
	}
	

	static function CustomRate($rate, $expected, $score)
    {        
        if($rate < 500)
            $k = self::KFACTOR;
        elseif ($rate < 1000)
            $k = self::KFACTOR - 20;
        else
            $k = self::KFACTOR - 30;

        return $rate + ($k*($score - $expected));
    }
	
}
?>