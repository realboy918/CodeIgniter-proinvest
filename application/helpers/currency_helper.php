<?php
function to_currency_format($number, $decimals,$currency_symbol,$symbol_location,$number_of_decimals,$thousands_separator,$decimal_point)
{
	
	$number = (float)$number;
	$decimals_system_decide = true;
	
	if ($number_of_decimals !== NULL && $number_of_decimals!= '')
	{
		$decimals = (int)$number_of_decimals;
		$decimals_system_decide = false;
	}
	
	if($number >= 0)
	{
		if ($symbol_location == 'after')
		{
			$ret = number_format($number, $decimals, $decimal_point, $thousands_separator).' '.$currency_symbol;
		}
		else
		{
			$ret = $currency_symbol.' '.number_format($number, $decimals, $decimal_point, $thousands_separator);			
		}
   }
   else
   {
		if ($symbol_location == 'after')
	   	{
			$ret = '<span style="white-space:nowrap;">-</span>'.number_format(abs($number), $decimals, $decimal_point, $thousands_separator).' '.$currency_symbol;
		}
		else
		{
			$ret = '<span style="white-space:nowrap;">-</span>'.$currency_symbol.' '.number_format(abs($number), $decimals, $decimal_point, $thousands_separator);
		}
   }

	 if ($decimals_system_decide && $decimals >=2)
	 {
 		if ($symbol_location == 'after')
		{
   		 	return preg_replace('/(?<=\d{2})0+'.preg_quote($currency_symbol).'$/', $currency_symbol, $ret);
		}
		else
		{
   			 return preg_replace('/(?<=\d{2})0+$/', '', $ret);
		}
	 }
	 else
	 {
		 return $ret;
	 }
}
function to_currency($number, $decimals = 2, $show_not_set = TRUE)
{
	$CI =& get_instance();
	
	$currency_symbol = $CI->settings_model->getSettingsInfo()['currency'] ? $CI->settings_model->getSettingsInfo()['currency'] : '$';
	$symbol_location = $CI->settings_model->getSettingsInfo()['currency_position'] ? $CI->settings_model->getSettingsInfo()['currency_position'] : 'before';
	$number_of_decimals = '2';
	$thousands_separator = ',';
	$decimal_point = '.';
	
	if($show_not_set && $number === NULL)
	{
		return lang('common_not_set');
	}
	
	return to_currency_format($number, $decimals,$currency_symbol,$symbol_location,$number_of_decimals,$thousands_separator,$decimal_point);	
}
?>