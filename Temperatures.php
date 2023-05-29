<?php
/**
* return mos closest temperature according to the value placed
* @param array $temperaturas  array( 17, 32, 14, 21 )
* @param string $temperatura_prox_esperada 0
* @return int todos os meses com seus dias ou o valor do dia do mes
**/
function closestTemperature(array $temperaturas,int $temperatura_prox_esperada = 0) : int {
	
	sort($temperaturas);
	$abs_temperaturas = array_map('abs', $temperaturas);
	$valorMaior = max($abs_temperaturas); //27
	$mais_proximo = $temperatura_prox_esperada;

	foreach($temperaturas AS $temperatura) {
		$valor_atual = abs($temperatura_prox_esperada - $temperatura);

		if( $mais_proximo === $valor_atual) { // if it is of the same sign, it returns the value without change
			$mais_proximo = $temperatura;
		}
		else if(abs($mais_proximo) === $valor_atual) { //if it is a different sign it returns the original value
			$mais_proximo = $temperatura;
		}
		else if($valor_atual < $valorMaior) {
			$valorMaior = $valor_atual;
			$mais_proximo = $temperatura;
		}
	}
	return $mais_proximo;
}