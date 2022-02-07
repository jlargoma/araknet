<?php

namespace App\Services;

class CalendarService {

  private  $firstDay;
  private  $lastDay;
  
  public function __construct($date){
         $this->getFirstDay($date);
         $this->getLastDay($date);
  }
  
  function getFirstDay($date) {
      /********************************************************************* */
      // Tomamos el primer día de la semana del mes
      $weekday = date('N', strtotime($date)) - 1;
      switch ($weekday) {
          case 0: 
            $this->firstDay = strtotime($date);
            break;
          case 6:
            $this->firstDay = strtotime($date . '+' . (7 - $weekday) . ' days');
            break;
          default: 
            $this->firstDay = strtotime($date . '-' . $weekday . ' days');
            break;
      }
    }
    
    function setLastDay($time) {
      $this->lastDay = $time;
    }
    function setLastDayWeeks($weeks) {
      $this->lastDay = strtotime(' +'.$weeks.' weeks',$this->firstDay) - 24 * 60 * 60;
    }
    
    function getLastDay($date) {
      
        /********************************************************************** */
        // Y tomamos el ultimo día del mes
        $this->lastDay = strtotime(date('Y-m-t', strtotime($date)));
        $weekday = date('N', $this->lastDay);
        if ($weekday < 6)
            $this->lastDay = strtotime('next Saturday', $this->lastDay);
        elseif ($weekday == 7)
            $this->lastDay = strtotime(date('Y-m-d', $this->lastDay) . ' -1 days');
    }
    
    function getCalendarWeeks() {

      /********************************************************************* */
      // Crear calendario
        $cDay = $this->firstDay;
        $days = [[], [], [], [], [], []];
        $weekday = 1;
        $week = 0;
        $oneDay = 24 * 60 * 60;
        $daySP = [
            1 => 'Lun',
            2 => 'Mar',
            3 => 'Mié',
            4 => 'Jue',
            5 => 'Vie',
            6 => 'Sáb',
            0 => 'Dom',
        ];
        while ($cDay <= $this->lastDay) {
            if ($weekday < 7)
                $days[$week][] = [
                    'time' => $cDay,
                    'date' => date('d/m', $cDay),
                    'day' => $daySP[$weekday]
                ];
            $cDay += $oneDay;
            if ($weekday == 7) {
                $weekday = 0;
                $week++;
            }
            $weekday++;
        }
        
        
        return [
            'firstDay' => $this->firstDay,
            'lastDay' => $this->lastDay,
            'days' => $days,
        ];
    }
    
    
    function getCalendarOneWeek() {

      /********************************************************************* */
      // Crear calendario
        $cDay = $this->firstDay;
        $days = [[]];
        $weekday = 1;
        $week = 0;
        $oneDay = 24 * 60 * 60;
        $daySP = [
            1 => 'Lun',
            2 => 'Mar',
            3 => 'Mié',
            4 => 'Jue',
            5 => 'Vie',
            6 => 'Sáb',
            0 => 'Dom',
        ];
        for($i=0;$i<7;$i++){
            if ($weekday < 7)
                $days[$week][] = [
                    'time' => $cDay,
                    'date' => date('d/m', $cDay),
                    'day' => $daySP[$weekday]
                ];
            $cDay += $oneDay;
            if ($weekday == 7) {
                $weekday = 0;
                $week++;
            }
            $weekday++;
        }
      
        $this->lastDay = $cDay;
        return [
            'firstDay' => $this->firstDay,
            'lastDay' => $this->lastDay,
            'days' => $days,
        ];
    }

}
