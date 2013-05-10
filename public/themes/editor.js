var debug = false;
var cache;

$(document).ready(function() {
    // pull in the "theme" from the query string
    $('#theme_id').val($.url().param('theme'));

    // some fields aren't editable yet
    $(['start_banner', 'start_label', 'body_label', 'banner']).each(function() {
        $('[id="' + this + '"]').replaceWith($('#tbd').html());
    });

    organization_dropdown();

    // append a [?] to the end of each label to make it clear that there is
    // hover-over text
    $('label[title]').qtip({
      style: { name: 'green' },
      position: {
        corner: { target: 'mouse', tooltip: 'bottomLeft'}
      },
      show: { delay: 0 }
    }).append('&nbsp;<span style="color:blue; text-decoration:underline; font-size:80%">[?]</span>');

    $.getJSON('web_service.php?allthemes=1', function(json) {
        cache = json;
        load_autocomplete();

        if ($('#theme_id').val()) {
            load_theme();
        }

        $('#gifts').change(function() {
            $('div', $(this).parent()).toggle('fast');
        });

        $('#show_theme').click(toggle_preview);
        $('#show_start').click(toggle_preview);
        toggle_preview();

        $('#save_theme').click(save_theme);

        $('#cc_submit').click(create_campaign);
    });
});

function organization_dropdown() {
    // replace #organization with a drop-down
    $.getJSON( 'web_service.php?orgs', {}, function(json) {
        html = '<select id="org" class="settable" name="org">';
        html = html + '<option value="">(none)</option>';
        $.each(json, function(i,name) {
            html = html + '<option value="' + name + '">' + name + '</option>';
        });
        html = html + '</select>';
        $('#org').replaceWith(html);
    });
}

function reload_iframe(selector) {
    if ( ! $.url().param('no_preview')) {
        if (debug) console.log("reload_iframe: " + selector);
        $(selector).attr('src', $(selector).attr('src'));
    }
}

function toggle_preview() {
    if ($('#preview_theme').css('display') == "none") {
        // we are previewing start page
        if (debug) console.log("toggling preview to fundraiser");
        $('#preview_start').hide();
        $('#preview_theme').show();
        reload_iframe('#preview_theme iframe');
        $('#show_start').show();
        $('#show_theme').hide();
    }
    else {
        // we are previewing theme page (fundraiser)
        if (debug) console.log("toggling preview to start page");
        $('#preview_theme').hide();
        $('#preview_start').show();
        reload_iframe('#preview_start iframe');
        $('#show_theme').show();
        $('#show_start').hide();
    }
}

function load_theme() {
    // this function clears the table, gets the JSON for the selected theme,
    // displays the theme elements as editable fields, and adds a final row
    // to the table to add new elements

    if (debug) console.log("loading theme");

    var valid = false;
    var theme = $('#theme_id').val();

    $.each(cache, function() {
        if (theme == this.name) {
            $('#status').text('you picked theme: ' + $('#theme_id').val());
            valid = true;
        }
    });

    // if the theme is unknown, nothing else to be done
    if (!valid) {
        $('#status').text('that is not a valid theme');
        return;
    }

    // clear all inputs
    $('.settable').val('');
    $('.settable[type="checkbox"]').prop('checked', false);

    // go get the rows from mysql
    $.getJSON( 'web_service.php', { theme: theme }, function(json) {
        if (json) {
            if (debug) console.log("loading theme: ", json);
            $.each(json, function(k, v) {
                sel = '.settable[id="' + k + '"]';
                if ($(sel).length) {
                    // this covers simple settings, individual checkboxes and text
                    if ($(sel).prop('type') == 'checkbox') {
                        $(sel).prop('checked', (v == "true" ? true : false));
                    }
                    else {
                        $(sel).val(v);
                    }
                }
                else {
                    // start figuring out more complex stuff (multi-checkboxes)
                    sel = 'ul[id="' + k + '"]';
                    if ($(sel).length) {
                        $.each(v, function(opt, val) {
                            $('[id="' + k + '"] .settable[type="checkbox"][name="' + opt + '"]').
                                attr('checked', (val == "true" ? true : false) );
                        });
                    }
                    else if (k == 'h20' || k == 'facebook') {
                        $.each(v, function(opt, val) {
                            $('.settable[id="' + k + '_' + opt + '"]').val(val);
                        });
                    }
                    else {
                        // don't know what to do with this key in the json bag,
                        // just ignore
                        console.log("selector '" + sel + "' didn't match anything");
                    }
                }
            });

            // set the ifram
            if ( ! $.url().param('no_preview')) {
                $('#preview_theme iframe').attr('src', '/members/ryang/?theme=' + $('#theme_id').val());
                $('#preview_start iframe').attr('src', '/start/?theme=' + $('#theme_id').val());
            }
        }

        $('#save_theme').removeAttr('disabled');
    });
}

function get_themedata() {
    var inputs = {};

    $('.settable').each(function(i) {
        if ($(this).attr('type') == "checkbox") {
            if ($(this).parent().is('li')) {
                var field = $(this).parent().parent().attr('id');
                if (!inputs[field]) inputs[field] = {};
                inputs[field][$(this).attr('name')] = $(this).is(':checked');
            }
            else {
                inputs[$(this).attr('id')] = $(this).is(':checked');
            }
        }
        else {
            inputs[$(this).attr('id')] = $(this).val();
        }
    });

    return inputs;
}

function save_theme() {
    // as you might suspect, this saves the theme

    if (debug) console.log("saving theme");

    var inputs = get_themedata();
    inputs.theme = $('#theme_id').val();

    if (debug) console.log("saving theme: ", inputs);

    $('#save_theme').attr('disabled', 'disabled').after('<img src="progress-48x48.gif"/>');
    $.post('web_service.php', inputs, function(response) {
        $('#status').text("Your changes were saved.");
        $('#save_theme').next().remove();
        $.getJSON('web_service.php?allthemes=1', function(json) {
            cache = json;
            load_theme();
        });
    });
}

function load_autocomplete() {
    // hooks up the global "cache" to the #theme_id field with auto-completion
    $('#theme_id').autocomplete({
        source: function(req, resp) {
            // 'matches' is going to collect the theme ids of the themes whose
            // descriptions contain 'req.term'
            if (!req.term) {
                return;
            }

            $.getJSON('web_service.php?allthemes=1', function(json) {
                var matches = [];
                cache = json;
                req.term = req.term.toLowerCase();
                if (debug) console.log('matching: ' + req.term);

                $.each(cache, function() {
                    var hit = 0;
                    if (this.desc && this.desc.toLowerCase().search(req.term) >= 0) {
                        if (debug) console.log('hit on desc: ' + this.desc);
                        hit = hit + 1;
                    }
                    if (this.name && this.name.toLowerCase().search(req.term) >= 0) {
                        if (debug) console.log('hit on name: ' + this.name);
                        hit = hit + 1;
                    }

                    if (hit > 0) {
                        matches.push({
                            label: this.name,
                            value: this.name
                        });
                    }
                });

                if (debug) console.log('found ' + matches.length + ' matches');
                resp(matches);
            });
        },
        select: function(event, ui) {
            load_theme();
        },
        minLength: 0
    });
}

function create_campaign() {
    if ( ! $('#org').val().trim()) {
        $('#cc_status').append('<p class="error">Organization is required, select one from the drop-down above.</p>');
        return;
    }

    if ( ! $('#theme_id').val().trim()) {
        $('#cc_status').append('<p class="error">Theme name is required, fill one in at the top of the page, under "theme info".</p>');
        return;        
    }

    $('#cc_status').html('&nbsp;');
    $('#cc_submit').attr('dixsabled', 'disabled').after('<img src="progress-48x48.gif"/>');

    var inputs = get_themedata();
    inputs.theme = $('#theme_id').val();
    inputs.teams = $('#cc_teams').val();

    $.post('web_service.php', inputs, function(response) {
        $('#cc_submit').removeAttr('disabled');
        $('#cc_submit').next().remove();

        if (response.status = 'ok') {
            $('#cc_status').html('Your campaign was created, <a href="' + response.url + '" target="_blank">click here</a> to view it.</p>');
        }
        else {
            $('#cc_status').html('There was an error creating your campaign:' + response.reason);
        }
    }, 'json');
}
