@if($fromDate->isSameDay($toDate))
    <span class="text-nowrap">{{\Jenssegers\Date\Date::parse($fromDate)->format($formatDate)}}</span> <span class="text-nowrap">{{$fromDate->format($formatTime).' - '.$toDate->format($formatTime)}} {{__("Uhr")}}</span>
@else
    <span class="text-nowrap">{{\Jenssegers\Date\Date::parse($fromDate)->format($formatDate.' '.$formatTime)}} -</span> <span class="text-nowrap">{{\Jenssegers\Date\Date::parse($toDate)->format($formatDate.' '.$formatTime)}} {{__("Uhr")}}</span>
@endif
