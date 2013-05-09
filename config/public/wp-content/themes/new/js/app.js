
(function($) {
  $(function() {

    // Page initialization functions

    // Clip WP figure photos to the right edge
    if (clip_captions)
      clip_captions();

    var currentSel;
    var lastOrg;
    // Fundraiser search box
    $("#fr-search").select2({
        placeholder: "Search for a fundraiser",
        minimumInputLength: 1,
        ajax: {
            url: "//" + window.location.host + "/ajax-campaign.php",
            dataType: 'jsonp',
            quietMillis: 100,
            data: function (term, page) { // page is the one-based page number tracked by Select2
                return {
                    q: term, //search term
                    page_limit: 10, // page size
                    page: page // page number
                };
            },
            results: function (data, page) {
                lastOrg = null;
                var more = (page * 10) < data.total; // whether or not there are more results available

                // Support paging? more: true
                return {results: data.orgs, more: false};
            }
        },
        formatResult: function(org) {
          var markup = "";

          if (!lastOrg || (lastOrg.type != org.type))
            markup += "<div class='select2-hdr'>" + org.type + "</div>";
          lastOrg = org;

          markup += "<div class='clearfix'>";
          if (org.image !== undefined) {
              markup += "<img class='org-image' src='//res.cloudinary.com/seeyourimpact/image/fetch/w_50,h_50,c_thumb,g_faces/" + org.image + "'/>";
          }
          markup += "<div class='org-info'><span class='org-name'>" + org.name + "</span>";
          if (org.location !== undefined) {
              markup += "<div class='org-location'>" + org.location + "</div>";
          }
          markup += "</div></div>";
          return markup;
        },
        formatSelection: function(org) {
          currentSel = org;
          return org.name
        },
        formatInputTooShort: function(term,minLength) {
          return "Please enter the name of a fundraiser or organization, or <a href='/partners'>see all charity partners</a>.";
        },
        formatNoMatches: function(term,minLength) {
          return "No matches found.";
        }
    }).on('change', function(ev, val) {
      if (!currentSel || !currentSel.url)
        return;
      window.location = currentSel.url;
    });


    // Resize equal-sized sections when window resizes
    $(window).resize(function() {
      $('#main').siblings().andSelf().balance();
      $('.row.balanced>*[class*="span"]').balance();
    }).trigger('resize');

  });

  $.fn.eqHeights = function() {
    if ($(this).length < 2) return;
    var heights = $(this).height('auto').map(function() { 
      return $(this).outerHeight(); 
    }).get(); // box model -> outerHeight
    var tallest = Math.max.apply(Math, heights);
    $(this).height( tallest );
    // $(this).css( $.browser.msie && $.browser.version == 6.0 ? { height : tallest } : { minHeight : tallest });
  }

  $.fn.balance = function() {
    if ($(this).length == 0) return;
    var row = [];
    var y;
    $(this).height('auto').each(function(i,el) {
      var top = $(el).position().top;
      if (top !== y) {
        $(row).eqHeights();
        row = [];
      }
      y = top;
      row.push(el);
    });
    $(row).eqHeights();
  };

})(jQuery);
