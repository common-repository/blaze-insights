jQuery(document).ready(function($){
  "use strict";

  function parseWithFunctions(json) {
    var parsed = JSON.parse(json, function(key, value) {
      if (/^function.*?\(.*?\)\s*\{.*\}$/.test(value)) {
        // If it looks like a function, convert it to a function.
        var parsedFunction = new Function("return " + value)();
        return parsedFunction;
      } else {
        return value;
      }
    });
    return parsed;
  }

  var blaze_insights = {

    poll_attempt: 0,
    show_error: false,

    test_mode: window.bi_test_mode,

    $fuzzy_date: 'input[name="fuzzy-date"]:checked',
    $start_date: 'input[name="start-date"]',
    $end_date: 'input[name="end-date"]',

    data: null,

    // Elements
    conversionRateElementOverall: document.getElementById("conversion-rate-overall"),
    conversionRateElementMobile: document.getElementById("conversion-rate-mobile"),
    conversionRateElementDesktop: document.getElementById("conversion-rate-desktop"),
    conversionRateElementTablet: document.getElementById("conversion-rate-tablet"),
    snapshotElement: document.getElementById("snapshot"),
    funnelRelativeElement: document.getElementById("funnel-relative"),
    funnelAbsoluteElement: document.getElementById("funnel-absolute"),
    relativeInsightsElement: document.getElementById("relative-insights"),
    productViewsElement: document.getElementById("product-views"),
    addToCartElement: document.getElementById("add-to-cart"),
    checkoutElement: document.getElementById("checkout"),
    transactionsElement: document.getElementById("transactions"),

    // Charts
    snapshotChart: null,
    funnelRelativeChart: null,
    funnelAbsoluteChart: null,
    productViewsChart: null,
    addToCartViewsChart: null,
    checkoutViewsChart: null,
    transactionViewsChart: null,

    chartOptions: {
      responsive: true,
      maintainAspectRatio: false,
    },

    init: function() {
      var self = this;

      var fileterData = this.getFilterData();
      this.getReports(fileterData[0], fileterData[1], fileterData[2]);

      $('input[name="fuzzy-date"]').on('change', function() {
        self.generateInsights();
      })

      $('input[name="start-date"], input[name="end-date"]').on('change', function(e) {
        self.generateInsights(true);
      })
    },
    getFilterData: function() {
      var fuzzy_date = $(this.$fuzzy_date).val();
      var start_date = $(this.$start_date).val();
      var end_date = $(this.$end_date).val();
      return [fuzzy_date, start_date, end_date];
    },
    getReports: function(fuzzy_date, start_date, end_date) { 
      var self = this;
      this.showLoadingState();
      
      var data = {
        action: "blaze_insights_get_report",
        fuzzy_date: fuzzy_date,
        start_date: start_date,
        end_date: end_date,
        test_mode: this.test_mode
      };
      $.post(ajaxurl, data, function (response) {
        var json = JSON.parse(response);
        if (json.success == 1 && json.status != "pending") {
          self.data = json;
          self.generateReport();
          self.hideLoadingState();
        }
        if(json.status == "pending") {
          if( self.poll_attempt < 4 ) {
            var delay = Math.pow(2, self.poll_attempt) * 1000;
            console.log('delay', delay);
            setTimeout(function() {
              self.generateInsights();
              self.poll_attempt++;
              console.log('attempting to request again', self.poll_attempt);
            }, delay);
          } else {
            self.show_error = true;
            console.log('failed to load data');
            $('.analytics-container').prepend('ERROR: Could not load data.')
            $('.bi-summary').hide();
          }
        }
      });
    },
    generateInsights: function(date_inputs = false) {
      // this.clearDateRangeInput();
      if(!date_inputs) {
        this.clearDateRangeInput();
      }

      var fuzzy_date = $(this.$fuzzy_date).val();
      var start_date = $(this.$start_date).val();
      var end_date = $(this.$end_date).val();

      if(date_inputs && (start_date == '' || end_date == '') ) {
        return false;
      }

      this.getReports(fuzzy_date, start_date, end_date);
    },
    clearDateRangeInput: function() {
      $(this.$start_date).val('');
      $(this.$end_date).val('');
    },
    generateReport: function() {
      const productViewsData = parseWithFunctions(this.data.charts.stageCharts.PRODUCT_VIEW);
      const addToCartData = parseWithFunctions(this.data.charts.stageCharts.ADD_TO_CART);
      const checkoutData = parseWithFunctions(this.data.charts.stageCharts.CHECKOUT);
      const transactionsData = parseWithFunctions(this.data.charts.stageCharts.TRANSACTION);
      this.destroyCharts();

      // this.setSnapshotChart();
      this.setRelativeFunnelChart();
      // this.setAbsoluteFunnelChart();

      this.productViewsChart = this.populateChart(this.productViewsElement, productViewsData);
      this.addToCartViewsChart = this.populateChart(this.addToCartElement, addToCartData);
      this.checkoutViewsChart = this.populateChart(this.checkoutElement, checkoutData);
      this.transactionViewsChart = this.populateChart(this.transactionsElement, transactionsData);
    },
    destroyCharts: function() {
      if(this.snapshotChart instanceof Chart) {
        this.snapshotChart.destroy()
      }
      if(this.funnelAbsoluteChart instanceof Chart) {
        this.funnelAbsoluteChart.destroy()
      }
      if(this.funnelRelativeChart instanceof Chart) {
        this.funnelRelativeChart.destroy()
      }
      if(this.productViewsChart instanceof Chart) {
        this.productViewsChart.destroy()
      }
      if(this.addToCartViewsChart instanceof Chart) {
        this.addToCartViewsChart.destroy()
      }
      if(this.checkoutViewsChart instanceof Chart) {
        this.checkoutViewsChart.destroy()
      }
      if(this.transactionViewsChart instanceof Chart) {
        this.transactionViewsChart.destroy()
      }
    },
    setSnapshotChart: function() {
      var snapshotContext = this.snapshotElement.getContext("2d");
      this.snapshotChart = new Chart(snapshotContext, parseWithFunctions(this.data.charts.snapshotChart));
    },
    setRelativeFunnelChart: function() {
      const funnelRelativeContext = this.funnelRelativeElement.getContext("2d");
      this.funnelRelativeChart = new Chart(funnelRelativeContext, parseWithFunctions(this.data.charts.relativeFunnelChart));
      this.setRelativeChartAnnotations();
    },
    setAbsoluteFunnelChart: function() {
      const funnelAbsoluteContext = this.funnelAbsoluteElement.getContext("2d");
      this.funnelAbsoluteChart = new Chart(funnelAbsoluteContext, parseWithFunctions(this.data.charts.absoluteFunnelChart));
    },
    getDurationIndicationForRelativeFunnel: function() {
      var data = this.getFilterData();
      var text = '';
      var fuzzy_date = data[0], start_date = data[1], end_date = data[2];
      if(start_date == '' || end_date == '') {
        text = "Primary insight for the last " + fuzzy_date.replace('daysAgo', '') + " days:";
      } else {
        text = "Primary insight for from " + start_date + " to " + end_date + ":";
      }

      var durationElement = document.createElement("h1");
      durationElement.textContent = text;

      return durationElement;
    },
    setRelativeChartAnnotations: function() {
      $(this.relativeInsightsElement).html("")
      $(this.relativeInsightsElement).parent().prev().html(this.getDurationIndicationForRelativeFunnel())

      for (var insight of this.data.insights) {
        var insightElement = document.createElement("div");
        insightElement.classList.add("annotation-item");
        var insightTextElement = document.createElement("span");
        var insightLegendElementWrapper = document.createElement("div");
        insightLegendElementWrapper.classList.add("legend-wrapper");
        var insightLegendElement = document.createElement("span");
        var insightCallToActionElement = document.createElement("a");
        insightLegendElement.classList.add("legend");
        insightLegendElement.classList.add("legend-" + insight.statusName);
        insightTextElement.textContent = insight.annotation + ' ';
        insightCallToActionElement.href = insight.callToAction.link;
        insightCallToActionElement.textContent = insight.callToAction.text;
        insightCallToActionElement.target = "_blank";
        insightLegendElementWrapper.appendChild(insightLegendElement);
        insightElement.appendChild(insightLegendElementWrapper);
        insightTextElement.appendChild(insightCallToActionElement);
        insightElement.appendChild(insightTextElement);
        this.relativeInsightsElement.appendChild(insightElement);
        if(insight.statusName == 'primary' && this.data.insights.length > 1) {
          $(this.relativeInsightsElement).append('<h2>Other Insights:</h2>');
        }
      }
      this.funnelRelativeChart.update();
    },
    populateChart: function (element, chartData) {
      if(chartData) {
        var context = element.getContext("2d");
        var options = chartData;
        var chart = new Chart(context, options);
        $(element).closest('.bi-container').show();
        return chart;
      } else {
        element.style.display = "none";
        $(element).closest('.bi-dialog').hide();
      }
    },
    getChartAnnotation: function (statusName) {
      var chartAnnotation;
      switch (statusName) {
        case "primary":
          chartAnnotation = {
            type: "point",
            radius: 20,
            borderWidth: 8,
            borderColor: "red",
            backgroundColor: "transparent",
          };
          break;
        case "secondary":
          chartAnnotation = {
            type: "point",
            radius: 20,
            borderWidth: 4,
            borderColor: "#f39300",
            backgroundColor: "transparent",
          };
          break;
        case "tertiary":
          chartAnnotation = {
            type: "point",
            radius: 17,
            borderWidth: 4,
            borderColor: "#ffd500",
            backgroundColor: "transparent",
          };
          break;
      }
      return chartAnnotation;
    },
    showLoadingState: function() {
      $(this.relativeInsightsElement).html("");
      $(this.conversionRateElementOverall).closest('.bi-content').addClass('is-loading');
      $(this.snapshotElement).closest('.bi-content').addClass('is-loading');
      $(this.funnelRelativeElement).closest('.bi-content').addClass('is-loading');
      $(this.funnelAbsoluteElement).closest('.bi-content').addClass('is-loading');
      $(this.productViewsElement).closest('.bi-content').addClass('is-loading');
      $(this.addToCartElement).closest('.bi-content').addClass('is-loading');
      $(this.checkoutElement).closest('.bi-content').addClass('is-loading');
      $(this.transactionsElement).closest('.bi-content').addClass('is-loading');
      $(this.relativeInsightsElement).addClass('is-loading');
    },
    hideLoadingState: function() {
      $(this.conversionRateElementOverall).closest('.bi-content').removeClass('is-loading');
      $(this.snapshotElement).closest('.bi-content').removeClass('is-loading');
      $(this.funnelRelativeElement).closest('.bi-content').removeClass('is-loading');
      $(this.funnelAbsoluteElement).closest('.bi-content').removeClass('is-loading');
      $(this.productViewsElement).closest('.bi-content').removeClass('is-loading');
      $(this.addToCartElement).closest('.bi-content').removeClass('is-loading');
      $(this.checkoutElement).closest('.bi-content').removeClass('is-loading');
      $(this.transactionsElement).closest('.bi-content').removeClass('is-loading');
      $(this.relativeInsightsElement).removeClass('is-loading');
    },
  }

  blaze_insights.init();

  $('.update-blaze-insights-plugin').on( 'click', function() {
    var self = this;
    var data = {
      action: 'update_blaze_insights_plugin',
    }
    $(this).addClass('is-loading');
    $.post(ajaxurl, data, function(response) {
      alert("Plugin successfully updated.");
      $(self).removeClass('is-loading');
      window.location.reload();
    })
  });
});