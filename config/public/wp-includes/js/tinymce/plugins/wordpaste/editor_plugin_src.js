/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */ 
var TinyMCE_WordPastePlugin = {
	getInfo : function() {
		return {
			longname : 'Paste Word content',
			author : 'DidiSoft Ltd.',
			authorurl : 'http://didisoft.com',
			infourl : 'http://didisoft.com',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	initInstance : function(inst) {
		var fCleanUp = tinyMCE.cleanupHTMLCode;		
		tinyMCE.cleanupHTMLCode = function(s) {		
			//s = TinyMCE_WordPastePlugin._wordClean(s);		
			var re = new RegExp('(<xml>)([^<>]*)(<\/xml>)', 'gi');
			var s = s.replace(re, ""); 
			var re = new RegExp('(<style>)([^<>]*)(<\/style>)', 'gi');
			var s = s.replace(re, ""); 
			var re = new RegExp('<(!--)([^>]*)(--)>', 'gi');
			var s = s.replace(re, ""); 			
			s = TinyMCE_WordPastePlugin._insertWordContent(s);
			s = fCleanUp(s);
			return s;
		}
		tinyMCE.addEvent(inst.getBody(), "paste", TinyMCE_WordPastePlugin._handlePasteEvent);
	},

	handleEvent : function(e) {
		return true;
	},

	getControlHTML : function(cn) { 
		return ''; 
	},

	execCommand : function(editor_id, element, command, user_interface, value) { 
		// Pass to next handler in chain 
		return false; 
	},

	// Private plugin internal methods

	_handlePasteEvent : function(e) {
		switch (e.type) {
			case "paste":
				window.setTimeout('tinyMCE.execCommand("mceCleanup");', 100);
				return true;
		}

		return true;
	},

	_insertWordContent : function(content) { 
		if (content && content.length > 0) {
			// Cleanup Word content
			var bull = String.fromCharCode(8226);
			var middot = String.fromCharCode(183);
			var cb;

			if ((cb = tinyMCE.getParam("paste_insert_word_content_callback", "")) != "")
				content = eval(cb + "('before', content)");

			var rl = tinyMCE.getParam("paste_replace_list", '\u2122,<sup>TM</sup>,\u2026,...,\u201c|\u201d,",\u2019,\',\u2013|\u2014|\u2015|\u2212,-').split(',');
			for (var i=0; i<rl.length; i+=2)
				content = content.replace(new RegExp(rl[i], 'gi'), rl[i+1]);

			if (tinyMCE.getParam("paste_convert_headers_to_strong", false)) {
				content = content.replace(new RegExp('<p class=MsoHeading.*?>(.*?)<\/p>', 'gi'), '<p><b>$1</b></p>');
			}

			content = content.replace(new RegExp('tab-stops: list [0-9]+.0pt">', 'gi'), '">' + "--list--");
			content = content.replace(new RegExp(bull + "(.*?)<BR>", "gi"), "<p>" + middot + "$1</p>");
			content = content.replace(new RegExp('<SPAN style="mso-list: Ignore">', 'gi'), "<span>" + bull); // Covert to bull list
			content = content.replace(/<o:p><\/o:p>/gi, "");
			content = content.replace(new RegExp('<br style="page-break-before: always;.*>', 'gi'), '-- page break --'); // Replace pagebreaks
			content = content.replace(new RegExp('<(!--)([^>]*)(--)>', 'g'), "");  // Word comments

			if (tinyMCE.getParam("paste_remove_spans", true))
				content = content.replace(/<\/?span[^>]*>/gi, "");

			if (tinyMCE.getParam("paste_remove_styles", true))
				content = content.replace(new RegExp('<(\\w[^>]*) style="([^"]*)"([^>]*)', 'gi'), "<$1$3");

			content = content.replace(/<\/?font[^>]*>/gi, "");

			// Strips class attributes.
			switch (tinyMCE.getParam("paste_strip_class_attributes", "all")) {
				case "all":
					content = content.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3");
					break;

				case "mso":
					content = content.replace(new RegExp('<(\\w[^>]*) class="?mso([^ |>]*)([^>]*)', 'gi'), "<$1$3");
					break;
			}

			content = content.replace(new RegExp('href="?' + TinyMCE_WordPastePlugin._reEscape("" + document.location) + '', 'gi'), 'href="' + tinyMCE.settings['document_base_url']);
			content = content.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3");
			content = content.replace(/<\\?\?xml[^>]*>/gi, "");
			content = content.replace(/<\/?\w+:[^>]*>/gi, "");
			content = content.replace(/-- page break --\s*<p>&nbsp;<\/p>/gi, ""); // Remove pagebreaks
			content = content.replace(/-- page break --/gi, ""); // Remove pagebreaks

	//		content = content.replace(/\/?&nbsp;*/gi, ""); &nbsp;
	//		content = content.replace(/<p>&nbsp;<\/p>/gi, '');

			if (!tinyMCE.settings['force_p_newlines']) {
				content = content.replace('', '' ,'gi');
				content = content.replace('</p>', '<br /><br />' ,'gi');
			}

			if (!tinyMCE.isMSIE && !tinyMCE.settings['force_p_newlines']) {
				content = content.replace(/<\/?p[^>]*>/gi, "");
			}

			content = content.replace(/<\/?div[^>]*>/gi, "");

			// Convert all middlot lists to UL lists
			if (tinyMCE.getParam("paste_convert_middot_lists", true)) {
				var div = document.createElement("div");
				div.innerHTML = content;

				// Convert all middot paragraphs to li elements
				var className = tinyMCE.getParam("paste_unindented_list_class", "unIndentedList");

				while (TinyMCE_WordPastePlugin._convertMiddots(div, "--list--")) ; // bull
				while (TinyMCE_WordPastePlugin._convertMiddots(div, middot, className)) ; // Middot
				while (TinyMCE_WordPastePlugin._convertMiddots(div, bull)) ; // bull

				content = div.innerHTML;
			}

			// Replace all headers with strong and fix some other issues
			if (tinyMCE.getParam("paste_convert_headers_to_strong", false)) {
				content = content.replace(/<h[1-6]>&nbsp;<\/h[1-6]>/gi, '<p>&nbsp;&nbsp;</p>');
				content = content.replace(/<h[1-6]>/gi, '<p><b>');
				content = content.replace(/<\/h[1-6]>/gi, '</b></p>');
				content = content.replace(/<b>&nbsp;<\/b>/gi, '<b>&nbsp;&nbsp;</b>');
				content = content.replace(/^(&nbsp;)*/gi, '');
			}

			content = content.replace(/--list--/gi, ""); // Remove --list--

			if ((cb = tinyMCE.getParam("paste_insert_word_content_callback", "")) != "")
				content = eval(cb + "('after', content)");
		}
		
		return content;	
	},

	_reEscape : function(s) {
		var l = "?.\\*[](){}+^$:";
		var o = "";

		for (var i=0; i<s.length; i++) {
			var c = s.charAt(i);

			if (l.indexOf(c) != -1)
				o += '\\' + c;
			else
				o += c;
		}

		return o;
	},

	_convertMiddots : function(div, search, class_name) {
		var mdot = String.fromCharCode(183);
		var bull = String.fromCharCode(8226);

		var nodes = div.getElementsByTagName("p");
		var prevul;
		for (var i=0; i<nodes.length; i++) {
			var p = nodes[i];

			// Is middot
			if (p.innerHTML.indexOf(search) == 0) {
				var ul = document.createElement("ul");

				if (class_name)
					ul.className = class_name;

				// Add the first one
				var li = document.createElement("li");
				li.innerHTML = p.innerHTML.replace(new RegExp('' + mdot + '|' + bull + '|--list--|&nbsp;', "gi"), '');
				ul.appendChild(li);

				// Add the rest
				var np = p.nextSibling;
				while (np) {
			        // If the node is whitespace, then
			        // ignore it and continue on.
			        if (np.nodeType == 3 && new RegExp('^\\s$', 'm').test(np.nodeValue)) {
			                np = np.nextSibling;
			                continue;
			        }

					if (search == mdot) {
					        if (np.nodeType == 1 && new RegExp('^o(\\s+|&nbsp;)').test(np.innerHTML)) {
					                // Second level of nesting
					                if (!prevul) {
					                        prevul = ul;
					                        ul = document.createElement("ul");
					                        prevul.appendChild(ul);
					                }
					                np.innerHTML = np.innerHTML.replace(/^o/, '');
					        } else {
					                // Pop the stack if we're going back up to the first level
					                if (prevul) {
					                        ul = prevul;
					                        prevul = null;
					                }
					                // Not element or middot paragraph
					                if (np.nodeType != 1 || np.innerHTML.indexOf(search) != 0)
					                        break;
					        }
					} else {
					        // Not element or middot paragraph
					        if (np.nodeType != 1 || np.innerHTML.indexOf(search) != 0)
					                break;
				        }

					var cp = np.nextSibling;
					var li = document.createElement("li");
					li.innerHTML = np.innerHTML.replace(new RegExp('' + mdot + '|' + bull + '|--list--|&nbsp;', "gi"), '');
					np.parentNode.removeChild(np);
					ul.appendChild(li);
					np = cp;
				}

				p.parentNode.replaceChild(ul, p);

				return true;
			}
		}

		return false;
	},
};

tinyMCE.addPlugin("wordpaste", TinyMCE_WordPastePlugin);
