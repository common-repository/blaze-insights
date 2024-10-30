jQuery(document).ready(function($){
  var fileterData = getFilterData();
  getReports(fileterData[0], fileterData[1], fileterData[2]);

  $('input[name="fuzzy-date"]').on('change', function() {
    $('input[name="start-date"]').val('');
    $('input[name="end-date"]').val('');

    var fuzzy_date = $('input[name="fuzzy-date"]:checked').val();
    var start_date = $('input[name="start-date"]').val();
    var end_date = $('input[name="end-date"]').val();

    getReports(fuzzy_date, start_date, end_date);
  })
  $('input[name="start-date"], input[name="end-date"]').on('change', function(e) {
    var fuzzy_date = $('input[name="fuzzy-date"]:checked').val();
    var start_date = $('input[name="start-date"]').val();
    var end_date = $('input[name="end-date"]').val();
    if(start_date != '' && end_date != '' ) {
      getReports(fuzzy_date, start_date, end_date);
    }
  })

  function getFilterData() {
    var fuzzy_date = $('input[name="fuzzy-date"]:checked').val();
    var start_date = $('input[name="start-date"]').val();
    var end_date = $('input[name="end-date"]').val();
    return [fuzzy_date, start_date, end_date];
  }

  function getReports(fuzzy_date, start_date, end_date) { 
    console.log('reports'); 
    var query = "";

    if(start_date == '' || end_date == '') {
      query = "fuzzy-date=" + fuzzy_date;
    } else {
      query = "start-date=" + start_date + "&end-date=" + end_date;
    }
    
    var data = {
      action: "blaze_insights_get_report",
      query: query
    };
    $.post(ajaxurl, data, function (response) {
      var json = JSON.parse(response);
      console.log(json);
      if (json.success == 1) {
        generateReport(json)
      }
    });
  }
});

function generateReport(data) {
  const mobileColor = "#EF476F";
  const desktopColor = "#118AB2";
  const tabletColor = "#FFD166";
  
  const sessionsByDeviceData = data.reports.sessionsByDevice;
  const absoluteFunnelData = data.reports.funnel.absolute;
  const relativeFunnelData = data.reports.funnel.relative;
  const productViewsData = absoluteFunnelData[1].dates;
  const addToCartData = absoluteFunnelData[2].dates;
  const checkoutData = absoluteFunnelData[3].dates;
  const transactionsData = absoluteFunnelData[4].dates;

  const conversionRateElementOverall = document.getElementById("conversion-rate-overall");
  const conversionRateElementMobile = document.getElementById("conversion-rate-mobile");
  const conversionRateElementDesktop = document.getElementById("conversion-rate-desktop");
  const conversionRateElementTablet = document.getElementById("conversion-rate-tablet");
  conversionRateElementOverall.textContent = data.reports.conversionRates.overall + "%";
  conversionRateElementMobile.textContent = data.reports.conversionRates.mobile + "%";
  conversionRateElementDesktop.textContent = data.reports.conversionRates.desktop + "%";
  conversionRateElementTablet.textContent = data.reports.conversionRates.tablet + "%";

  const sessionsByDeviceElement = document.getElementById("sessions-by-device");
  const sessionsByDeviceContext = sessionsByDeviceElement.getContext("2d");
  if(window.sessionsByDeviceChart instanceof Chart) {
    window.sessionsByDeviceChart.destroy()
  }
  window.sessionsByDeviceChart = new Chart(sessionsByDeviceContext, {
    type: "pie",
    options: {
      responsive: false,
      plugins: {
        tooltip: {
          position: "nearest",
          callbacks: {
            label: function(context) {
              // Add a % suffix to the labels
              return context.label + ": " + context.formattedValue + "%";
            }
          }
        },
        legend: {
          position: 'left',
          align: 'start'
        }
      }
    },
    data: {
      labels: ["Mobile", "Desktop", "Tablet"],
      datasets: [
        {
          data: sessionsByDeviceData,
          backgroundColor: [mobileColor, desktopColor, tabletColor],
        }
      ]
    }
  });

  const funnelAbsoluteElement = document.getElementById("funnel-absolute");
  const funnelAbsoluteContext = funnelAbsoluteElement.getContext("2d");
  if(window.funnelAbsoluteChart instanceof Chart) {
    window.funnelAbsoluteChart.destroy()
  }
  window.funnelAbsoluteChart = new Chart(funnelAbsoluteContext, {
    type: "line",
    options: {
      interaction: {
        intersect: false,
        mode: "index",
      },
      responsive: false,
      plugins: {
        tooltip: {
          position: "nearest",
          callbacks: {
            label: function(context) {
              // Add a % suffix to the labels
              return context.dataset.label + ": " + context.formattedValue + "%";
            }
          }
        }
      },
      scales: {
        y: {
          type: "logarithmic"
        }
      }
    },
    data: {
      datasets: [
        {
          label: "Mobile",
          data: absoluteFunnelData,
          backgroundColor: mobileColor,
          borderColor: mobileColor,
          parsing: {
            xAxisKey: "label",
            yAxisKey: "mobilePercent",
          }
        },
        {
          label: "Desktop",
          data: absoluteFunnelData,
          backgroundColor: desktopColor,
          borderColor: desktopColor,
          parsing: {
            xAxisKey: "label",
            yAxisKey: "desktopPercent",
          }
        },
        //{
        //  label: "Tablet",
        //  data: absoluteFunnelData,
        //  backgroundColor: tabletColor,
        //  borderColor: tabletColor,
        //  parsing: {
        //    xAxisKey: "label",
        //    yAxisKey: "tabletPercent",
        //  }
        //},
      ]
    }
  });

  const funnelRelativeElement = document.getElementById("funnel-relative");
  const funnelRelativeContext = funnelRelativeElement.getContext("2d");
  if(window.funnelRelativeChart instanceof Chart) {
    window.funnelRelativeChart.destroy()
  }
  window.funnelRelativeChart = new Chart(funnelRelativeContext, {
    type: "line",
    options: {
      interaction: {
        mode: "index",
        intersect: false,
      },
      responsive: false,
      plugins: {
        tooltip: {
          position: "nearest",
          callbacks: {
            label: function(context) {
              // Add a % suffix to the labels
              return context.dataset.label + ": " + context.formattedValue + "%";
            }
          }
        },
        annotation: {
          annotations: {}
        }
      },
      scales: {
        y: {
          type: "logarithmic"
        }
      }
    },
    data: {
      datasets: [
        {
          label: "Mobile",
          data: relativeFunnelData,
          backgroundColor: mobileColor,
          borderColor: mobileColor,
          parsing: {
            xAxisKey: "label",
            yAxisKey: "mobilePercent",
          }
        },
        {
          label: "Desktop",
          data: relativeFunnelData,
          backgroundColor: desktopColor,
          borderColor: desktopColor,
          parsing: {
            xAxisKey: "label",
            yAxisKey: "desktopPercent",
          }
        },
      ]
    }
  });

  const funnelStageOptions = {
    type: "line",
    options: {
      interaction: {
        mode: "index",
        intersect: false,
      },
      responsive: false,
      plugins: {
        tooltip: {
          position: "nearest",
          callbacks: {
            label: function(context) {
              // Add a % suffix to the labels
              return context.dataset.label + ": " + context.formattedValue + "%";
            }
          }
        }
      }
    },
    data: {
      datasets: [
        {
          label: "Mobile",
          data: productViewsData,
          backgroundColor: mobileColor,
          borderColor: mobileColor,
          tension: 0.4,
          parsing: {
            xAxisKey: "key",
            yAxisKey: "mobilePercent",
          }
        },
        {
          label: "Desktop",
          data: productViewsData,
          backgroundColor: desktopColor,
          borderColor: desktopColor,
          tension: 0.4,
          parsing: {
            xAxisKey: "key",
            yAxisKey: "desktopPercent",
          }
        },
        //{
        //  label: "Tablet",
        //  data: productViewsData,
        //  backgroundColor: tabletColor,
        //  borderColor: tabletColor,
        //  tension: 0.4,
        //  parsing: {
        //    xAxisKey: "key",
        //    yAxisKey: "tabletPercent",
        //  }
        //},
      ]
    }
  };

  function populateChart(elementId, data) {
    const element = document.getElementById(elementId);
    const context = element.getContext("2d");
    const options = JSON.parse(JSON.stringify(funnelStageOptions));
    options.data.datasets[0].data = data;
    options.data.datasets[1].data = data;
    const chart = new Chart(context, options);
    return chart;
  }

  const chartAnnotations = {
  }

  function getChartAnnotation(statusName) {
    let chartAnnotation;
    switch (statusName) {
      case "primary":
        chartAnnotation = {
          type: "point",
          radius: 15,
          borderWidth: 4,
          borderColor: "red",
          backgroundColor: "rgba(255, 0, 0, 0.2)",
        };
        break;
      case "secondary":
        chartAnnotation = {
          type: "point",
          radius: 10,
          borderWidth: 2,
          borderColor: "red",
          backgroundColor: "transparent",
        };
        break;
      case "tertiary":
        chartAnnotation = {
          type: "point",
          radius: 10,
          borderWidth: 2,
          borderColor: "#EDAE49",
          backgroundColor: "transparent",
        };
        break;
    }
    return chartAnnotation;
  };
  if(window.productViews instanceof Chart) {
    window.productViews.destroy()
  }
  window.productViews = populateChart("product-views", productViewsData);

  if(window.addToCartViews instanceof Chart) {
    window.addToCartViews.destroy()
  }
  window.addToCartViews = populateChart("add-to-cart", addToCartData);

  if(window.checkoutViews instanceof Chart) {
    window.checkoutViews.destroy()
  }
  window.checkoutViews = populateChart("checkout", checkoutData);

  if(window.transactionViews instanceof Chart) {
    window.transactionViews.destroy()
  }
  window.transactionViews = populateChart("transactions", transactionsData);


  const relativeInsightsElement = document.getElementById("relative-insights");
  const relativeChartAnnotations = funnelRelativeChart.options.plugins.annotation.annotations;
  for (const insight of data.insights) {
    const insightElement = document.createElement("div");
    const insightTextElement = document.createElement("span");
    const insightLegendElement = document.createElement("span");
    const chartAnnotation = getChartAnnotation(insight.statusName);
    if (chartAnnotation) {
      chartAnnotation.xValue = insight.xValue;
      chartAnnotation.yValue = insight.yValue;
      relativeChartAnnotations[insight.insightName] = chartAnnotation;
      insightLegendElement.classList.add("legend");
      insightLegendElement.classList.add("legend-" + insight.statusName);
    }
    insightTextElement.textContent = insight.annotation;
    insightElement.appendChild(insightLegendElement);
    insightElement.appendChild(insightTextElement);
    relativeInsightsElement.appendChild(insightElement);
  }
  funnelRelativeChart.update();
}