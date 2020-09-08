<?php
class TimeDate
{
	function get_date_time()
	{
		return date('Y-m-d h:i:s', time());
	}
	function get_time()
	{
		return date('h:i:s', time());
	}
	function get_date()
	{
		return date('m-d-Y' , time());
	}
	function get_hours()
	{
		return date('h', time());
	}
	function get_mins()
	{
		return date('i', time());
	}
	function get_sec()
	{
		return date('s', time());
	}
	function get_am_pm()
	{
		return date('a', time());
	}
	function get_years()
	{
		return date('Y', time());
	}
	function get_months()
	{
		return date('m', time());
	}
	function get_days()
	{
		return date('d', time());
	}
	
}