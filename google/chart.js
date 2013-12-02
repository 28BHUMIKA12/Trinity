//-----------CHART 1---------------

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Time', 'Weight in lbs', ],
      ['Week1',  <?=$this->user->weight_lbs?>,  ],
      ['Week2',  <?=$this->user->weight_lbs?>,  ],
      ['Week3',  <?=$this->user->weight_lbs?>,  ],
      ['Week4',  <?=$this->user->weight_lbs?>,  ]
    ]);
    
    var options = {
      title: 'Weight Report'
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart_div_1'));
    chart.draw(data, options);
  }
  google.setOnLoadCallback(drawChart);




//-----------CHART 2---------------
  
  function drawVisualization() {
  
    // Some raw data (not necessarily accurate)
    var data = google.visualization.arrayToDataTable([
      ['Month', 'Actual', 'Target'],
      ['Week1',  1890,      1950 ],
      ['Week2',  1955,      1950],
      ['Week3',  2030,      1950],
      ['Week4',  1890,      1950],
      ['Week5',  2010,      1950]
    ]);

  
  	var options = {
      title : '',
      vAxis: {title: "Calories"},
      hAxis: {title: "Time"},
      seriesType: "bars",
      series: {2: {type: "line"}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div_2'));
    chart.draw(data, options);
  
  }

  google.setOnLoadCallback(drawVisualization);

