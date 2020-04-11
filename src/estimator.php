<?php

function covid19ImpactEstimator($data)
{
  $data = json_decode($data);
  $reportedCases = $data->reportedCases;
  
  $periodType = $data->periodType;
  $timeToElapse = $data->timeToElapse;


  $currentlyInfected = $reportedCases * 10;
  $currentlyInfectedServere = $reportedCases * 50;


  if($periodType == "weeks"){

    $timeToElapse = $timeToElapse * 7;

  }else{

    $timeToElapse = $timeToElapse * 4 * 7;
  }
//Challenge ONE
  $power = intdiv($timeToElapse, 3);
  $infectionsByRequestedTime = $currentlyInfected * (2 ** $power);
  $infectionsByRequestedTimeSevere = $currentlyInfectedServere * (2 ** $power);

  $impact = array($currentlyInfected, $infectionsByRequestedTime);
  $impact = json_encode($impact);

  //Challenge TWO

  $severeCasesByRequestedTime = 15 / 100 * $infectionsByRequestedTime;
  $hospitalBedsByRequestedTime = (35 /100 * $data->totalHospitalBeds) - $severeCasesByRequestedTime; 

  $severeImpact = array($currentlyInfectedServere, $infectionsByRequestedTimeSevere);
  $severeImpact = json_encode($severeImpact);

  //Challenge THREE

  $casesForICUByRequestedTime = 5 / 100 * $infectionsByRequestedTime;
  $casesForVentilatorsByRequestedTime = 2 / 100 * $infectionsByRequestedTime;
  $dollarsInFlight = round($infectionsByRequestedTime * $data->avgDailyIncomePopulation * $data->avgDailyIncomeInUSD * $timeToElapse, 2);

  //Aggregate the output
  $covid = array(
    $data, $impact, $severeImpact, 
    "hospitalBedsByRequestedTime" => $hospitalBedsByRequestedTime, 
    "casesForICUByRequestedTime" => $casesForICUByRequestedTime, 
    "casesForVentilatorsByRequestedTime" => $casesForVentilatorsByRequestedTime, 
    "dollarsInFlight" => $dollarsInFlight
  );
  $data = json_encode($covid);
  return $data;
}