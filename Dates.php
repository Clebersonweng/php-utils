<?php

/**
* Calculates the number of days between 2 dates.
* Past dates will always be valid if the first date will always be less than the second date.
* @param string $dateStart format YYYY-MM-DD
* @param string $dateEnd format YYYY-MM-DD
* @return int O number days between two date
**/

function calculaDias(string $dateStart, string $dateEnd) : int {

	if (intval($dateStart) > intval($dateEnd)) {
		return 0;
	}

	$splitDataStart = explode('-', $dateStart);
	$splitDataEnd = explode('-', $dateEnd);

	$yearStart = intval($splitDataStart[0]);
	$monthStart = intval($splitDataStart[1]);
	$dayStart = intval($splitDataStart[2]);

	// second dates
	$yearEnd = intval($splitDataEnd[0]);
	$monthEnd = intval($splitDataEnd[1]);
	$dayEnd = intval($splitDataEnd[2]);

	//   01-3-2004 - 12-02-2009
	//  (2007-2012) years + (6-1) months + (13-6) days
	// = 4 years, 11 months, 11 days

	$resYears = difYears($yearStart,$yearEnd);
	$resMonths = difMonths($monthStart,$monthEnd,$resYears);
	// os  dias do mes do ultimo ano ex: 2 dias do mes de marco de 2004, por que os outros dias estao o mes inteiro 
	$resDays = difDays($dayStart,$dayEnd,$resMonths);

	$resultDays = $resYears*(365)+$resMonths*(30)+$resDays;

	$years  = range($yearStart, $yearEnd);
	foreach($years AS $year) {
		//I update the array according to each year because there may be leap years between the period, 
		//it does not count the final year if it happens to be a leap year because it did not end
		if( $year != $yearStart && $year != $yearEnd && isLeapYear($year) ){
			$resultDays++;
		}
	}

	$resultDays += aditionalDays($monthStart,$monthEnd,$yearEnd);

	return $resultDays;
}

// if the initial month is less than the final month, it borrows from the year and adds the 12 months
function difMonths(int $monthStart, int $monthEnd,int &$years) : int {
	
	$months = $monthEnd-$monthStart;
	// if the month end is < month start we need to borrow month on the year
   if( $monthEnd<$monthStart ) {
		$years =  $years - 1; // borrowed for month 1 year = 12 months
		$months = ($monthEnd+12)-$monthStart;
	}
	
   return $months;
}

function difDays(int $dayStart, int $dayEnd,&$months) : int {
   $days = $dayEnd-$dayStart;
	// if the day end is < day start we need to borrow days on the month
   if( $dayEnd<$dayStart ) {
		$months =  $months - 1; // borrowed for day 1 month = 30 days
		$days = ($dayEnd+30)-$dayStart;
	}
	
   return $days;
}

function difYears(int $anoInicio, int $anoFin) : int {
   return abs($anoFin-$anoInicio);
}
/**
* return if is leap year
* @param int $year 2022
* @return bool true | false
**/
function isLeapYear(int $year) : bool{
	return (($year%4 == 0 && $year%100 != 0) || $year%400 == 0) ? true : false;
}

/**
* retorna a quantidade de dias de um mes o sobrante de 30 dias se for 31 -30 = 1 
* @param string $month  1
* @param string $year 2022
* @return array|int todos os meses com seus dias ou o valor do dia do mes
**/
function getDaysOfTheMonth(int $year,$month=null) {

	$february = -2;
	if ( isLeapYear($year) ) 
	{
		$february = -1;
	}

	$months = array(
		1=>1,
		2=>$february,
		3=>1,
		4=>0,
		5=>1,
		6=>0,
		7=>1,
		8=>1,
		9=>0,
		10=>1,
		11=>0,
		12=>1
	);

	if($month !== null) {
		return $months[$month];
	}
	return $months;
};

//initial month counts but final month does not count
function rangeMonths(int $monthStart,int $monthEnd) : array {
	$months = [];
	for ($i=$monthStart; ; $i++) { 
		if(($i == $monthEnd )) {
			break;
		}
		$months[] += $i;
		if($i===12) {
			$i=0;
		}
	}
	return $months;
}

// after we have all days we need sum the month have 31 days, because we sum all months with 30 days
function aditionalDays($monthStart,$monthEnd,$currentYear) {
	$days = 0;
	$rangeMonths = rangeMonths($monthStart,$monthEnd);
	foreach ($rangeMonths as $month) {
		$days += getDaysOfTheMonth($currentYear,$month);
	}
	return $days;
}