jQuery(document).ready(function ($) {
  "use strict";
  window.google_views = {};

  var blaze_insights_wizard = {
    
    test_mode: window.bi_test_mode,

    $viewContainerSelector: '.view-container',
    $viewSelector: 'select#view-selector',
    $wizardContentSelector: '.wizard-content',

    init: function() {
      var self = this;
      if(this.getCurrentStep() == 2) {
        var data = {
          action: "get_google_views",
        };
        $(self.$viewContainerSelector).addClass('is-loading');
        $.post(ajaxurl, data, function (response) {
          var json = JSON.parse(response);
          if (json.success == 1 && json.views.length > 0) {
            json.views.forEach(function(view) {
              window.google_views[view.viewId] = view;
            })
            var options = json.views.map(function(view) {
              return '<option value="'+view.viewId+'">'+view.websiteURL+' ('+view.name+')</option>';
            })
            $(self.$viewSelector).html(options);
            $(self.$viewContainerSelector).removeClass('is-loading');
          }
        });
      } else if (this.getCurrentStep() == 3) {
        self.fetchIndustry();
      }

      this.showCurrentStepContent();

      $(".skip-btn").on("click", function (e) {
        e.preventDefault();
        var data = {
          action: "skip_wizard",
        };
    
        $.post(ajaxurl, data, function (response) {
          var json = JSON.parse(response);
          if (json.success == 1) {
            window.location.href = wizard_object.admin_url;
          }
        });
      });
    
      $(".google-signin").on("click", function (e) {
        e.preventDefault();
        self.showPageLoading();
        var data = {
          action: "blaze_insights_google_authentication",
        };
    
        $.post(ajaxurl, data, function (response) {
          var json = JSON.parse(response);
          if (json.success == 1) {
            window.location.href = json.url;
          } else {
            alert(json.message);
            self.hidePageLoading();
          }
        });
      });
    
      $(".select-view-btn").on("click", function (e) {
        e.preventDefault();
        self.showPageLoading();
        var viewSelector = $('select#view-selector');
        var selectedView = viewSelector.val();
        var data = {
          action: "set_google_view",
          view: window.google_views[selectedView]
        };
    
        $.post(ajaxurl, data, function (response) {
          var json = JSON.parse(response);
          if (json.success == 1) {
            // window.location.href = wizard_object.blaze_insights_dashboard;
            self.showCurrentStepContent(3);
            self.fetchIndustry();
            self.hidePageLoading();
          } else {
            alert('Unable to set view.');
            self.hidePageLoading();
          }
        });
      });

      $("form.form-set-contact-email").on('submit', function(e) {
        e.preventDefault();
        self.showPageLoading();
        
        var contact_email = $('input#contact-email').val();
        var industry = $('select#industry-vertical').val();

        var data = {
          action: "set_industry",
          contact_email: contact_email,
          industry: industry,
        };

        $.post(ajaxurl, data, function (response) {
          var json = JSON.parse(response);
          if (json.success == 1) {
            window.location.href = '/wp-admin/admin.php?page=blaze-insights';
          } else {
            alert('Unable to set industry.');
            self.hidePageLoading();
          }
        });
      })
    },
    getCurrentStep: function() {
      var url_string = window.location.href
      var url = new URL(url_string);
      var step = url.searchParams.get("step");
      if(!step && window.step) {
        step = window.step;
      }
      if(!step && !window.step) {
        step = 1;
        this.setCurrentStep(step)
      }
      return step;
    },
    setCurrentStep: function(step) {
      window.step = step;
      return step
    },
    showCurrentStepContent: function(step = false) {
      if(!step) {
        step = this.getCurrentStep();
      }
      $('.wizard-step').removeClass('current-step')
      $('.wizard-step-' + step).addClass('current-step')
      $('.step').removeClass('current-step')
      $('.step-' + step).addClass('current-step')
    },
    showPageLoading: function() {
      $(this.$wizardContentSelector).addClass('is-loading');
    },
    hidePageLoading: function() {
      $(this.$wizardContentSelector).removeClass('is-loading');
    },
    fetchIndustry: function() {
      var data = {
        action: "get_selected_industry_vertical"
      };
      $("select#industry-vertical").html('');
      $('span.industry-container').addClass("is-loading");
      $.get(ajaxurl, data, function (response) {
        response = JSON.parse(response);
        $("select#industry-vertical").html(response.industry_list_options);
        $("#contact-email").val(response.contact_email);
        $('span.industry-container').removeClass("is-loading");
      });
    }
  }

  blaze_insights_wizard.init();
});
