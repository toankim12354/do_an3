<?php 

use App\Exceptions\CustomErrorException;
use Carbon\Carbon;
/**
 * date time
 */
if (! function_exists('t_now')) {
	function t_now($dateString = '', $customFormat = 'dddd, HH:mm, DD/MM/YYYY'){
		// now
		$now = Carbon::now()->locale('vi');

		// other date: add hour, minute, second
		if ($dateString !== '') {
			$now = Carbon::parse($dateString)
      ->locale('vi')
      ->addHours($now->hour)
      ->addMinutes($now->minute)
      ->addSeconds($now->second);
    }

    return (object) [
     'day' => $now->dayOfWeekIso,
     'date' => $now->toDateString(),
     'time' => $now,
     'custom' => $now->isoFormat($customFormat)
   ];
 }
}

// count day of month by name
function count_day_by_name($nameOfDay = '', $timeString = '') {
  $startMonth = $timeString ? Carbon::parse($timeString)->startOfMonth() 
  : Carbon::today()->startOfMonth();
  
  $endMonth = $startMonth->copy()->endOfMonth();
  
  $nameOfDay = ucfirst(strtolower($nameOfDay));
  
  $nameMethod = '';
  
  switch($nameOfDay) {
    case 'Monday':
    $nameMethod = "isMonday";
    break;
    case 'Tuesday':
    $nameMethod = "isTuesday";
    break;
    case 'Wednesday':
    $nameMethod = "isWednesday";
    break;
    case 'Thursday':
    $nameMethod = "isThursday";
    break;
    case 'Friday':
    $nameMethod = "isFriday";
    break;
    case 'Saturday':
    $nameMethod = "isSaturday";
    break;
    case 'Sunday':
    $nameMethod = "isSunday";
    break;
    default:
    $nameMethod = "isMonday";
    break;
  }
  
  $count = $startMonth->diffInDaysFiltered(function($date) use($nameMethod) {
    return $date->$nameMethod();
  }, $endMonth);
  
  return $count;
}

if (! function_exists('transformDate')) {
  function transformDate($value, $format = 'Y-m-d') {
    try {
      return Carbon::instance(
        \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
          $value));
    } catch (\ErrorException $e) {
      try {
        return Carbon::parse($value)->format($format);
      } catch (\Carbon\Exceptions\InvalidFormatException $e) {
        return null;
      }
    }
  }
}

// lay cac ngay tuong ung voi cac thu trong tuan trong 1 khoang thoi gian
if (! function_exists('dateGroupByDayName')) {
  function dateGroupByDayName($fromDate, $toDate = '') {
    $startDates = [
      '1' => Carbon::parse($fromDate)->next(Carbon::MONDAY),
      '2' => Carbon::parse($fromDate)->next(Carbon::TUESDAY),
      '3' => Carbon::parse($fromDate)->next(Carbon::WEDNESDAY),
      '4' => Carbon::parse($fromDate)->next(Carbon::THURSDAY),
      '5' => Carbon::parse($fromDate)->next(Carbon::FRIDAY),
      '6' => Carbon::parse($fromDate)->next(Carbon::SATURDAY),
      '7' => Carbon::parse($fromDate)->next(Carbon::SUNDAY),
    ];

    $endDate = ($toDate === '') ? Carbon::now()->endOfMonth() 
                                : Carbon::parse($toDate);

    $result = [];

    foreach ($startDates as $key => $startDate) {
      for ($date = $startDate; $date->lte($endDate); $date->addWeek()) { 
        $result[$key][] = $date->format('Y-m-d');
      }
    }

    return $result;
  }
}